<?php

return [
    // redis 队列配置
    'worker_queue_conf' => require __DIR__.'/worker_queue_conf.php',

    // cron 定时任务
    'worker_cron_conf' => require __DIR__.'/worker_cron_conf.php',

    // kafka 模式
    'worker_kafka_conf' => require __DIR__.'/worker_kafka_conf.php',

    // 其他公共模式比如扫表
    'worker_common_conf' => require __DIR__.'/worker_common_conf.php',

];