<?php
session_start();
$_SESSION = [];
session_destroy();
require __DIR__ . '/db.php';
jsonResponse(['ok' => true]);
