<?php
session_start();
require __DIR__ . '/db.php';
if (!empty($_SESSION['admin_id'])) {
    jsonResponse(['ok' => true, 'authorized' => true, 'admin' => ['id' => $_SESSION['admin_id'], 'login' => $_SESSION['admin_login'] ?? '']]);
}
jsonResponse(['ok' => true, 'authorized' => false]);
