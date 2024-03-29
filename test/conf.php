<?php

return [
    // redis 队列配置
    'worker_queue_conf'      => require __DIR__ . '/worker_queue_conf.php',

    // cron 定时任务
    'worker_local_cron_conf' => require __DIR__ . '/worker_local_cron_conf.php',

    // cron fork定时任务
    'worker_fork_cron_conf'  => require __DIR__ . '/worker_fork_cron_conf.php',

    // kafka 模式
    'worker_kafka_mq_conf'   => require __DIR__ . '/worker_kafka_mq_conf.php',

    // rabbitMq 模式
    'worker_rabbit_mq_conf'  => require __DIR__ . '/worker_rabbit_mq_conf.php',

    // 其他公共模式比如扫表
    'worker_common_conf'     => require __DIR__ . '/worker_common_conf.php',

];