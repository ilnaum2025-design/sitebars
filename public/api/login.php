<?php
require __DIR__ . '/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['ok' => false, 'error' => 'Метод не поддерживается'], 405);
}

$payload = json_decode(file_get_contents('php://input'), true) ?: [];
$login = trim($payload['login'] ?? '');
$password = trim($payload['password'] ?? '');

if ($login === '' || $password === '') {
    jsonResponse(['ok' => false, 'error' => 'Логин и пароль обязательны'], 422);
}

$db = getDb();
$stmt = $db->prepare('SELECT id, login, password FROM admins WHERE login = ? LIMIT 1');
$stmt->bind_param('s', $login);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if (!$admin) {
    jsonResponse(['ok' => false, 'error' => 'Неверный логин или пароль'], 401);
}

$valid = password_verify($password, $admin['password']) || hash_equals($admin['password'], $password);
if (!$valid) {
    jsonResponse(['ok' => false, 'error' => 'Неверный логин или пароль'], 401);
}

$_SESSION['admin_id'] = (int)$admin['id'];
$_SESSION['admin_login'] = $admin['login'];

jsonResponse(['ok' => true, 'admin' => ['id' => (int)$admin['id'], 'login' => $admin['login']]]);
