<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cargar la conexión a la base de datos. La ruta es relativa a la carpeta cronjobs.
require_once 'app/models/conexion.php'; 

// Carga las key de api syscom
$secrets = require 'config/secrets.php';

// Obtener el token de Syscom del array de secretos
$token = $secrets['prod_mercado_libre']['prodtToken'];


/**
 * Función para hacer una llamada GET a la API de Mercado Libre para un item_id dado
 * 
 * @param string $itemId ID del producto
 * @param string $token Token de autorización
 * @return array|null Respuesta decodificada de la API o null si falla
 */
$itemId = "MLM2345422965";
function consultarApiMeli($itemId, $token) {
    $url = "https://api.mercadolibre.com/items/$itemId";
    $headers = ["Authorization: Bearer $token"];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "Error al realizar la solicitud: " . curl_error($ch) . "\n";
        return null;
    }

    curl_close($ch);
    return json_decode($response, true);
}

$resultado = consultarApiMeli($itemId, $token);

// --- Imprimir resultado ---
echo json_encode($resultado, JSON_PRETTY_PRINT);