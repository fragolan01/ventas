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
            $data['condicion'],
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
                    list_cost = VALUES(list_cost),           
                    currency_id = VALUES(currency_id),       -- 
                    billable_weight = VALUES(billable_weight)"; // 

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
            $data['list_cost'],        
            $data['currency_id'],      
            $data['billable_weight']   
        );

        $result = $stmt->execute();

        if (!$result) {
            error_log("SQL Error (UPSERT ON DUPLICATE): " . $stmt->error);
        }

        $stmt->close();
        return $result;
    }


    /*

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

    */


    public function obtenerTodosLosEnvios()
    {
        // La consulta obtiene todos los campos de envios_meli (Costo Actual)
        // y usa una subconsulta para obtener el Costo Anterior del historial.
        $sql = "SELECT 
                em.item_id, 
                em.item_price, 
                em.mode, 
                em.logistic_type, 
                em.list_cost,             
                em.currency_id, 
                em.billable_weight, 
                (
                    SELECT old_list_cost 
                    FROM envios_meli_historial 
                    WHERE item_id COLLATE utf8mb4_general_ci = em.item_id COLLATE utf8mb4_general_ci
                    ORDER BY fecha_cambio DESC 
                    LIMIT 1
                ) AS costo_anterior_historial
            FROM envios_meli em
            ORDER BY em.id DESC
            ";

        $result = $this->db->query($sql);

        if (!$result) {
            error_log("SQL Error (obtenerTodosLosEnvios): " . $this->db->error);
            return [];
        }

        $data = $result->fetch_all(MYSQLI_ASSOC);
        $result->free(); 
        
        // ==========================================================
        // LÓGICA PHP: Comparación para la columna 'Validaciones'
        // ==========================================================
        
        $datos_procesados = [];
        foreach ($data as $row) {
            // 1. Obtener Costos (asegurando que sean números flotantes)
            $costoActual = (float)$row['list_cost'];
            
            // Si no hay historial, se asume que el costo anterior es 0 o el mismo
            $costoAnterior = (float)($row['costo_anterior_historial'] ?? $costoActual);
            
            $estadoCambio = "Sin Datos Históricos"; // Default
            
            // Compara solo si hay historial (o si se establece un valor por defecto)
            if (isset($row['costo_anterior_historial'])) {
                
                // Usamos la misma tolerancia que en el cronjob
                $diferencia = abs($costoActual - $costoAnterior);
                
                if ($diferencia < 0.01) {
                    $estadoCambio = "Sin cambios";
                } elseif ($costoActual > $costoAnterior) {
                    $estadoCambio = "Incremento";
                } else { // costoActual < costoAnterior
                    $estadoCambio = "Decremento";
                }
            }
            
            // 2. Agregar los campos nuevos al array
            $row['costo_anterior'] = $costoAnterior; // Columna 1
            $row['validaciones'] = $estadoCambio;   // Columna 2
            
            $datos_procesados[] = $row;
        }
        
        return $datos_procesados;
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




    public function obtenerItemsParaCronjob()
    {
        
        // Selecciona todos los campos
        $sql = "SELECT 
                item_id, price, listing_type_id, condicion, shipping, logistic_type    
                FROM item_meli
                WHERE shipping = 'me2'
                "; 
        
        $result = $this->db->query($sql);

        if (!$result) {
            error_log("SQL Error (obtenerItemsParaCronjob): " . $this->db->error);
            return [];
        }

        $data = $result->fetch_all(MYSQLI_ASSOC);
        $result->free(); 
        
        // Devuelve un array de arrays, donde cada sub-array contiene todos los datos de un ítem.
        return $data;
    }

            
    

}