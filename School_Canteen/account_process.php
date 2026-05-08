<?php
session_start();
require_once 'conection_db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $conn = KonektatuDatuBasera();

    $id       = $_SESSION['user_id'];
    $name     = $_POST['name'];
    $surname  = $_POST['surname'];
    $phone    = $_POST['phone'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "UPDATE user SET Name=?, Surname=?, Phone_num=?, Password=? WHERE Id=?");
    mysqli_stmt_bind_param($stmt, "sssss", $name, $surname, $phone, $password, $id);

    if (mysqli_stmt_execute($stmt)) {
        // ✅ Actualizar la sesión con el nombre correcto
        $_SESSION['user_name'] = $name;
        header("Location: acount.php?success=1");
        exit();
    } else {
        header("Location: acount.php?error=1");
        exit();
    }
}
?>