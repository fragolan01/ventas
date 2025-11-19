<?php
// app/models/EnviomeliModel.php

require_once 'Model.php';

class EnviomeliModel extends Model
{
    /**
     * Inserta un nuevo registro de envío (Datos de Consulta).
     * @param array $data Contiene los 6 campos de la tabla.
     */
    public function addEnviosMeli(array $data)
    {
        $sql = "INSERT INTO envios_meli (
                    item_id, item_price, listing_type_id, mode, condicion, logistic_type
                ) VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        
        // 6 Tipos, 6 Variables: s (item_id), d (item_price), ssss (varchars)
        $stmt->bind_param(
            "sdssss", 
            $data['item_id'], 
            $data['item_price'], 
            $data['listing_type_id'], 
            $data['mode'], 
            $data['condicion'], // ASUMIMOS QUE LA CLAVE ES 'condicion' EN $data
            $data['logistic_type']
        );
        
        $result = $stmt->execute();
        
        if (!$result) {
            // DEPURACIÓN: Si falla, registra el error SQL exacto
            error_log("SQL Error (INSERT): " . $stmt->error); 
        }
        
        $stmt->close();
        return $result;
    }

    /**
     * Actualiza un registro de envío existente (Datos de Consulta).
     * Nota: Corregido para solo actualizar los campos necesarios (5 SETs + 1 WHERE = 6 parámetros)
     */
    public function updateEnvio(array $data)
    {
        $sql = "UPDATE envios_meli SET 
                    item_price = ?, 
                    listing_type_id = ?, 
                    mode = ?, 
                    condicion = ?, 
                    logistic_type = ?
                WHERE item_id = ?";
                
        $stmt = $this->db->prepare($sql);

        // 6 Tipos: d (item_price), ssss (4 varchars), s (item_id en WHERE)
        $stmt->bind_param(
            "dsssss", 
            $data['item_price'], 
            $data['listing_type_id'], 
            $data['mode'], 
            $data['condicion'], 
            $data['logistic_type'],
            $data['item_id'] // Usado en el WHERE
        );
        
        $result = $stmt->execute();
        
        if (!$result) {
            // DEPURACIÓN: Si falla, registra el error SQL exacto
            error_log("SQL Error (UPDATE): " . $stmt->error); 
        }
        
        $stmt->close();
        return $result;
    }
    

/*
    public function insertOrUpdateShippingData(array $data)
    {
        $sql = "INSERT INTO envios_meli (
                    item_id, item_price, listing_type_id, mode, condicion, logistic_type
                ) VALUES (?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    item_price = VALUES(item_price),
                    listing_type_id = VALUES(listing_type_id),
                    mode = VALUES(mode),
                    condicion = VALUES(condicion),
                    logistic_type = VALUES(logistic_type)";

        $stmt = $this->db->prepare($sql);

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
            error_log("SQL Error (UPSERT): " . $stmt->error);
        }

        $stmt->close();
        return $result;
    }
    */


    /*
     
    public function insertOrUpdateShippingData(array $data)
    {
        // 1. Intentar actualizar primero
        $updated = $this->updateEnvio($data);
        
        if ($updated && $this->db->affected_rows > 0) {
            return true; // Se actualizó
        } 
        
        if ($updated && $this->db->affected_rows === 0) {
             return $this->addEnviosMeli($data); // Se inserta
        }
        
        if (!$updated) {
            error_log("EnvioModel: Falló la operación de UPDATE/INSERT para item_id: " . $data['item_id']);
        }

        return false;
    }
        
    */

    // app/models/EnviomeliModel.php

    // Esta función reemplaza a addEnviosMeli, updateEnvio, e insertOrUpdateShippingData
    public function insertOrUpdateShippingData(array $data)
    {
        $sql = "INSERT INTO envios_meli (
                    item_id, item_price, listing_type_id, mode, condicion, logistic_type,
                    list_cost, currency_id, billable_weight 
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    item_price = VALUES(item_price),
                    listing_type_id = VALUES(listing_type_id),
                    mode = VALUES(mode),
                    condicion = VALUES(condicion),
                    logistic_type = VALUES(logistic_type),
                    list_cost = VALUES(list_cost),           -- AÑADIDO
                    currency_id = VALUES(currency_id),       -- AÑADIDO
                    billable_weight = VALUES(billable_weight)"; // AÑADIDO

        $stmt = $this->db->prepare($sql);

        // 9 Tipos: s d s s s s d s d (de acuerdo a tu estructura de tabla)
        $stmt->bind_param(
            "sdssssdsd",
            $data['item_id'],
            $data['item_price'],
            $data['listing_type_id'],
            $data['mode'],
            $data['condicion'],
            $data['logistic_type'],
            $data['list_cost'],        // Nuevo dato de la API
            $data['currency_id'],      // Nuevo dato de la API
            $data['billable_weight']   // Nuevo dato de la API
        );

        $result = $stmt->execute();

        if (!$result) {
            error_log("SQL Error (UPSERT ON DUPLICATE): " . $stmt->error);
        }

        $stmt->close();
        return $result;
    }


    public function obtenerTodosLosEnvios()
        {
            // Se seleccionan los campos relevantes para la tabla
            $sql = "SELECT item_id, item_price, mode, logistic_type, list_cost, currency_id, billable_weight 
                    FROM envios_meli 
                    ORDER BY id DESC"; // Ordenar por ID descendente para ver los más recientes primero

            // Ejecutar la consulta sin parámetros (no es una sentencia preparada)
            $result = $this->db->query($sql);

            if (!$result) {
                error_log("SQL Error (obtenerTodosLosEnvios): " . $this->db->error);
                return [];
            }

            // Obtener todos los resultados como un array asociativo
            $data = $result->fetch_all(MYSQLI_ASSOC);
            
            // Liberar el resultado
            $result->free(); 
            
            return $data;
        }


    // Cron job
    public function obtenerCostosEnvioPorItemId($itemId)
    {
        $sql = "SELECT list_cost, billable_weight 
                FROM envios_meli 
                WHERE item_id = ? 
                LIMIT 1";
                
        $stmt = $this->db->prepare($sql);
        
        // por si falla
        if ($stmt === false) {
            error_log("SQL Prepare Error: " . $this->db->error);
            return false;
        }
        
        $stmt->bind_param("s", $itemId);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        $stmt->close();
        
        return $data; 
    }

        
    

}