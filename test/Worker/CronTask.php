<?php
namespace Jobfy\Test\Worker;

use Jobfy\Cron\CronLocalProcess;

class CronTask extends CronLocalProcess
{
    /**
     * @return mixed|void
     */
    public function handle()
    {
        var_dump($this->cronName.':'.$this->cronExpression);
    }
}