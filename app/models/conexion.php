<?php

$servername = "localhost"; // Servidor de base de datos
$username = "fragcom_develop"; // Usuario de MySQL
$password = "S15t3ma5@Fr4g0l4N"; // Contraseña de MySQL
$database = "fragcom_ventas_test"; // base de datos

// $servername = "localhost"; // Servidor de base de datos
// $username = "root"; // Usuario de MySQL
// $password = ""; // Contraseña de MySQL
// $database = "fragcom_ventas_test"; // base de datos


// $database = "fragcom_develop"; // base de datos

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $database);

// Verifica la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}else{
    // echo "La conexion es correcta";
}

?>