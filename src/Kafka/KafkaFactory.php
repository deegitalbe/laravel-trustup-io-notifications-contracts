<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Kafka;

use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\MissingKafkaCredentialsException;
use Junges\Kafka\Contracts\ConsumerBuilder;
use Junges\Kafka\Contracts\MessageProducer;
use Junges\Kafka\Facades\Kafka;

class KafkaFactory
{
    public function producer(): MessageProducer
    {
        return $this->applySasl(Kafka::publish($this->brokers()));
    }

    /** @param array<int, string> $topics */
    public function consumer(array $topics): ConsumerBuilder
    {
        return $this->applySasl(Kafka::consumer(
            topics: $topics,
            groupId: config('trustup-io-notifications-contracts.kafka.consumer_group_id'),
            brokers: $this->brokers(),
        ));
    }

    private function brokers(): ?string
    {
        return config('trustup-io-notifications-contracts.kafka.brokers');
    }

    /**
     * @template TBuilder of MessageProducer|ConsumerBuilder
     *
     * @param  TBuilder  $builder
     * @return TBuilder
     */
    private function applySasl(MessageProducer|ConsumerBuilder $builder): MessageProducer|ConsumerBuilder
    {
        $securityProtocol = config('trustup-io-notifications-contracts.kafka.security_protocol');

        if ($securityProtocol === 'PLAINTEXT') {
            return $builder;
        }

        $username = config('trustup-io-notifications-contracts.kafka.sasl.username');
        $password = config('trustup-io-notifications-contracts.kafka.sasl.password');
        $mechanisms = config('trustup-io-notifications-contracts.kafka.sasl.mechanisms');

        if ($username === null || $password === null || $mechanisms === null) {
            throw new MissingKafkaCredentialsException($securityProtocol);
        }

        return $builder->withSasl(
            username: $username,
            password: $password,
            mechanisms: $mechanisms,
            securityProtocol: $securityProtocol,
        );
    }
}
