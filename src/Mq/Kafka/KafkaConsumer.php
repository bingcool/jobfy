<?php
namespace Jobfy\Mq\Kafka;

use Jobfy\Mq\MqProcess;
use Common\Library\Kafka\Consumer;
use RdKafka\Message;

abstract class KafkaConsumer extends MqProcess
{
    /**
     * @var array|string
     */
    protected $brokerList;

    /**
     * @var string
     */
    protected $topicName;

    /**
     * @var Consumer
     */
    protected $consumer;

    /**
     * @var string
     */
    protected $groupId;

    /**
     * @var int
     */
    protected $timeoutMs;

    /**
     * @var int
     */
    protected $handleNum = 0;

    /**
     * onInit
     */
    public function onInit()
    {
        parent::onInit();
        $this->brokerList = $this->getArgs()['broker_list'] ?? '127.0.0.1:9092';
        $this->topicName  = $this->getArgs()['topic_name'];
        $this->groupId    = $this->getArgs()['group_id'];
        $this->timeoutMs  = $this->getArgs()['timeout_ms'] ?? 3000;
        $this->consumer   = new Consumer($this->brokerList, $this->topicName);
        $this->consumer->setGroupId($this->groupId);
        $this->consumer->setRebalanceCb(function (\RdKafka\KafkaConsumer $kafkaConsumer, $err, $partitions) {
            switch ($err) {
                case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
                    $kafkaConsumer->assign($partitions);
                    break;
                case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
                    $kafkaConsumer->assign(null);
                    break;
                default:
                    throw new \Exception($err);
            }
        });
    }

    /**
     * run
     */
    public function run()
    {
        parent::run();
        $rdKafkaConsumer = $this->consumer->subject();
        while (true)
        {
            try {

                if($this->isExiting() || $this->isRebooting()) {
                    sleep(1);
                    continue;
                }

                if($this->isStaticProcess() && $this->handleNum > $this->maxHandle) {
                    $this->reboot(3);
                    continue;
                }

                if(!empty($this->limitCurrentRunCoroutineNum)) {
                    if($this->getCurrentRunCoroutineNum() > $this->limitCurrentRunCoroutineNum) {
                        \Swoole\Coroutine\System::sleep(0.5);
                        continue;
                    }
                }

                $message = $rdKafkaConsumer->consume($this->timeoutMs);
                $this->handleNum++;

                if(!empty($message))
                {
                    switch ($message->err)
                    {
                        case RD_KAFKA_RESP_ERR_NO_ERROR:
                            $this->handle($message);
                            break;
                        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                            echo "No more messages; will wait for more";
                            break;
                        case RD_KAFKA_RESP_ERR__TIMED_OUT:
                            break;
                        default:
                            throw new \Exception($message->errstr(), $message->err);
                            break;
                    }
                }
            }catch (\Throwable $exception)
            {
                $this->onHandleException($exception);
            }
        }
    }

    /**
     *
     */
    abstract public function handle(Message $message);
}