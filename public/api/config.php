<?php
return [
    'db_host' => getenv('DB_HOST') ?: '127.0.0.1',
    'db_port' => getenv('DB_PORT') ?: '3306',
    'db_name' => getenv('DB_NAME') ?: 'sitebars',
    'db_user' => getenv('DB_USER') ?: 'root',
    'db_pass' => getenv('DB_PASS') ?: '',
    'vk_group_id' => getenv('VK_GROUP_ID') ?: '',
    'vk_token' => getenv('VK_TOKEN') ?: '',
    'yandex_org_id' => getenv('YANDEX_ORG_ID') ?: '',
    'vk_subscribe_url' => getenv('VK_SUBSCRIBE_URL') ?: 'https://vk.com',
    'address' => getenv('BARS_ADDRESS') ?: 'Москва, Красная площадь, 1',
    'phone' => getenv('BARS_PHONE') ?: '+7 (900) 000-00-00'
];
