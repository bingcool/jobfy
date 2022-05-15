<?php
namespace Jobfy\Test\Worker;

use RdKafka\Message;

class KafkaConsumer extends \Jobfy\Mq\Kafka\KafkaConsumer
{

    /**
     * @inheritDoc
     */
    public function handle(Message $message)
    {
        var_dump($message->payload);
    }
}