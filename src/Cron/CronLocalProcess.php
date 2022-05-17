<?php
namespace Jobfy\Cron;

use Workerfy\Crontab\CrontabManager;

abstract class CronLocalProcess extends CronProcess
{
    /**
     * @var string
     */
    protected $cronName;

    /**
     * @var string
     */
    protected $cronExpression;

    /**
     * onInit
     */
    public function onInit()
    {
        parent::onInit();
        $this->cronName       = $this->getArgs()['cron_name'];
        $this->cronExpression = $this->getArgs()['cron_expression'];
    }

    /**
     * run
     */
    public function run()
    {
        try {
            CrontabManager::getInstance()->addRule($this->cronName, $this->cronExpression, function ($cron_name, $expression)  {
                try {
                    $this->handle();
                }catch (\Throwable $exception)
                {
                    $this->onHandleException($exception, $this->getArgs());
                }
            }, 0);
        }catch (\Throwable $exception) {
            $this->onHandleException($exception, $this->getArgs());
        }
    }

    /**
     * @return mixed
     */
    abstract public function handle();
}