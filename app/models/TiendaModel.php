<?php

require_once 'Model.php';

class TiendaModel extends Model
{
    public function getTiendas()
    {
        $sql = "SELECT * FROM tiendas";
        $result = $this->db->query($sql);
        $tiendas = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $tiendas[] = $row;
            }
        }
        return $tiendas;
    }
}