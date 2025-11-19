<?php

require_once 'Model.php';

class EnviomeliHistorialModel extends Model
{
    /**
     * Insertsa regidtr tipo envio en tabla: envios_meli_historial
     * @param string $item_id
     * @param float $old_list_cost
     * @param float $new_list_cost
     * @param float $old_billable_weigh
     * @param float $new_billable_weig 
     */
    
    public function insertaHistoriaCostoEnvios(
        $item_id,
        $old_list_cost,
        $new_list_cost,
        $old_billable_weigh,
        $new_billable_weight
    ){
        $sql = "INSERT INTO envios_meli_historial (item_id, old_list_cost, new_list_cost, old_billable_weight, new_billable_weight) 
                            VALUES (?,?,?,?,?)";
        
        $stmt = $this->db->prepare($sql);

        if ($stmt === false) {
             error_log("SQL Error (Historial Prepare): " . $this->db->error);
             return false;
        }

        // s()string, d(double/float)
        $stmt->bind_param(
            "sdddd",
            $item_id,
            $old_list_cost,
            $new_list_cost,
            $old_billable_weight,
            $new_billable_weight
        );

        $result = $stmt->execute();

        if(!$result){
            error_log("SQL Error (Historial): ".$stmt->error);
        }
        $stmt->close();
        return $result;
    }
}