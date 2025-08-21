<?php

require_once 'Model.php';

class EnvioModel extends Model
{
    public function getenvios()
    {
        $sql = "SELECT * FROM envios";
        $result = $this->db->query($sql);
        $envios = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $envios[] = $row;
            }
        }
        return $envios;
    }

    public function addEnvio($nombre_envio, $costo, $moneda_id)
    {
        // Se corrigió la lista de variables a enlazar en bind_param para que coincida con la consulta
        $stmt = $this->db->prepare("INSERT INTO envios (nombre_envio, costo, moneda_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $nombre_envio, $costo, $$moneda_id);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function getEnvioById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM envios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateEnvio($id, $nombre_envio, $costo)
    {
        // Se corrigió la firma del método para que coincida con los parámetros que se necesitan
        $stmt = $this->db->prepare("UPDATE envios SET nombre_envio = ?, costo = ? WHERE id = ?");
        $stmt->bind_param("sdi", $nombre_envio, $costo, $id);
        return $stmt->execute();
    }


    public function eliminarEnvio($id)
    {
        $stmt = $this->db->prepare("DELETE FROM envios WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

        
}