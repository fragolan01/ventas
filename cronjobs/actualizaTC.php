<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cargar la conexión a la base de datos. La ruta es relativa a la carpeta cronjobs.
require_once '../app/models/conexion.php'; 


// Carga las key de api syscom
$secrets = require '../config/secrets.php';


// Obtener el token de Syscom del array de secretos
$token = $secrets['syscom']['api_token'];


/**
 * Función para hacer una llamada GET a la API de SYSCOM para obtener el tipo de cambio
 *
 * @param string $token Token de autorización
 * @return array|null Respuesta decodificada de la API o null si falla
 */
function consultarApiSyscom($token) {
    $url = "https://developers.syscom.mx/api/v1/tipocambio";
    $headers = ["Authorization: Bearer $token"];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        error_log("Error al realizar la solicitud del tipo de cambio: " . curl_error($ch) . "\n");
        return null;
    }

    curl_close($ch);
    return json_decode($response, true);
}


/**
 * Función para insertar datos del tipo de cambio en la tabla `plataforma_productos_tipo_cambio`
 *
 * @param mysqli $conn Conexión activa a la base de datos
 * @param array $datos Datos a insertar
 * @return bool Resultado de la operación
 */
function insertarTipoDeCambioSyscom($conn, $datos) {
    $sql = "INSERT INTO `tipo_de_cambio`
                 (`normal`, `preferencial`, `un_dia`, `una_semana`, `dos_semanas`, `tres_semanas`, `un_mes`)
                 VALUES (?, ?, ?, ?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE
                 `normal` = VALUES(`normal`),
                 `preferencial` = VALUES(`preferencial`),
                 `un_dia` = VALUES(`un_dia`),
                 `una_semana` = VALUES(`una_semana`),
                 `dos_semanas` = VALUES(`dos_semanas`),
                 `tres_semanas` = VALUES(`tres_semanas`),
                 `un_mes` = VALUES(`un_mes`)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Error al preparar la consulta de inserción del tipo de cambio: " . $conn->error . "\n");
        return false;
    }

    $stmt->bind_param(
        "ddddddd",
        $datos['normal'],
        $datos['preferencial'],
        $datos['un_dia'],
        $datos['una_semana'],
        $datos['dos_semanas'],
        $datos['tres_semanas'],
        $datos['un_mes']
    );

    $result = $stmt->execute();

    if (!$result) {
        error_log("Error al ejecutar la consulta de inserción del tipo de cambio: " . $stmt->error . "\n");
    }

    $stmt->close();

    return $result;
}

// Obtener el tipo de cambio desde la API
$tipoCambioApi = consultarApiSyscom($token);

// Inspeccionar la respuesta de la API decodificada
echo "<pre>Respuesta de API:\n";
print_r($tipoCambioApi);
echo "</pre>";


if ($tipoCambioApi) {
    $resultado = insertarTipoDeCambioSyscom($conn, $tipoCambioApi);

    if ($resultado) {
        echo "Inserción correcta ";
    } else {
        echo "Error al insertar en la tabla ";
    }
} else {
    echo "Sin respuesta válida de la API";
}

$result = $conn->query("SELECT * FROM tipo_de_cambio LIMIT 1");

if ($result && $row = $result->fetch_assoc()) {
    echo "<pre>Contenido actual en la tabla tipo_de_cambio:\n";
    print_r($row);
    echo "</pre>";
} else {
    echo "No se encontraron registros en la tabla.";
}
