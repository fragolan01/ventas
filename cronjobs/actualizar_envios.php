<?php

// Ajuste del límite de tiempo y memoria (para procesos largos)
set_time_limit(0); 
ini_set('memory_limit', '512M'); 

// --- 1. CONFIGURACIÓN E INCLUSIÓN DE ARCHIVOS ---

// Define el directorio base
$baseDir = __DIR__ . '/..'; 

// Cargar secretos (donde debe estar tu $token)
$secrets = require __DIR__ . '/secrets.php'; 

// Cargar Clases
require_once $baseDir . '/app/models/Model.php'; 
require_once $baseDir . '/app/models/EnviomeliModel.php'; 
require_once $baseDir . '/app/services/ActualizaEnviosMeli.php'; 

// --- 2. INICIALIZACIÓN DE DEPENDENCIAS ---

// Obtener Token 
$token = $secrets['prod_mercado_libre']['prodtToken'];

// Instancia del modelo para obtener la lista de ítems a procesar
$envioModel = new EnviomeliModel(); 

// Instancia del Servicio (el orquestador)
$updater = new ActualizaEnviosMeli($token); 

// --- 3. PRINCIPAL DEL PROCESO ---

echo "--- Iniciando proceso de actualización de costos de envío (ME2) ---\n";

//lista completa de ítems a procesar
$itemsToProcess = $envioModel->obtenerItemsParaCronjob(); 

if (empty($itemsToProcess)) {
    echo "No se encontraron ítems ME2 para procesar.\n";
    exit;
}

$count = 0;
$itemsChanged = 0;
$itemsFailed = 0;

foreach ($itemsToProcess as $itemData) {
    // item_id de cada elemento de la lista.
    $itemId = $itemData['item_id'];
    
    echo "Procesando item: $itemId...\n";
    
    // Llamar al método del servicio
    $resultado = $updater->updateShippingCost($itemId); 
    
    if ($resultado['success']) {
         // Interpretar el resultado devuelto por el servicio
         if ($resultado['changed']) {
             $status = 'CAMBIO DETECTADO. Costo registrado en historial.';
             $itemsChanged++;
         } else {
             $status = 'Sin cambios.';
         }
         echo " -> OK. $status\n";
    } else {
         // Manejo de fallas
         echo " -> FALLA. Mensaje: " . ($resultado['message'] ?? 'Error desconocido') . "\n";
         $itemsFailed++;
    }
    $count++;
}

echo "--- Proceso completado ---\n";
echo "Total ítems procesados: $count\n";
echo "Ítems con cambios registrados: $itemsChanged\n";
echo "Ítems que fallaron: $itemsFailed\n";

?>