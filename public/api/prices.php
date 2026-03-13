<?php
require __DIR__ . '/db.php';

$db = getDb();
$razdels = [];
$res = $db->query('SELECT id, title FROM razdels ORDER BY id ASC');
while ($row = $res->fetch_assoc()) {
    $razdels[(int)$row['id']] = ['id' => (int)$row['id'], 'title' => $row['title'], 'services' => []];
}

$svc = $db->query('SELECT id, title, is_from, cost, razdel_id FROM price ORDER BY id ASC');
while ($row = $svc->fetch_assoc()) {
    $rid = (int)$row['razdel_id'];
    if (!isset($razdels[$rid])) { continue; }
    $razdels[$rid]['services'][] = [
        'id' => (int)$row['id'],
        'title' => $row['title'],
        'isFrom' => (int)$row['is_from'] === 1,
        'cost' => $row['cost']
    ];
}

jsonResponse(['ok' => true, 'razdels' => array_values($razdels)]);
