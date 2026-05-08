<?php
    session_start();
    require_once 'conection_db.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
        
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_now'])) {        
        $conn =  konektatuDatuBasera();
        $user_id = $_SESSION['user_id'];
        $month = $_POST['month'];
        $total = $_POST['total'];
        $paid = 1; 

        $stmt = mysqli_prepare($conn, "UPDATE payment SET Paid=? WHERE User_id=? AND Month=?");
        mysqli_stmt_bind_param($stmt, "iss", $paid, $user_id, $month);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: acount.php?success=1");
            exit();
        } else {
            header("Location: acount.php?error=1");
        }
        exit;
    }
?>