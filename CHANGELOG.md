# @deegitalbe/laravel-trustup-io-notifications-contracts

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
