<?php

// redis queue
return
[
     [
        // 进程名
        'process_name' => 'worker-queue1',
        'handler' => \Jobfy\Test\Worker\WorkerOrderQueue::class,
        'worker_num' => 100, // 默认动态进程数量
        'max_handle' => 10000, //消费达到10000后reboot进程
        'life_time'  => 3600, // 每隔3600s重启进程
        'limit_run_coroutine_num' => 10, // 当前进程的实时协程数量，如果协程数量超过此设置的数量，则禁止继续消费队列处理业务，而是在等待
        'extend_data' => [], // 额外数据

        // queue option
        'args' => [
            'driver' => 'redis', // 对应config的配置项
            'queue_name' => 'worker-queue1',
            'dynamic_queue_create_backlog' => 3000, //队列达到500积压，则动态创建进程
            'dynamic_queue_destroy_backlog' => 20, //队列少于300积压，则销毁，这个值不设置，则表示是500
            'dynamic_queue_worker_num' => 2, //动态创建的进程数,
            'retry_num' => 2, // 重试次数
            'retry_delay_time' => 5, // 延迟5s后放回主队列重试
            'ttl' => 300, // 超过多少秒没有被消费，就抛弃，0代表永不抛弃
        ]
    ]
];