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

     public function addTienda($nombre, $canal)
    {
        // Usa sentencias preparadas para prevenir inyección SQL (más seguro)
        $stmt = $this->db->prepare("INSERT INTO tiendas (nombre, canal_id) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $canal);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}