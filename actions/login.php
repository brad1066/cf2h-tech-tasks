<?php

session_start();

include_once '../inc/definitions.php';

$servername = DB_HOST;
$dbname = DB_NAME;
$username = DB_USER;
$password = DB_PASSWORD;
$csrfToken = $_SESSION['csrfToken'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_POST['csrfToken']) || $_POST['csrfToken'] !== $csrfToken) {
            var_dump($_POST['csrfToken']);
            var_dump($csrfToken);
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $username = filter_input(INPUT_POST, 'username');
        $password = md5(filter_input(INPUT_POST, 'password'));

        $checkPasswordQuery = $conn->prepare("SELECT username, first_name, last_name, role FROM users WHERE username = :username && password = :password");
        // var_dump($checkPasswordQuery);
        $checkPasswordQuery->execute(['username' => $username, 'password' => $password]);
        $response = $checkPasswordQuery->fetchAll();

        if ($checkPasswordQuery->rowCount() === 1) {
            // var_dump($response);
            echo json_encode(['success' => true]);
            $_SESSION['authenticated'] = true;
            $_SESSION['user'] = $response[0];
        } else {
            unset($_SESSION['authenticated']);
            unset($_SESSION['user']);
            echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'There was an internal error processing your request. Please try again']);
        return;
    }
    finally {
        $conn = null;
    }
}