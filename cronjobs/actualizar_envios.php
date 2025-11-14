<?php
// cronjobs/actualizar_envios.php (Monolítico y Simplificado)

// **********************************************
// 1. CONFIGURACIÓN E INICIALIZACIÓN
// **********************************************

// -- Configuración de Errores (para debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// -- Inclusión de Clases Necesarias (Asegúrate de que la ruta sea correcta)
require_once __DIR__ . '/../app/services/MeliApiClient.php';
// NOTA: Si necesitas incluir tu archivo de configuración de DB (db_config.php)
// para definir las constantes, inclúyelo aquí.

// -- Credenciales y Conexión Directa
define('DB_HOST', 'localhost');
define('DB_USER', 'fragcom_develop'); // <-- REEMPLAZAR
define('DB_PASS', 'S15t3ma5@Fr4g0l4N'); // <-- REEMPLAZAR
define('DB_NAME', 'fragcom_linking_people'); // <-- REEMPLAZAR

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($db->connect_error) {
    die("Error de conexión a la BD: " . $db->connect_error);
}

// -- Credenciales de Mercado Libre
$token = 'APP_USR-7626391564892909-111315-ca14e8e5566badf720803da87cb38e73-2424408169'; 
$meliUserId = 2424408169;
$itemIdPrueba = 'MLM4156921140'; 


// **********************************************
// 2. FUNCIONES DE MODELO (Lógica SQL)
// **********************************************

/**
 * Intenta actualizar un registro existente.
 */
function updateEnvioSimple($db, $data)
{
    // Corregido: Solo 5 campos en SET (excluyendo item_id que es la clave)
    $sql = "UPDATE envios_meli SET 
                item_price = ?, 
                listing_type_id = ?, 
                mode = ?, 
                condicion = ?, 
                logistic_type = ?
            WHERE item_id = ?";
            
    $stmt = $db->prepare($sql);
    
    // Cadena de tipos: 
    // d (item_price)
    // ssss (listing_type_id, mode, condicion, logistic_type)
    // s (item_id en WHERE)
    
    // Total: 6 parámetros (d + 4s + s)
    $stmt->bind_param(
        "dsssss", // CORREGIDO: 6 tipos en total (d, s, s, s, s, s)
        $data['item_price'],    // d
        $data['listing_type_id'], // s
        $data['mode'],            // s
        $data['condicion'],       // s
        $data['logistic_type'],   // s
        $data['item_id']          // s (el del WHERE)
    );
    
    $result = $stmt->execute();
    
    if (!$result) {
        error_log("SQL Error (UPDATE SIMPLE): " . $stmt->error);
    }
    
    $affectedRows = $db->affected_rows;
    $stmt->close();
    
    return $affectedRows; // Retornamos el número de filas afectadas
}

/**
 * Inserta un nuevo registro.
 */
function addEnviosMeliSimple($db, $data)
{
    $sql = "INSERT INTO envios_meli (
                item_id, item_price, listing_type_id, mode, condicion, logistic_type
            ) VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    
    // s (item_id), d (item_price), ssss (varchars)
    $stmt->bind_param(
        "sdssss",
        $data['item_id'], 
        $data['item_price'], 
        $data['listing_type_id'], 
        $data['mode'], 
        $data['condicion'], 
        $data['logistic_type']
    );
    
    $result = $stmt->execute();
    
    if (!$result) {
        error_log("SQL Error (INSERT SIMPLE): " . $stmt->error);
    }
    
    $stmt->close();
    return $result; // Retornamos true/false
}


/**
 * Obtiene los datos del item desde la BD (Simulando ItemModel::datosConstoEnvio)
 */
// En actualizar_envios.php

// En actualizar_envios.php

function getItemData($db, $itemId)
{
    // ... (código SQL y ejecución) ...
    $data = $result->fetch_assoc();
    $stmt->close();
    
    // CAMBIO CLAVE: Inicializar $data si no se encuentra nada
    if (!$data) {
        return []; // Retornar un array vacío en lugar de null/false
    }
    
    // Renombrar 'price' a 'item_price' y asegurar el ID para la API
    // ... (código de mapeo) ...
    
    return $data; // Ahora $data siempre será un array o un array vacío.
}

// **********************************************
// 3. FUNCIÓN DE SERVICIO (Lógica de Ejecución)
// **********************************************

function updateShippingCostSimple($db, $meliClient, $meliUserId, $itemId)
{
    // 1. Obtener datos de la BD
    $paramsDb = getItemData($db, $itemId);

    // ************ CORRECCIÓN CLAVE ************
    if (empty($paramsDb)) {
        // Detiene la ejecución si el ítem no se encontró en la BD.
        return ['success' => false, 'message' => "Ítem **$itemId** no encontrado en la tabla item_meli."];
    }
    // ******************************************

    // 2. Preparar parámetros para la API
    $meliParams = $paramsDb;
    // ... el resto del código (pasos 2 al 6) sigue igual ...

    // ...
    // 5. Mapeo y Preparación de datos (SOLO USAMOS LOS DATOS DE LA CONSULTA para el guardado simple)
    $dataToSave = [
        'item_id' => $itemId, 
        'item_price' => (float)$meliParams['item_price'],
        'listing_type_id' => $meliParams['listing_type_id'],
        'mode' => $meliParams['mode'],
        'condicion' => $meliParams['condicion'], 
        'logistic_type' => $meliParams['logistic_type'],
        // Ignoramos costo_envio, moneda_envio, etc., para la tabla envios_meli simplificada
    ];


    // 6. Persistencia de datos (UPSERT)
    $affectedRows = updateEnvioSimple($db, $dataToSave);
    
    if ($affectedRows > 0) {
        return ['success' => true, 'message' => "Costo de envío actualizado con éxito."];
    } else {
        // Intentar INSERT si no se actualizó ninguna fila
        $insertResult = addEnviosMeliSimple($db, $dataToSave);

        if ($insertResult) {
            return ['success' => true, 'message' => "Costo de envío insertado con éxito."];
        } else {
            return ['success' => false, 'message' => "Error al guardar los datos de envío en la BD."];
        }
    }
}


// **********************************************
// 4. EJECUCIÓN DEL SCRIPT
// **********************************************

echo "--- Iniciando proceso de actualización de envío (Simple) ---\n";
echo "Procesando Item ID: " . $itemIdPrueba . "\n";

try {
    $meliClient = new MeliApiClient($token);
    
    $resultado = updateShippingCostSimple($db, $meliClient, $meliUserId, $itemIdPrueba);

    if ($resultado['success']) {
        echo "ÉXITO: " . $resultado['message'] . "\n";
    } else {
        echo "FALLO: " . $resultado['message'] . "\n";
    }

} catch (Exception $e) {
    echo "ERROR FATAL: " . $e->getMessage() . "\n";
    exit(1);
}

echo "--- Proceso finalizado ---\n";

// Cierre de conexión
$db->close();