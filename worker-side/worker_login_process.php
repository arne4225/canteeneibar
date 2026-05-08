<?php
session_start();
require_once 'DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basic check
    if (empty($email) || empty($password)) {
        header("Location: worker_login.php?error=Vul alle velden in");
        exit();
    }

    try {
        // Worker ophalen op basis van email
        $stmt = $pdo->prepare("SELECT * FROM worker WHERE Email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $worker = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($worker) {
            if ($password === $worker['Password']) {
                $_SESSION['worker_id'] = $worker['Id'];
                $_SESSION['worker_name'] = $worker['Name'];
                $_SESSION['worker_post'] = $worker['Post'];

                // Redirect naar worker dashboard
                header("Location: worker_dashboard.php");
                exit();

            } else {
                header("Location: worker_login.php?error=Onjuist wachtwoord");
                exit();
            }
        } else {
            header("Location: worker_login.php?error=Email niet gevonden");
            exit();
        }

    } catch (PDOException $e) {
        header("Location: worker_login.php?error=Database fout");
        exit();
    }
}