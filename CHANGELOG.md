# @deegitalbe/laravel-trustup-io-notifications-contracts

## 3.9.1

### Patch Changes

- bde4224: Wire pro_name into the email sender name for whitelabel notifications

  `WhitelabelDemandReceivedNotificationData` and
  `WhitelabelNewChatMessageNotificationData` are `final` and relied on the
  default `RendersEmail` trait, which built `EmailContent` without the
  `senderName` added in `3.9.0`. Since both classes are `final` and their
  `emailVariables()` override point is `protected`, mkp-backend had no way to
  supply it from outside the package.

  Both classes now override `toEmail()` to pass their existing `pro_name`
  property as `EmailContent::$senderName`. No new field and no constructor
  change: `pro_name` was already there for the email template variables.

## 3.9.0

### Minor Changes

- 4f45716: Add an optional sender display name to EmailContent

  `EmailContent` gains an optional `senderName` field (`?string`, default `null`)
  so a caller can request that an outgoing email display a name (e.g. a
  professional's name) in the `From` header while the technical sender address
  stays unchanged:

  ```
  De : A3D CONSTRUCT <noreply@trustup.be>
  ```

  Backward compatible: every existing `EmailCapable` implementation leaves this
  field unset, which is identical to today's behavior. The service sanitizes
  the value before using it (strips `<`, `>`, `"`, commas and line breaks to
  prevent From-header injection, truncates to 78 characters) and falls back to
  the plain configured address when nothing meaningful is left after
  sanitization.

## 3.8.0

### Minor Changes

- e5c4656: Add dedicated whitelabel notification types for the website demand flow, and remove pro identity fields from marketplace DTOs

  - New `NotificationType::WhitelabelDemandReceivedNotification` and `WhitelabelNewChatMessageNotification`, backed by `WhitelabelDemandReceivedNotificationData` and `WhitelabelNewChatMessageNotificationData`
  - These carry `pro_name`, `pro_phone`, `pro_email` and `pro_logo` (with `has_pro_logo` derived in `emailVariables()`), so a website demand can render the professional's identity instead of TrustUp's
  - `MarketplaceDemandReceivedNotificationData` and `MarketplaceNewChatMessageForCustomerNotificationData` no longer carry these pro fields: the marketplace flow keeps the TrustUp identity and has no use for them
  - A template can only be bound to one Postmark layout, so the marketplace and website flows now use separate `NotificationType` cases instead of sharing one data class for both identities

## 3.7.0

### Minor Changes

- 91ed9c1: Deliver push notifications to professionals for new demands, chat messages, and response reminders

  - Sends the new-demand, new-chat-message, and response-reminder notifications by push to professionals with a registered device, in addition to email
  - Tags every push payload with `notification_type` so the mobile app can route a tap to the right screen, ending the send in error if the type cannot be determined
  - Coerces non-text accompanying values to text before handing them to the push provider, and ends the send in error on an unconvertible value or a rejected payload key instead of failing silently or exhausting retries
  - Keeps the freemium new-demand notification email-only, since its recipients have no registered devices

## 3.6.0

### Minor Changes

- b682c06: Add pro fields to marketplace demand-received and new-chat-message notification DTOs

  - `MarketplaceDemandReceivedNotificationData` and `MarketplaceNewChatMessageForCustomerNotificationData` now accept nullable `pro_name`, `pro_phone`, `pro_email` and `pro_logo`
  - `has_pro_logo` is derived automatically from `pro_logo` and exposed in the email template variables

## 3.5.0

### Minor Changes

- b23de44: Add the ToolsProSecondReminderNotification type for the second pro reminder

  - Adds `ToolsProSecondReminderNotificationData`, mirroring `ToolsProResponseReminderNotificationData` with an extra nullable `city` field, transmitted raw (no PHP normalization)
  - Registers `NotificationType::ToolsProSecondReminderNotification` (`tools.pro-second-reminder.notification`), mapped to its data class and to `Source::Tools`

## 3.4.0

### Minor Changes

- cc6ad4c: Add cometchat_group_guid and locale to MarketplaceNewChatMessageForCustomerNotificationData

  - New `locale` (required) and `cometchat_group_guid` (nullable) constructor properties, so consumers can build a deep-link to the CometChat conversation (`/{locale}/espace-membre/messages?conversation={cometchat_group_guid}`).
  - `locale` has no default, so any caller still using positional arguments for this DTO must be updated to pass it.

## 3.3.1

### Patch Changes

- 0a8f555: Default Kafka compression to none instead of lz4

  - Azure Event Hubs (Standard tier) silently rejects lz4-compressed produce requests, so any notification large enough to be compressed never reached the topic. The default is now `none`, which produces reliably across Event Hubs and Redpanda.

## 3.3.0

### Minor Changes

- 2a16b5b: Own the Kafka transport options in the KafkaFactory

  - The factory now sets the producer compression codec (default lz4), the producer message size cap (default 1MB, the Event Hubs limit) and the consumer offset reset (default latest) explicitly on the builder, read from the package's own config.
  - These previously fell through to `mateusjunges/laravel-kafka`'s `config/kafka.php` defaults per host app, so two apps could diverge (e.g. one compressing snappy, another none). The factory is now the single source of truth for the notifications transport, independent of any host `config/kafka.php`.
  - New env keys: `TRUSTUP_IO_NOTIFICATIONS_KAFKA_COMPRESSION`, `TRUSTUP_IO_NOTIFICATIONS_KAFKA_OFFSET_RESET`, `TRUSTUP_IO_NOTIFICATIONS_KAFKA_MESSAGE_MAX_BYTES`.

## 3.2.0

### Minor Changes

- f4562d2: Own the Kafka connection layer in the contracts package

  - Add a `KafkaFactory` that builds producers and consumers with SASL applied from the package's own config, so any host app (client or service) authenticates against SASL brokers (Azure Event Hubs) without wiring the transport itself.
  - Publish a `trustup-io-notifications-contracts` config holding the Kafka connection (brokers, security protocol, SASL, consumer group id), the four topics, and the source, read from `TRUSTUP_IO_NOTIFICATIONS_*` env keys.
  - Fail loud with `MissingKafkaCredentialsException` when the security protocol expects SASL but credentials are missing, instead of silently connecting unauthenticated.

## 3.1.0

### Minor Changes

- c7e1031: Add ToolsNewDemandForProfessionalFreemiumNotification enum case and data class

  - Add `NotificationType::ToolsNewDemandForProfessionalFreemiumNotification` enum case
  - Add `ToolsNewDemandForProfessionalFreemiumNotificationData` class carrying the notification payload

## 3.0.0

### Major Changes

- d74da23: Remove legacy_conversation_id from MarketplaceNewChatMessageForCustomerNotificationData

  - Breaking: `legacy_conversation_id` is removed from the constructor of `MarketplaceNewChatMessageForCustomerNotificationData`; the new constructor signature is `base_url`, `demand_id`, `claim_token` (nullable). The field only made sense for migrated legacy conversations and is no longer structural data for the notification.
  - `fromArray()` silently ignores a lingering `legacy_conversation_id` key in payloads produced by producers not yet updated, so no data migration is required.

## 2.0.0

### Major Changes

- 1f193ba: Adjust marketplace notification data fields following the base_url change

  - `MarketplaceDemandReceivedNotificationData` gains a nullable `first_name` field.
  - `MarketplaceUserAssignmentNotificationData` gains a nullable `claim_token` field.
  - `MarketplaceAssignationActivationNotificationData` gains a nullable `claim_token` field, replacing the removed `action_url` field.
  - Breaking: `action_url` is removed from `MarketplaceUserReassignNotificationData`; callers must rely on the existing nullable `claim_token` instead.

## 1.0.0

### Major Changes

- 493c1b4: Require a base_url field on every notification data class

  - Every `NotificationData` implementation in `src/Data/` now takes a required `base_url` constructor argument, letting email templates build absolute links.
  - Breaking: existing callers constructing these classes directly, or calling their `fromArray()`, must now provide `base_url`.

## 0.14.0

### Minor Changes

- 0d91807: Add unclaimed demand reminder notification type

  - Add `MarketplaceUnclaimedDemandReminderNotificationData` (EmailCapable)
  - Add `NotificationType::MarketplaceUnclaimedDemandReminderNotification` enum case
  - Wire the new case into `dataClass()` and `source()` resolution

## 0.13.0

### Minor Changes

- 9b248a6: Add marketplace user-assignment notification type

  - Add `MarketplaceUserAssignmentNotificationData` carrying `demand_id` and `professional_count`, email-capable
  - Add `NotificationType::MarketplaceUserAssignmentNotification` enum case (`marketplace.user-assignment.notification`)
  - Map the new type to `Source::Marketplace` and its dedicated data class
  - Restrict supported channels for this type to email only

## 0.12.0

### Minor Changes

- c875cbf: Add ToolsNewDemandForProfessionalNotification type

  - Introduce `ToolsNewDemandForProfessionalNotificationData`, an email-capable
    notification DTO carrying the demand, professional, workfield, city, title,
    and description.
  - Add the `ToolsNewDemandForProfessionalNotification` enum case, wired into
    `dataClass()` and `source()` (Source::Tools).
  - Notifies a professional that an incoming demand matches their profile.

## 0.11.0

### Minor Changes

- 75f994e: Add marketplace satisfaction survey notification contract

  - Add `MarketplaceSatisfactionSurveyNotificationData`, a new `EmailCapable` notification data class carrying `demand_id` and `satisfaction_token`.
  - Add the `MarketplaceSatisfactionSurveyNotification` case to `NotificationType`.
  - Wire the new case into `dataClass()` and `source()` (resolves to `Source::Marketplace`).

## 0.10.0

### Minor Changes

- 4a542d7: Add ToolsNewChatMessageForProfessional notification type

  - Add `ToolsNewChatMessageForProfessionalNotificationData` (EmailCapable) with `demand_id` and `demand_professional_id` integer fields
  - Add `NotificationType::ToolsNewChatMessageForProfessionalNotification` enum case
  - Wire the new case into `dataClass()`, mapping it to its data class
  - Wire the new case into `source()`, mapping it to the Tools source

## 0.9.0

### Minor Changes

- f6d5549: Add the marketplace assignation-activation notification type

  - Add the `marketplace.assignation-activation.notification` type with `MarketplaceAssignationActivationNotificationData` (`demand_id`, `action_url`), bound to the marketplace source, email only.

## 0.8.0

### Minor Changes

- 82b862b: Add marketplace new chat message notification data for customers

  - Add `MarketplaceNewChatMessageForCustomerNotificationData` (EmailCapable) with `demand_id`, `legacy_conversation_id`, and optional `claim_token` fields
  - Add `NotificationType::MarketplaceNewChatMessageForCustomerNotification` enum case
  - Wire the new type into `dataClass()`, `source()` (Marketplace), and `supportedChannels()` (email only)

## 0.7.0

### Minor Changes

- 90e41e4: Point the demand-transmitted email at the client's own demand

  - Replace `pro_slug` with `demand_id` (int) and `claim_token` in `MarketplaceDemandTransmittedNotificationData`; the payload is now `first_name`, `pro_name`, `demand_id`, `claim_token`
  - The email's call to action linked to the contractor page with a review modal, copy-pasted from the review-request template, which contradicted its own copy ("suivre l'avancement depuis votre espace membre"). It now opens the demand in the member area, authorised by the claim token
  - The four Postmark templates were updated to match, which also fixes the language segment: every language hardcoded `/en/` in the link

## 0.6.0

### Minor Changes

- 20f2ed5: Add the demand-transmitted email notification type for the marketplace source

  - Add `NotificationType::MarketplaceDemandTransmittedNotification` (`marketplace.demand-transmitted.notification`), bound to the marketplace source and email as its only supported channel
  - Add `MarketplaceDemandTransmittedNotificationData` (`first_name`, `pro_name`, `pro_slug`) as the payload for this notification
  - Drop `demand_professional_id` from `MarketplaceReviewRequestNotificationData`; the review-request payload is `first_name`, `pro_name`, `pro_slug` only

- 20f2ed5: Add the review-request email notification type for the marketplace source

  - Add `NotificationType::MarketplaceReviewRequestNotification` (`marketplace.review-request.notification`), bound to the marketplace source and email as its only supported channel
  - Add `MarketplaceReviewRequestNotificationData` (`first_name`, `pro_name`, `pro_slug`) as the payload for this notification
  - Wire the new type into the existing registry-driven send, preference grid, and validation paths with no additional code

- 20f2ed5: Refocus the notification type registry on real notifications

  - Add the `tools.new-demand.notification` type with `ToolsNewDemandNotificationData` (`first_name`, `workfield_name`, `demand_professional_id`), bound to the tools source, email only. `demand_professional_id` is carried through to the status and engagement feedback so a consuming app can correlate a delivered/opened/clicked event back to its own demand professional.
  - Remove the unused `tools.comment.notification` type and `ToolsCommentNotificationData`, which had no real notification behind it.

## 0.5.1

### Patch Changes

- 59709ec: Align internal dependency constraint with monorepo-builder in CI

## 0.5.0

### Minor Changes

- fd5ac83: Publish packages with the aligned dependency constraint

## 0.4.0

### Minor Changes

- eaedf36: Publish packages to their Packagist mirrors

## 0.3.0

### Minor Changes

- b9787a5: Publish the shared contracts package to Packagist

## 0.2.0

### Minor Changes

- f27d163: First public release of the shared contracts package

  - Wire types, notification data classes, capability interfaces, and enums exchanged between source applications and the notifications service.
  - Envelope serialization with versioned routing and strict validation.
  - Installable via `composer require deegitalbe/laravel-trustup-io-notifications-contracts`.
