<?php
$conn = mysqli_connect("localhost", "root", "", "canteenapp");

// Cogemos la fecha de hoy en formato: 2026-04-21
$hoy = date('Y-m-d');

// Buscamos las fotos de la fecha de hoy
$query = "SELECT tipo, foto FROM menu_fotos WHERE fecha = '$hoy'";
$resultado = mysqli_query($conn, $query);

$fotos = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $fotos[$fila['tipo']] = $fila['foto'];
}
?>