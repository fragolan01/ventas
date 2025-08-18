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


    public function getTiendaById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tiendas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateTienda($id, $nombre, $canal)
    {
        $stmt = $this->db->prepare("UPDATE tiendas SET nombre = ?, canal_id = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nombre, $canal, $id);
        return $stmt->execute();
    }



    public function eliminarTienda($id)
    {
        $stmt = $this->db->prepare("DELETE FROM tiendas WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    
}