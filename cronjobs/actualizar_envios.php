<?php
// cronjobs/actualizar_envios.php
// Este script simula la ejecución periódica del Worker.

// 1. Configuración y carga de clases (ajusta las rutas de requerimiento)
require_once __DIR__ . '/../app/services/ActualizaEnviosMeli.php';
// require_once __DIR__ . '/../app/config/database.php'; // Si necesitas inicializar la conexión global de la DB
// require_once __DIR__ . '/../app/models/ItemModel.php'; // etc.

// 2. Obtención del token (CRÍTICO: Debes obtener el token de Mercado Libre)
// Ejemplo de obtención segura (ajusta a tu método)
// $secrets = require __DIR__ . '/secrets.php';
// $token = $secrets['prod_mercado_libre']['prodtToken'];
$token = 'APP_USR-7626391564892909-111218-d15f7bcc271377b738f3f489b95ba501-2424408169'; // Reemplaza con tu token real

// if (empty($token) || $token === 'TU_ACCESS_TOKEN_REAL_AQUI') {
//     echo "ERROR: El token de acceso de Mercado Libre no está configurado.\n";
//     exit(1);
// }

// 3. Ítem de prueba (Reemplaza con un Item ID que ya esté en tu tabla item_meli)
$itemIdPrueba = 'MLM3754145804'; 

// 4. Inicialización y ejecución del servicio
echo "--- Iniciando proceso de actualización de envío ---\n";
echo "Procesando Item ID: " . $itemIdPrueba . "\n";

try {
    $updater = new ActualizaEnviosMeli($token);
    $resultado = $updater->updateShippingCost($itemIdPrueba);

    // 5. Mostrar el resultado de la ejecución
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