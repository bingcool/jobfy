<?php

// cron task
return [
    // 定时fork进程任务
    [
        'process_name' => 'worker-fork-task-cron',
        'handler' => \Jobfy\Cron\CronForkProcess::class,
        'worker_num' => 1, // 默认动态进程数量
        'max_handle' => 100, //消费达到10000后reboot进程
        'life_time'  => 3600, // 每隔3600s重启进程
        'limit_run_coroutine_num' => 10, // 当前进程的实时协程数量，如果协程数量超过此设置的数量，则禁止继续消费队列处理业务，而是在等待
        'extend_data' => [],
        'args' => [
            // 定时任务列表
            'task' =>[
                [
                    'cron_name' => 'send message', // 发送短信
                    'cron_expression' => '*/1 * * * *', // 分分钟执行一次
                    'run_cli' => "php ".APP_ROOT."/test/Test/TestCommand.php" // fork执行的bin命令行
                ]
            ]
        ],
    ]
];