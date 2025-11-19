<?php
// app/services/ActualizaEnviosMeli.php

// Asegúrate de incluir el nuevo modelo
require_once '../app/models/EnviomeliHistorialModel.php'; 
require_once '../app/models/EnviomeliModel.php'; 

// ... (clase ActualizaEnviosMeli)

    public function updateShippingCost($itemId)
    {
        // ... (código para obtener datos de la API: $newShippingData) ...

        // Inicializar modelos
        $envioMeliModel = new EnviomeliModel();
        $historialModel = new EnviomeliHistorialModel();

        // 1. Obtener datos antiguos de la base de datos (si existen)
        $oldData = $envioMeliModel->obtenerDatosEnvioPorItemId($itemId); 
        
        // Extraer los nuevos datos de costo y peso
        $newListCost = $newShippingData['shipping_option']['list_cost'] ?? 0;
        $newBillableWeight = $newShippingData['shipping_option']['billable_weight'] ?? 0;

        // 2. Comprobar si hay cambios y guardar en historial
        if ($oldData) {
            $oldListCost = $oldData['list_cost'];
            $oldBillableWeight = $oldData['billable_weight'];
            
            // Usamos un pequeño margen para comparar floats
            $costoCambio = abs($newListCost - $oldListCost) > 0.01; 
            $pesoCambio = abs($newBillableWeight - $oldBillableWeight) > 0.01;
            
            if ($costoCambio || $pesoCambio) {
                // Hay un cambio: Insertar en el historial
                $historialModel->insertarRegistroCambio(
                    $itemId,
                    $oldListCost,
                    $newListCost,
                    $oldBillableWeight,
                    $newBillableWeight
                );
            }
        }
        
        // 3. (Continuar) Actualizar o insertar los datos en la tabla principal envios_meli
        // ... (Tu llamada a insertOrUpdateShippingData aquí) ...
        
        return $resultadoDeActualizacion;
    }