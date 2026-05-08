
<?php

require_once 'DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    $occasional = isset($_POST['occasional_user']) ? 1 : 0;
    $intern = isset($_POST['is_intern']) ? 1 : 0;

    if ($password !== $password_confirm) {
        header("Location: register.php?error=Passwords do not match");
        exit;
    }


    $id = rand(10000000, 99999999) . chr(rand(65, 90));

    try {
        $stmt = $pdo->prepare("
            INSERT INTO user (Id, Name, Surname, Email, Password, Phone_num, Ocasional, Intern)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $id,
            $fname,
            $lname,
            $email,
            $password,
            $phone,
            $occasional,
            $intern
        ]);

        header("Location: register.php?success=1");
        exit;
    } catch (PDOException $e) {
        header("Location: register.php?error=Email already exists or DB error");
        exit;
    }
}
