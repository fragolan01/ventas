<?php
// cronjobs/actualizar_envios.php
// Script de ejecución por línea de comandos (CLI)

// Ajuste del límite de tiempo y memoria (para procesos largos)
set_time_limit(0); 
ini_set('memory_limit', '512M'); 

// --- 1. CONFIGURACIÓN E INCLUSIÓN DE ARCHIVOS ---

// Define el directorio base (asumiendo que este script está dentro de 'cronjobs')
$baseDir = __DIR__ . '/..'; 

// Cargar secretos (donde debe estar tu $token)
// NOTA: Asegúrate de que 'secrets.php' exista y devuelva un array con el token.
$secrets = require __DIR__ . '/secrets.php'; 

// Cargar Clases
require_once $baseDir . '/app/models/Model.php'; 
require_once $baseDir . '/app/models/EnviomeliModel.php'; 
require_once $baseDir . '/app/services/ActualizaEnviosMeli.php'; 

// --- 2. INICIALIZACIÓN DE DEPENDENCIAS ---

// Obtener Token 
$token = $secrets['prod_mercado_libre']['prodtToken'];

// El ID de usuario se usa internamente en el servicio, pero lo definimos aquí si es necesario
// $meliUserId = 2424408169; 

// Instancia del modelo para obtener la lista de ítems a procesar
// NOTA: Asume que EnviomeliModel::obtenerItemsParaCronjob() existe y devuelve un array de arrays.
$envioModel = new EnviomeliModel(); 

// Instancia del Servicio (el orquestador)
$updater = new ActualizaEnviosMeli($token); 

// --- 3. LÓGICA PRINCIPAL DEL PROCESO ---

echo "--- Iniciando proceso de actualización de costos de envío (ME2) ---\n";

// Obtener la lista completa de ítems a procesar (incluye item_id, price, etc.)
$itemsToProcess = $envioModel->obtenerItemsParaCronjob(); 

if (empty($itemsToProcess)) {
    echo "No se encontraron ítems ME2 para procesar.\n";
    exit;
}

$count = 0;
$itemsChanged = 0;
$itemsFailed = 0;

foreach ($itemsToProcess as $itemData) {
    // Extraemos el item_id de cada elemento de la lista.
    $itemId = $itemData['item_id'];
    
    echo "Procesando item: $itemId...\n";
    
    // Llamar al método del servicio (SOLO se pasa el itemId)
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