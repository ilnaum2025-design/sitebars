<?php
function getDb(): mysqli {
    static $db = null;
    if ($db instanceof mysqli) {
        return $db;
    }

    $config = require __DIR__ . '/config.php';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $db = new mysqli(
        $config['db_host'],
        $config['db_user'],
        $config['db_pass'],
        $config['db_name'],
        (int)$config['db_port']
    );
    $db->set_charset('utf8mb4');

    return $db;
}

function jsonResponse(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function requireAuth(): void {
    session_start();
    if (empty($_SESSION['admin_id'])) {
        jsonResponse(['ok' => false, 'error' => 'Не авторизован'], 401);
    }
}
