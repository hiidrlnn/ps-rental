<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$log_data = [
    'timestamp' => date('Y-m-d H:i:s'),
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN',
    'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
    'message' => 'LOCKDOWN_ACCESS_ATTEMPT'
];

file_put_contents(__DIR__ . '/security_breach.log', json_encode($log_data) . PHP_EOL, FILE_APPEND);

header("Location: hacked.php");
exit();
?>