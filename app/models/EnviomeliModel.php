<?php

require_once 'Model.php';

class EnviomeliModel extends Model
{
    // Método de soporte: Verifica si existe un envío para el item_id
    public function getEnvioMeliByItemId($itemId)
    {
        $stmt = $this->db->prepare("SELECT id FROM envios_meli WHERE item_id = ?");
        $stmt->bind_param("s", $itemId);
        $stmt->execute();
        $result = $stmt->get_result();
        // Solo necesitamos saber si hay alguna fila (fetch_row)
        $exists = $result->fetch_row();
        $stmt->close();
        return $exists; 
    }

    /**
     * Inserta un nuevo registro de envío.
     * @param array $data Contiene todos los campos del registro (incluyendo los de resultado de la API).
     */
    public function addEnviosMeli(array $data)
    {
        // NOTA: Asegúrate que esta lista de campos coincida con tu tabla 'envios_meli'
        $sql = "INSERT INTO envios_meli (
                    item_id, item_price, listing_type_id, mode, condicion, logistic_type
                ) VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        
        // El tipo de dato 's' (string) se usará para el item_id y los Varchar. 
        // 'd' (double) se usará para el precio y el costo de envío (decimal).
        $stmt->bind_param(
            "sdssss", // Tipos: string, double, string, string, string, string, double, string, string
            $data['item_id'], 
            $data['item_price'], 
            $data['listing_type_id'], 
            $data['mode'], 
            $data['condicion'], 
            $data['logistic_type'],
            // $data['costo_envio'], // Nuevo campo de resultado
            // $data['moneda_envio'], // Nuevo campo de resultado
            // $data['shipping_option_mode'] // Nuevo campo de resultado
        );
        
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }


    /**
     * Actualiza un registro de envío existente.
     * @param array $data Contiene todos los campos del registro.
     */
    public function updateEnvio(array $data)
    {
        $sql = "UPDATE envios_meli SET 
                    item_price = ?, 
                    listing_type_id = ?, 
                    mode = ?, 
                    condicion = ?, 
                    logistic_type = ?
                    /* costo_envio = ?,      
                    moneda_envio = ?,
                    shipping_option_mode = ? Campo de resultado */
                WHERE item_id = ?";
                
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param(
            "dssssd", // Tipos: double, string, string, string, string, double, string, string, string (item_id)
            $data['item_price'], 
            $data['listing_type_id'], 
            $data['mode'], 
            $data['condicion'], 
            $data['logistic_type'],
            // $data['costo_envio'],
            // $data['moneda_envio'],
            // $data['shipping_option_mode'],
            $data['item_id'] // Usado en el WHERE
        );
        
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    // ***************************************************************
    // MÉTODO CENTRAL QUE USARÁ EL SERVICIO ActualizaEnviosMeli.php
    // ***************************************************************

    /**
     * Realiza una operación UPSERT (UPDATE o INSERT) en la tabla de envíos.
     * @param array $data Los datos completos del envío a guardar (deben incluir item_id).
     * @return bool True si la operación fue exitosa, false en caso contrario.
     */
    public function insertOrUpdateShippingData(array $data)
    {
        // 1. Intentar actualizar primero
        $updated = $this->updateEnvio($data);
        
        // 2. Si la actualización fue exitosa, o si no se afectó ninguna fila 
        // pero la operación fue técnicamente exitosa, retornamos true.
        if ($updated && $this->db->affected_rows > 0) {
            return true; // Se actualizó
        } 
        
        // 3. Si la actualización fue exitosa pero no se afectó ninguna fila (affected_rows == 0),
        // significa que el item_id no existía. Procedemos a insertar.
        if ($updated && $this->db->affected_rows === 0) {
             return $this->addEnviosMeli($data); // Se inserta
        }
        
        // 4. Si updateEnvio falló por alguna otra razón (no es común en este flujo), retornamos false.
        if (!$updated) {
            error_log("EnvioModel: Falló la operación de UPDATE/INSERT para item_id: " . $data['item_id']);
        }

        return false;
    }
    
    /* --- Métodos de listado y consulta por ID (se mantienen como referencia) ---
    */
    public function getenvios_meli()
    {
        $sql = "SELECT * FROM envios_meli";
        $result = $this->db->query($sql);
        $envios_meli = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $envios_meli[] = $row;
            }
        }
        return $envios_meli;
    }

    public function getEnvioMeliById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM envios_meli WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    // NOTA: Se ha eliminado la versión original de addEnviosMeli y updateEnvio para usar la versión array-based.
}