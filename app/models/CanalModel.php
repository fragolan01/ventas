<?php

require_once 'Model.php';

class CanalModel extends Model
{
    public function getCanales()
    {
        $sql = "SELECT * FROM canales";
        $result = $this->db->query($sql);
        $canales = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $canales[] = $row;
            }
        }
        return $canales;
    }

    public function addCanal($nombre, $descripcion, $logo_url, $api_base_url, $activo)
    {
        // Se corrigió la lista de variables a enlazar en bind_param para que coincida con la consulta
        $stmt = $this->db->prepare("INSERT INTO canales (nombre, descripcion, logo_url, api_base_url, activo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $nombre, $descripcion, $logo_url, $api_base_url, $activo);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function getCanalById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM canales WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateCanal($id, $nombre, $descripcion, $logo_url, $api_base_url, $activo)
    {
        // Se corrigió la firma del método para que coincida con los parámetros que se necesitan
        $stmt = $this->db->prepare("UPDATE canales SET nombre = ?, descripcion = ?, logo_url = ?, api_base_url = ?, activo = ? WHERE id = ?");
        $stmt->bind_param("ssssii", $nombre, $descripcion, $logo_url, $api_base_url, $activo, $id);
        return $stmt->execute();
    }


    public function eliminarCanal($id)
    {
        $stmt = $this->db->prepare("DELETE FROM canales WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}