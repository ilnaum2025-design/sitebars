<?php
require __DIR__ . '/db.php';
requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['ok' => false, 'error' => 'Метод не поддерживается'], 405);
}

$payload = json_decode(file_get_contents('php://input'), true);
if (!is_array($payload) || !isset($payload['razdels']) || !is_array($payload['razdels'])) {
    jsonResponse(['ok' => false, 'error' => 'Некорректные данные'], 422);
}

$db = getDb();
$db->begin_transaction();
try {
    $db->query('DELETE FROM price');
    $db->query('DELETE FROM razdels');

    $insertRazdel = $db->prepare('INSERT INTO razdels (title) VALUES (?)');
    $insertPrice = $db->prepare('INSERT INTO price (title, is_from, cost, razdel_id) VALUES (?, ?, ?, ?)');

    foreach ($payload['razdels'] as $razdel) {
        $title = trim($razdel['title'] ?? '');
        if ($title === '') {
            continue;
        }
        $insertRazdel->bind_param('s', $title);
        $insertRazdel->execute();
        $rid = $db->insert_id;

        $services = is_array($razdel['services'] ?? null) ? $razdel['services'] : [];
        foreach ($services as $service) {
            $stitle = trim($service['title'] ?? '');
            $cost = trim((string)($service['cost'] ?? ''));
            if ($stitle === '' || $cost === '') { continue; }
            $isFrom = !empty($service['isFrom']) ? 1 : 0;
            $insertPrice->bind_param('sisi', $stitle, $isFrom, $cost, $rid);
            $insertPrice->execute();
        }
    }

    $db->commit();
    jsonResponse(['ok' => true]);
} catch (Throwable $e) {
    $db->rollback();
    jsonResponse(['ok' => false, 'error' => 'Ошибка сохранения: ' . $e->getMessage()], 500);
}
