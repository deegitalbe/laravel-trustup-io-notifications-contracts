<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\MissingKafkaCredentialsException;
use Deegitalbe\TrustupIoNotificationsContracts\Kafka\KafkaFactory;
use Junges\Kafka\Contracts\ConsumerBuilder;
use Junges\Kafka\Contracts\MessageProducer;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

function saslOf(object $builder): ?object
{
    $property = (new ReflectionObject($builder))->getProperty('saslConfig');

    return $property->getValue($builder);
}

/** @return array<string, mixed> */
function optionsOf(object $builder): array
{
    $property = (new ReflectionObject($builder))->getProperty('options');

    return $property->getValue($builder);
}

it('sets the configured compression codec and message size cap on the producer', function (): void {
    config([
        'trustup-io-notifications-contracts.kafka.compression' => 'gzip',
        'trustup-io-notifications-contracts.kafka.message_max_bytes' => 1048576,
    ]);

    $options = optionsOf(app(KafkaFactory::class)->producer());

    expect($options['compression.codec'])->toBe('gzip')
        ->and($options['message.max.bytes'])->toBe('1048576');
});

it('sets the configured offset reset on the consumer', function (): void {
    config(['trustup-io-notifications-contracts.kafka.offset_reset' => 'earliest']);

    $options = optionsOf(app(KafkaFactory::class)->consumer(['some.topic']));

    expect($options['auto.offset.reset'])->toBe('earliest');
});

it('builds a producer carrying the configured sasl credentials', function (): void {
    config([
        'trustup-io-notifications-contracts.kafka.brokers' => 'broker:9093',
        'trustup-io-notifications-contracts.kafka.security_protocol' => 'SASL_SSL',
        'trustup-io-notifications-contracts.kafka.sasl.mechanisms' => 'PLAIN',
        'trustup-io-notifications-contracts.kafka.sasl.username' => 'user',
        'trustup-io-notifications-contracts.kafka.sasl.password' => 'secret',
    ]);

    $producer = app(KafkaFactory::class)->producer();

    expect($producer)->toBeInstanceOf(MessageProducer::class);

    $sasl = saslOf($producer);

    expect($sasl)->not->toBeNull()
        ->and($sasl->getUsername())->toBe('user')
        ->and($sasl->getPassword())->toBe('secret')
        ->and($sasl->getMechanisms())->toBe('PLAIN')
        ->and($sasl->getSecurityProtocol())->toBe('SASL_SSL');
});

it('builds a consumer carrying the configured sasl credentials and group id', function (): void {
    config([
        'trustup-io-notifications-contracts.kafka.brokers' => 'broker:9093',
        'trustup-io-notifications-contracts.kafka.security_protocol' => 'SASL_SSL',
        'trustup-io-notifications-contracts.kafka.sasl.mechanisms' => 'PLAIN',
        'trustup-io-notifications-contracts.kafka.sasl.username' => 'user',
        'trustup-io-notifications-contracts.kafka.sasl.password' => 'secret',
        'trustup-io-notifications-contracts.kafka.consumer_group_id' => 'group-a',
    ]);

    $consumer = app(KafkaFactory::class)->consumer(['some.topic']);

    expect($consumer)->toBeInstanceOf(ConsumerBuilder::class);

    $sasl = saslOf($consumer);

    expect($sasl)->not->toBeNull()
        ->and($sasl->getUsername())->toBe('user')
        ->and($sasl->getSecurityProtocol())->toBe('SASL_SSL');
});

it('applies sasl when the protocol is SASL_PLAINTEXT', function (): void {
    config([
        'trustup-io-notifications-contracts.kafka.security_protocol' => 'SASL_PLAINTEXT',
        'trustup-io-notifications-contracts.kafka.sasl.mechanisms' => 'PLAIN',
        'trustup-io-notifications-contracts.kafka.sasl.username' => 'app',
        'trustup-io-notifications-contracts.kafka.sasl.password' => 'app-secret',
    ]);

    $sasl = saslOf(app(KafkaFactory::class)->producer());

    expect($sasl)->not->toBeNull()
        ->and($sasl->getSecurityProtocol())->toBe('SASL_PLAINTEXT');
});

it('skips sasl entirely when the protocol is PLAINTEXT', function (): void {
    config([
        'trustup-io-notifications-contracts.kafka.security_protocol' => 'PLAINTEXT',
        'trustup-io-notifications-contracts.kafka.sasl.mechanisms' => null,
        'trustup-io-notifications-contracts.kafka.sasl.username' => null,
        'trustup-io-notifications-contracts.kafka.sasl.password' => null,
    ]);

    expect(saslOf(app(KafkaFactory::class)->producer()))->toBeNull();
});

it('throws when the protocol expects sasl but credentials are missing', function (): void {
    config([
        'trustup-io-notifications-contracts.kafka.security_protocol' => 'SASL_SSL',
        'trustup-io-notifications-contracts.kafka.sasl.mechanisms' => 'PLAIN',
        'trustup-io-notifications-contracts.kafka.sasl.username' => null,
        'trustup-io-notifications-contracts.kafka.sasl.password' => null,
    ]);

    expect(fn () => app(KafkaFactory::class)->producer())
        ->toThrow(MissingKafkaCredentialsException::class);
});

it('produces through the configured producer under a faked broker', function (): void {
    Kafka::fake();

    app(KafkaFactory::class)->producer()
        ->onTopic('notifications.request')
        ->withMessage(Message::create()->withBody(['ping' => true]))
        ->send();

    Kafka::assertPublishedOn('notifications.request');
});
