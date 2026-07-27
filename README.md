# Trustup IO Notifications Contracts

Shared contracts for the Trustup IO Notifications system: the data classes, capability interfaces, enums, and wire types exchanged between a source application (via the client package) and the notifications service.

This package holds no transport logic. It defines **what** a notification is and **how** it serializes. The client package (`deegitalbe/laravel-trustup-io-notifications`) depends on it to send notifications and to receive delivery feedback.

- Package name: `deegitalbe/laravel-trustup-io-notifications-contracts`
- Namespace: `Deegitalbe\TrustupIoNotificationsContracts\`
- Requires: PHP 8.2+, `ext-intl`

## What lives here

| Concern | Type |
| --- | --- |
| A notification's payload | `Data\*NotificationData` classes implementing `Contracts\NotificationData` |
| Which channels a notification supports | Capability interfaces: `EmailCapable`, `SmsCapable`, `PushCapable` |
| Default channel rendering | Traits: `RendersEmail`, `RendersSms`, `RendersPush` |
| Automatic array serialization | `SerializesFromConstructor` |
| The registry of notification types | `Enums\NotificationType` |
| The recipient of a notification | `Request\Recipient` |
| The wire envelope | `Envelope`, `Request\RequestPayload`, `Status\StatusPayload`, `Engagement\EngagementPayload` |

Most consumers only ever touch **data classes** (to send) and **payloads** (to read feedback). The rest is machinery.

## Notification data classes

A data class is the typed payload of one notification type. It declares which channels it supports by implementing capability interfaces, and gets array serialization for free from `SerializesFromConstructor`.

### Minimal, email-only

```php
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersEmail;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

final readonly class ToolsCommentNotificationData implements EmailCapable, NotificationData
{
    use RendersEmail;
    use SerializesFromConstructor;

    /** @param list<array<string, string>> $attachment_details */
    public function __construct(
        public string $product_url,
        public string $product_name,
        public string $body,
        public array $attachment_details,
        public string $commenter_name,
        public string $timestamp,
        public string $action_url,
        public string $notifications_url,
        public string $company_name,
        public string $company_address,
    ) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::ToolsCommentNotification;
    }
}
```

Two rules:

1. Implement `NotificationData` (which requires `notificationType()`), plus one capability interface per channel the notification supports.
2. Use `SerializesFromConstructor` so the payload can cross the wire.

The constructor parameter names are the serialized keys. For email, they are also the Postmark template model variables (see below), so name them to match your template placeholders (`product_url`, `body`, ...).

### Multi-channel

Implement several capability interfaces and pull in the matching traits:

```php
final readonly class ToolsTestNotificationData implements EmailCapable, NotificationData, PushCapable, SmsCapable
{
    use RendersEmail;
    use RendersPush;
    use RendersSms;
    use SerializesFromConstructor;

    public function __construct(
        public string $title,
        public string $body,
    ) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::ToolsTestNotification;
    }
}
```

`NotificationType::supportedChannels()` derives the channel list automatically from the interfaces the data class implements. You never declare channels twice.

## Capability interfaces and their default rendering

Each capability interface has a companion `Renders*` trait that provides a sensible default implementation. You only override a method when the default does not fit.

### `EmailCapable` + `RendersEmail`

Email is rendered **provider-side** (Postmark owns the layout and per-language wording). The data class only supplies the template model (variables) and picks the template.

| Method | Default | Override to |
| --- | --- | --- |
| `toEmail(): EmailContent` | `new EmailContent($this->emailVariables())` | rarely |
| `emailTemplate(): string` | `notificationType()->slug()` (e.g. `tools-comment-notification`) | use a different Postmark template alias |
| `emailVariables(): array` | `$this->toArray()` (all constructor data) | send a subset / reshape the template model |
| `emailTemplateLocaleGranularity(): EmailTemplateLocaleGranularity` | `Language` | opt into per-locale templates (see enum below) |

`EmailContent` is `readonly` and wraps `public array $variables` (the Postmark template model).

### `SmsCapable` + `RendersSms`

SMS has no provider template, so the body is translated **service-side** with `__()`.

| Method | Default | Override to |
| --- | --- | --- |
| `toSms(): SmsContent` | `new SmsContent($this->smsBody())` | rarely |
| `smsBody(): string` | `__($this->smsTranslationKey(), $this->smsBodyTranslationParams())` | build the body differently |
| `smsTranslationKey(): string` | `notifications.{slug}.sms` | point at another translation key |
| `smsBodyTranslationParams(): array` | `$this->toArray()` | change the placeholders |

`SmsContent` wraps `public string $body` and rejects an empty body.

### `PushCapable` + `RendersPush`

Same model as SMS: title and body translated service-side.

| Method | Default | Override to |
| --- | --- | --- |
| `toPush(): PushContent` | `new PushContent($this->pushTitle(), $this->pushBody(), $this->pushData())` | rarely |
| `pushTitle(): string` | `__("{pushTranslationKey}.title", pushTitleTranslationParams())` | custom title |
| `pushBody(): string` | `__("{pushTranslationKey}.body", pushBodyTranslationParams())` | custom body |
| `pushData(): array` | `$this->toArray()` | change the data payload sent to the device |
| `pushTranslationKey(): string` | `notifications.{slug}.push` | another key |
| `pushTitleTranslationParams()` / `pushBodyTranslationParams()` | `$this->toArray()` | change placeholders |

`PushContent` wraps `public string $title, public string $body, public array $data` and rejects an empty title or body.

## Translations (SMS and Push only)

Email is not translated here (Postmark owns the per-language wording). SMS and Push **are** translated, service-side, via `__()`.

### Who sends what

A source application never sends translated text. It sends the **data** (raw values). The notifications service renders the final SMS/Push text in the recipient's locale, using those values as translation parameters.

```
source app            wire            notifications service              provider
data (values)  ────────────────►  __(key, params) in recipient  ──────►  final text
                                    locale                                (FCM / Vonage)
```

### Parameters are the data class properties

`RendersSms` / `RendersPush` pass `$this->toArray()` (every constructor property) as the translation parameters. The Laravel placeholders (`:name`) in the translation string are therefore the **property names**.

Data class:

```php
final readonly class OrderShippedData implements PushCapable, NotificationData
{
    use RendersPush;
    use SerializesFromConstructor;

    public function __construct(
        public string $order_number,
        public string $customer_name,
    ) {}

    public function notificationType(): NotificationType { return NotificationType::OrderShipped; }
}
```

Translation string (placeholders match the property names):

```php
'order-shipped' => [
    'push' => [
        'title' => 'Commande expédiée',
        'body'  => 'Bonjour :customer_name, votre commande :order_number est en route',
    ],
],
```

The source app sends `OrderShippedData(order_number: 'A-123', customer_name: 'Marie')`; the service renders `Bonjour Marie, votre commande A-123 est en route` in the recipient's language.

### Where the translations live

The text is rendered inside the **notifications service**, so the translations belong to it, not to each source application. They are registered once in the central translation system (`trustup-io-translations`) under the app name **`trustup-io-notifications`**, keyed by `notifications.{slug}.sms` / `notifications.{slug}.push.{title,body}`. Source apps (tools, marketplace, ...) define **no** notification translations.

### Decoupling parameters from the payload

By default the parameters are all properties. To send different placeholders than the raw payload, override the params methods:

```php
protected function pushTitleTranslationParams(): array { return ['name' => $this->customer_name]; }
protected function pushBodyTranslationParams(): array  { return ['order' => $this->order_number]; }
```

Now the placeholders are `:name` / `:order`, independent of the property names. Same pattern for SMS via `smsBodyTranslationParams()`, and you can retarget the key with `smsTranslationKey()` / `pushTranslationKey()`.

## Serialization

`SerializesFromConstructor` derives `toArray()` / `fromArray()` from the constructor by reflection:

- `toArray()`: one key per promoted constructor parameter, value read from the matching property. Nested `Serializable` values are serialized recursively.
- `fromArray($data)`: rebuilds the object; a missing key falls back to the constructor default, then to `null` if the parameter is nullable, otherwise throws `InvalidNotificationDataException` (the wire boundary fails loud, not with a raw `TypeError`).

Override either method for non-trivial mappings (enums, value objects).

## The `NotificationType` registry

`Enums\NotificationType` is the single source of truth for the notification catalogue.

```php
NotificationType::ToolsCommentNotification->value;              // 'tools.comment.notification'
NotificationType::ToolsCommentNotification->slug();             // 'tools-comment-notification'
NotificationType::ToolsCommentNotification->dataClass();        // ToolsCommentNotificationData::class
NotificationType::ToolsCommentNotification->source();           // Source::Tools
NotificationType::ToolsCommentNotification->supportedChannels(); // [NotificationChannel::Email]
NotificationType::forSource(Source::Tools);                     // list of types for a source
```

### Adding a new notification type

1. Add a case with its dotted value: `case FooBar = 'tools.foo.bar';`
2. Add a branch in `dataClass()` and in `source()` (both `match` throw on unmapped cases).
3. Create the `NotificationData` class implementing the capability interfaces for the channels it supports.

`slug()`, `supportedChannels()`, and `forSource()` need no changes; channels are derived from the interfaces.

## `EmailTemplateLocaleGranularity`

Controls how the notifications service suffixes the Postmark template alias with the recipient locale:

- `Language` (default): per-language template (e.g. `tools-comment-notification-en`).
- `Locale`: per-locale template (e.g. `tools-comment-notification-en-BE`).

Override `emailTemplateLocaleGranularity()` on a data class to opt into `Locale`. The suffix itself is applied service-side from the recipient's resolved locale.

## `Recipient`

The addressee of a notification. Private constructor; use the factories.

```php
use Deegitalbe\TrustupIoNotificationsContracts\Request\Recipient;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\Source;

// Identified, source deferred to config: the client fills it from
// config('trustup-io-notifications.source') before publishing
Recipient::identified((string) $user->id);

// Identified with an explicit source
Recipient::identified((string) $user->id, Source::Tools);

// Anonymous: you supply the coordinates directly (at least one required)
Recipient::anonymous(email: 'a@b.com', phone: null, deviceTokens: [], locale: 'fr-BE');
```

- `identified(string $externalUserId, ?Source $source = null)`: the service looks up the recipient's coordinates and locale from the source system. The source is optional; when omitted, the client resolves it from `config('trustup-io-notifications.source')` at publish time (a single-source app therefore only needs the external id). If neither is set, publishing throws `MissingSourceException`.
- `anonymous(?string $email, ?string $phone, array $deviceTokens, ?string $locale = null)`: requires at least one coordinate, and a locale that normalizes via `LocaleNormalizer` (else `InvalidRecipientException`).
- `isIdentified(): bool`, `withSource(Source $source): self` (returns a copy; used to inject a default source).

An identified recipient serialized without a source (the deferred case) must have its source resolved before it crosses the wire. `fromArray()` throws `InvalidRecipientException` if it decodes an identified recipient with no source.

## Wire types

You rarely build these directly (the client package does), but you read `StatusPayload` / `EngagementPayload` when handling feedback.

- `Envelope` — wire wrapper. `CURRENT_VERSION = 1`, a `direction` (`request` / `status` / `engagement`) and the matching payload.
- `RequestPayload` — `type`, `recipient`, `data`, `channels` (`null` = all supported, or a non-empty list; `[]` is rejected).
- `StatusPayload` — delivery status feedback:
  ```php
  public string $sendId;
  public NotificationChannel $channel;   // email|sms|push
  public NotificationStatus $status;     // pending|sent|delivered|error
  public NotificationType $type;
  public NotificationData $data;
  ```
- `EngagementPayload` — engagement feedback:
  ```php
  public string $sendId;
  public NotificationChannel $channel;
  public ChannelEventKind $kind;         // delivered|bounced|spam_complaint|opened|clicked|...
  public NotificationType $type;
  public NotificationData $data;
  public ?string $clickedUrl;
  ```

## Locale helpers

- `Support\LocaleNormalizer::normalize(?string $raw): ?string` — canonicalizes to `lang-REGION` (default region `BE`). Known languages: `fr, nl, en, de`; anything else returns `null`. Maps proprietary tags (`be-fr` → `fr-BE`).
- `Support\LocaleLanguageExtractor::language(string $locale): string` — extracts the language subtag (`fr-BE` → `fr`), used for `Language`-granularity email templates.
