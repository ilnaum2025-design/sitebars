<?php
require __DIR__ . '/db.php';
$config = require __DIR__ . '/config.php';

function fetchJson(string $url): ?array {
    $ctx = stream_context_create(['http' => ['timeout' => 6]]);
    $raw = @file_get_contents($url, false, $ctx);
    if ($raw === false) return null;
    $data = json_decode($raw, true);
    return is_array($data) ? $data : null;
}

$vkPosts = [];
if ($config['vk_group_id'] !== '' && $config['vk_token'] !== '') {
    $url = sprintf(
        'https://api.vk.com/method/wall.get?owner_id=-%s&count=6&access_token=%s&v=5.199',
        urlencode($config['vk_group_id']),
        urlencode($config['vk_token'])
    );
    $resp = fetchJson($url);
    if (!empty($resp['response']['items'])) {
        foreach ($resp['response']['items'] as $item) {
            $photos = $item['attachments'] ?? [];
            $photoUrl = 'https://placehold.co/500x320?text=News';
            foreach ($photos as $att) {
                if (($att['type'] ?? '') === 'photo' && !empty($att['photo']['sizes'])) {
                    $sizes = $att['photo']['sizes'];
                    $best = end($sizes);
                    $photoUrl = $best['url'] ?? $photoUrl;
                    break;
                }
            }
            $vkPosts[] = [
                'text' => $item['text'] ?? '',
                'date' => $item['date'] ?? time(),
                'likes' => $item['likes']['count'] ?? 0,
                'comments' => $item['comments']['count'] ?? 0,
                'url' => 'https://vk.com/wall-' . $config['vk_group_id'] . '_' . ($item['id'] ?? 0),
                'image' => $photoUrl,
            ];
        }
    }
}

if (empty($vkPosts)) {
    $vkPosts = [
        ['text' => 'News Наш барбер открыл новый зал для VIP клиентов. Добро пожаловать!', 'date' => time(), 'likes' => 41, 'comments' => 9, 'url' => 'https://vk.com', 'image' => 'https://placehold.co/500x320?text=News+1'],
        ['text' => 'News Обновили прайс и добавили новые уходовые процедуры.', 'date' => time() - 86400, 'likes' => 28, 'comments' => 6, 'url' => 'https://vk.com', 'image' => 'https://placehold.co/500x320?text=News+2']
    ];
}

$reviews = [
    ['name' => 'Артем', 'avatar' => 'https://placehold.co/48x48', 'date' => date('Y-m-d'), 'text' => 'Отличный сервис и очень аккуратная стрижка.', 'rating' => 5],
    ['name' => 'Николай', 'avatar' => 'https://placehold.co/48x48', 'date' => date('Y-m-d', time()-86400*4), 'text' => 'Записался онлайн, приняли вовремя. Рекомендую.', 'rating' => 4],
    ['name' => 'Алина', 'avatar' => 'https://placehold.co/48x48', 'date' => date('Y-m-d', time()-86400*10), 'text' => 'Уютная атмосфера и профессиональные мастера.', 'rating' => 5],
];

$reviewCount = 232;

jsonResponse([
    'ok' => true,
    'address' => $config['address'],
    'phone' => $config['phone'],
    'vkSubscribeUrl' => $config['vk_subscribe_url'],
    'vkPosts' => $vkPosts,
    'reviews' => array_values(array_filter($reviews, fn($r) => $r['rating'] >= 4)),
    'reviewCount' => $reviewCount,
]);
