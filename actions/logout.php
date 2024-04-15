<?php
session_start();

$session_cleared = session_destroy();
$response = ['success' => $session_cleared, 'message' => ''];

if ($session_cleared) {
    $response['message'] = 'Logged out successfully';
} else {
    $response['message'] = 'Failed to log out';
}

echo json_encode($response);