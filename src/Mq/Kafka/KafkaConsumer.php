<?php
namespace Jobfy\Mq\Kafka;

use RdKafka\Message;
use Jobfy\Mq\MqProcess;
use Common\Library\Kafka\Consumer;

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
        $this->consumer->setAssignPartitionsCallback(function (array $partitions) {
            $this->setAssignPartitionsCallback($partitions);
        });
        $this->consumer->setRevokePartitionsCallback(function (array $partitions) {
            $this->setRevokePartitionsCallback($partitions);
        });

    }

    /**
     * @param array $partitions
     */
    protected function setAssignPartitionsCallback(array $partitions)
    {

    }

    /**
     * @param array $partitions
     */
    protected function setRevokePartitionsCallback(array $partitions)
    {

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
                            $msg = "【Warning-Kafka】Kafka no more messages, will wait for more";
                            echo $msg;
                            write_info($msg);
                            break;
                        case RD_KAFKA_RESP_ERR__TIMED_OUT:
                            echo "Kafka Time Out\n";
                            break;
                        default:
                            throw new \RdKafka\Exception($message->errstr(), $message->err);
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