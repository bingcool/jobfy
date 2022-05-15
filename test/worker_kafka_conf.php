<?php

// kafka 模式
return [
    // topic name
    [
        // 进程名
        'process_name' => 'kafka-order-consumer',
        'handler' => \Jobfy\Test\Worker\KafkaConsumer::class,
        'worker_num' => 1, // 默认动态进程数量
        'max_handle' => 10000, //消费达到10000后reboot进程
        'life_time'  => 3600, // 每隔3600s重启进程
        'limit_run_coroutine_num' => 10, // 当前进程的实时协程数量，如果协程数量超过此设置的数量，则禁止继续消费队列处理业务，而是在等待
        'extend_data' => [], // 额外数据

        'args' => [
            'topic_name' => 'mykafka',
            'broker_list' => ['127.0.0.1:9092'],
            'group_id' => 'group_order_1',
        ]
    ]
];