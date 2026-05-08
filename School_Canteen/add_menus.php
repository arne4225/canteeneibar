<?php
include_once("conection_db.php");
$conn = KonektatuDatuBasera();
session_start();

// Cargar alergias ANTES de procesar el formulario
$alergias = mysqli_query($conn, "SELECT Allergy_Id, Name FROM allergies");
$lista_alergia = [];
while ($alle = mysqli_fetch_assoc($alergias)) {
    $lista_alergia[] = $alle;
}

// Procesar formulario de plato
if (isset($_POST['añadir_plato'])) {
    $name      = $_POST['name'];
    $allergies = $_POST['allergies'];

    $foto_nombre  = basename($_FILES['Argazk']['name']);
    $ruta_destino = "img/plates/" . $foto_nombre;
    move_uploaded_file($_FILES['Argazk']['tmp_name'], $ruta_destino);

    $sql = "INSERT INTO plate (Name, Photo, Allergie_Id) VALUES ('$name', '$foto_nombre', '$allergies')";
    if (mysqli_query($conn, $sql)) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        $msg_plato = "Error: " . mysqli_error($conn);
    }
}

// Procesar formulario de menú
if (isset($_POST['añadir_menu'])) {
    $plate1   = $_POST['plate1'];
    $plate2   = $_POST['plate2'];
    $plate3   = $_POST['plate3'];
    $type     = $_POST['type'];
    $price_oc = $_POST['price_ocasional'];
    $price_in = $_POST['price_intern'];
    $date     = $_POST['date'];

    $sql = "INSERT INTO menu (1_Plate, 2_Plate, 3_Plate, Type, Price_Ocasional, Price_Intern, Date) 
            VALUES ('$plate1', '$plate2', '$plate3', '$type', '$price_oc', '$price_in', '$date')";
    if (mysqli_query($conn, $sql)) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        $msg_menu = "Error: " . mysqli_error($conn);
    }
}
?>