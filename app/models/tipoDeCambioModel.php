<?php
require_once  'Model.php';

class TipoDeCambioModel extends Model
{
    public function getTipoDeCambio()
    {
        $sql = "SELECT normal FROM tipo_de_cambio ORDER BY fecha DESC LIMIT 1";
        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['normal'];
        }
        // Si no hay resultados
        return null;

    }
}