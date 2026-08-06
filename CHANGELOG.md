# @deegitalbe/laravel-trustup-io-notifications-contracts

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
