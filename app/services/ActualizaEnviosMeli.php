<?php
require_once '../app/models/ItemModel.php'; 
require_once '../app/models/EnviomeliModel.php';
require_once '../app/services/MeliApiClient.php'; 

// Para Cron job
require_once '../app/models/EnviomeliHistorialModel.php';

class ActualizaEnviosMeli
{
    private $itemModel;
    private $envioModel;
    private $meliClient;
    private $meliUserId = 2424408169; 
    
    public function __construct($token)
    {
        $this->meliClient = new MeliApiClient($token);
        $this->itemModel = new ItemModel();
        $this->envioModel = new EnviomeliModel(); 
        // CronJob
        $this->historialModel = new EnviomeliHistorialModel();
    }

    public function updateShippingCost($itemId)
    {
        // 1. Datos BD
        $paramsDb = $this->itemModel->datosCostoEnvio($itemId);

        if (!$paramsDb) {
            error_log("Updater: Ítem $itemId no encontrado en la BD.");
            return ['success' => false, 'message' => "Ítem $itemId no encontrado en la BD."];
        }

        // CronJob
        $oldEnvioData = $this->envioModel->obtenerCostosEnvioPorItemId($itemId);      
        $oldListCost = $oldEnvioData['list_cost'] ?? 0.00;
        $oldBillableWeight = $oldEnvioData['billable_weight'] ?? 0.00;



        // 2. y 3. Preparar y Consultar la API de ML
        $meliParams = $paramsDb;
        $meliParams['verbose'] = 'true';
        $apiResponse = $this->meliClient->getFreeShippingOptions($this->meliUserId, $meliParams);

        // 4. Manejo de errores de la API
        if (isset($apiResponse['error']) && $apiResponse['error'] === true) {
            $apiMessage = $apiResponse['message'] ?? 'Error desconocido';
            error_log("Updater: Error al consultar ML para envío de $itemId. Mensaje: " . $apiMessage);
            return ['success' => false, 'message' => "Error de API: $apiMessage"];
        }

        // =================================================================
        // 5. PARSEO Y EXTRACCIÓN DE LOS 3 CAMPOS NUEVOS (list_cost, currency_id, billable_weight)
        // =================================================================
        $costoLista = 0.00;
        $moneda = 'MXN';
        $pesoFacturable = 0.00;

        
        // Navegación específica a 'coverage' -> 'all_country'
        if (!empty($apiResponse['coverage']['all_country'])) {
            $cobertura = $apiResponse['coverage']['all_country'];

            $costoLista = (float)($cobertura['list_cost'] ?? 0.00);
            $moneda = $cobertura['currency_id'] ?? $moneda;
            $pesoFacturable = (float)($cobertura['billable_weight'] ?? 0.00);
        } 
        
        
        // CronJob
        $haCambiado = false;

        $costoHaCambiado = abs($costoLista - $oldListCost) > 0.01;
        $pesoHaCambiado = abs($pesoFacturable - $oldBillableWeight) > 0.01;

        if ($costoHaCambiado || $pesoHaCambiado) {
            
            $this->historialModel->insertaHistoriaCostoEnvios(
                $itemId,
                $oldListCost,
                $costoLista,    // Nuevo costo va aquí
                $oldBillableWeight,
                $pesoFacturable // Nuevo peso va aquí
            );
            $haCambiado = true;
            error_log("Updater: CAMBIO DETECTADO para $itemId. Costo: $oldListCost -> $costoLista. Peso: $oldBillableWeight -> $pesoFacturable.");
        }



        // 6. Preparar datos para el Modelo (9 campos totales)
        $dataToSave = [
            // 6 Parámetros de la Consulta (de ItemModel)
            'item_id' => $itemId, 
            'item_price' => $paramsDb['item_price'],
            'listing_type_id' => $paramsDb['listing_type_id'],
            'mode' => $paramsDb['mode'],
            'condicion' => $paramsDb['condicion'], 
            'logistic_type' => $paramsDb['logistic_type'],
            
            // 3 Parámetros del Resultado de la API (Nuevos)
            'list_cost' => (float)$costoLista, 
            'currency_id' => $moneda,
            'billable_weight' => (float)$pesoFacturable,
        ];

        // 7. Persistencia de datos (Llamada al método UPSERT)
        $insertResult = $this->envioModel->insertOrUpdateShippingData($dataToSave);
        
        if ($insertResult) {
            // Se usa el mensaje final con el campo changed para el cronjob
            $message = "Datos de envío actualizados. Costo de lista: $costoLista $moneda.";
            if ($haCambiado) {
                 $message .= " ¡ATENCIÓN: Se registró cambio en el historial!";
            }
            
            return [
                'success' => true, 
                'message' => $message,
                'changed' => $haCambiado // Indica si hubo registro en historial
            ];
        } else {
            // Manejo de error de la persistencia
            error_log("Updater: Error fatal al guardar 9 campos en BD para item $itemId.");
            return [
                'success' => false, 
                'message' => "Error al guardar los 9 campos de envío en la BD.",
                'changed' => false
            ];
        }


    }

    
}