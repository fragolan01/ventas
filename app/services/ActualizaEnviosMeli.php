<?php
// app/services/ActualizaEnviosMeli.php

// Asegúrate de que las rutas sean correctas
require_once '../app/models/ItemModel.php'; 
require_once '../app/models/EnviomeliModel.php';
require_once '../app/services/MeliApiClient.php'; 

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
    }

    public function updateShippingCost($itemId)
    {
        // 1. Obtener datos de la BD para la consulta (6 campos)
        $paramsDb = $this->itemModel->datosCostoEnvio($itemId);

        if (!$paramsDb) {
            error_log("Updater: Ítem $itemId no encontrado en la BD.");
            return ['success' => false, 'message' => "Ítem $itemId no encontrado en la BD."];
        }

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
            return ['success' => true, 'message' => "Datos de envío actualizados. Costo de lista: **$costoLista $moneda**"];
        } else {
            error_log("Updater: Error fatal al guardar 9 campos en BD para item $itemId.");
            return ['success' => false, 'message' => "Error al guardar los 9 campos de envío en la BD."];
        }
    }
}