<?php
session_start();
require_once 'DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE Email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if ($user) {

            $_SESSION['user_id'] = $user['Id'];
            $_SESSION['user_name'] = $user['Name'];

            header("Location: index.php");
            exit;

        } else {
            header("Location: login.php?error=Invalid email or password");
            exit;
        }

    } catch (PDOException $e) {
        header("Location: login.php?error=Database error");
        exit;
    }
}
