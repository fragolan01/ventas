<?php
// services/ActualizaEnviosMeli.php (Versión Final Corregida)

require_once '../app/models/ItemModel.php';
require_once '../app/models/EnviomeliModel.php';
require_once '../app/services/MeliApiClient.php';

class ActualizaEnviosMeli
{
    private $itemModel;
    private $envioModel;
    private $meliClient;
    private $meliUserId = 2424408169; // ID de usuario fijo
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
        $this->meliClient = new MeliApiClient($token);
        // NOTA: Asegúrate de que tus modelos manejen la inyección de dependencia de la conexión a la DB si es necesario.
        $this->itemModel = new ItemModel();
        $this->envioModel = new EnviomeliModel(); 
    }

    /**
     * Procesa la solicitud de actualización de costos de envío para un ítem.
     * Es el cuerpo del 'Job' asíncrono.
     */
    public function updateShippingCost($itemId)
    {
        // 1. Obtener datos de la BD para la consulta
        $paramsDb = $this->itemModel->datosCostoEnvio($itemId);

        if (!$paramsDb) {
            error_log("Updater: Ítem $itemId no encontrado en la BD.");
            return ['success' => false, 'message' => "Ítem no encontrado."];
        }

        // 2. Preparar parámetros para la llamada a la API
        $meliParams = $paramsDb;
        $meliParams['verbose'] = 'true';
        
        // 3. Consultar la API de ML
        $apiResponse = $this->meliClient->getFreeShippingOptions($this->meliUserId, $meliParams);

        // 4. Manejo de errores de la API
        if (isset($apiResponse['error']) && $apiResponse['error'] === true) {
            error_log("Updater: Error al consultar ML para envío de $itemId. Mensaje: " . $apiResponse['message']);
            return ['success' => false, 'message' => "Error de API: " . $apiResponse['message']];
        }

        // =================================================================
        // 5. PARSEO Y EXTRACCIÓN DEL COSTO (Lógica de Negocio)
        // =================================================================
        $costoFinal = 0.00;
        $moneda = 'MXN'; // Valor por defecto
        $optionMode = $meliParams['mode']; // Usar el modo consultado por defecto
        
        // Lógica de extracción: Asumimos que la primera opción es la relevante
        if (isset($apiResponse['shipping_options'][0]['cost'])) {
            $costoFinal = (float)$apiResponse['shipping_options'][0]['cost'];
            $moneda = $apiResponse['shipping_options'][0]['currency_id'] ?? $moneda;
            $optionMode = $apiResponse['shipping_options'][0]['mode'] ?? $optionMode;
        } 
        
        // 6. Preparar datos para el Modelo (Unificando datos de la consulta y el resultado)
        $dataToSave = [
            'item_id' => $itemId, // ID para la búsqueda/clave
            // Parámetros de la consulta (de la BD)
            'item_price' => $meliParams['item_price'],
            'listing_type_id' => $meliParams['listing_type_id'],
            'mode' => $meliParams['mode'],
            'condition' => $meliParams['condition'],
            'logistic_type' => $meliParams['logistic_type'],
            // Resultados de la API (Campos de persistencia)
            'costo_envio' => $costoFinal,
            'moneda_envio' => $moneda,
            'shipping_option_mode' => $optionMode
        ];

        // 7. Persistencia de datos (Llamada al método UPSERT)
        $insertResult = $this->envioModel->insertOrUpdateShippingData($dataToSave);
        
        if ($insertResult) {
            return ['success' => true, 'message' => "Costo de envío actualizado con éxito."];
        } else {
            // Este error solo ocurre si hay un fallo en la ejecución de la consulta SQL del modelo
            error_log("Updater: Error fatal al guardar en BD para item $itemId.");
            return ['success' => false, 'message' => "Error al guardar los datos de envío en la BD."];
        }
    }
}