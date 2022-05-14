<?php
namespace Jobfy\Cron;

use Jobfy\DaemonProcess;

class CronProcess extends DaemonProcess
{

    /**
     * @var mixed
     */
    protected $taskList;

    /**
     * onInit
     */
    public function onInit()
    {
        parent::onInit();
        $this->taskList = $this->getArgs()['task'] ?? [];
    }

    /**
     * @inheritDoc
     */
    public function run()
    {

    }
}