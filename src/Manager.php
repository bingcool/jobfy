<?php

namespace Jobfy;

use Workerfy\ProcessManager;

class Manager extends ProcessManager
{

    /**
     * @param array $conf
     *
     */
    public function loadConf(array $conf)
    {
        foreach ($conf as $workerConfType => $workerConfItems)
        {
            foreach($workerConfItems ?? [] as $config)
            {
                $processName = $config['process_name'];
                $processClass = $config['handler'];
                $processWorkerNum = $config['worker_num'] ?? 1;
                $args = $config['args'] ?? [];
                $this->parseArgs($args, $config);
                $extendData = $config['extend_data'] ?? [];
                $enableCoroutine = true;
                $async = true;
                $this->addProcess($processName, $processClass, $processWorkerNum, $async, $args, $extendData, $enableCoroutine);
            }
        }

        return $this;
    }

    /**
     * @param array $args
     * @param array $config
     */
    protected function parseArgs(array &$args, array $config)
    {
        $args['max_handle'] = $config['max_handle'] ?? 10000;
        $args['life_time'] = $config['life_time'] ?? 3600;
        $args['limit_run_coroutine_num'] = $config['limit_run_coroutine_num'] ?? null;
    }
}