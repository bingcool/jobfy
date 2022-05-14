<?php

return [
    // redis 队列配置
    'worker_queue_conf' =>
        [
            // 队列名称
            'worker-queue1' => [
                // 进程名
                'process_name' => 'worker-queue1',
                'handler' => \Jobfy\Test\Worker\WorkerOrderQueue::class,
                'worker_num' => 1, // 默认动态进程数量
                'max_handle' => 10000, //消费达到10000后reboot进程
                'life_time'  => 3600, // 每隔3600s重启进程
                'limit_run_coroutine_num' => 10, // 当前进程的实时协程数量，如果协程数量超过此设置的数量，则禁止继续消费队列处理业务，而是在等待
                'extend_data' => [], // 额外数据

                // queue option
                'args' => [
                    'dynamic_queue_create_backlog' => 3000, //队列达到500积压，则动态创建进程
                    'dynamic_queue_destroy_backlog' => 20, //队列少于300积压，则销毁，这个值不设置，则表示是500
                    'dynamic_queue_worker_num' => 2, //动态创建的进程数,
                    'retry_num' => 2, // 重试次数
                    'retry_delay_time' => 5, // 延迟5s后放回主队列重试
                    'ttl' => 300, // 超过多少秒没有被消费，就抛弃，0代表永不抛弃
                    'driver' => 'redis', // 对应config的配置项
                ]
            ]
        ],

    // cron 定时任务
    'worker_cron_conf' => [
        // 定时fork进程任务
        'fork_task_cron' => [
            'process_name' => 'worker-task-cron',
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
        ],

        // 定时本地进程任务
        'local_cron_cancel_order' => [
            'process_name' => 'cron_cancel_order',
            'handler' => \Jobfy\Test\Worker\CronTask::class,
            'worker_num' => 1, // 默认动态进程数量
            'max_handle' => 100, //消费达到10000后reboot进程
            'life_time'  => 3600, // 每隔3600s重启进程
            'limit_run_coroutine_num' => 10, // 当前进程的实时协程数量，如果协程数量超过此设置的数量，则禁止继续消费队列处理业务，而是在等待
            'extend_data' => [],
            'args' => [
                'cron_name' => 'cancel order', // 取消订单
                'cron_expression' => '*/1 * * * *', // 分分钟执行一次
            ],
        ]
    ],

    // 其他公共模式比如扫表
    'worker_common_conf' => [
        'push_redis_queue' => [
            'process_name' => 'worker-push-queue-data',
            'handler' => \Jobfy\Test\Worker\PushQueue::class,
            'worker_num' => 1, // 默认动态进程数量
            'max_handle' => 100, //消费达到10000后reboot进程
            'life_time'  => 3600, // 每隔3600s重启进程
            'limit_run_coroutine_num' => 10, // 当前进程的实时协程数量，如果协程数量超过此设置的数量，则禁止继续消费队列处理业务，而是在等待
            'extend_data' => [],
            'args' => []
        ]
    ],

    // kafka 模式
    'worker_kafka_conf' => [

    ],

];