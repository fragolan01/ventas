<?php

require_once 'Model.php';

class ModeloModel extends Model
{
    public function getModelos()
    {
        $sql = "SELECT * FROM modelos";
        $result = $this->db->query($sql);
        $modelos = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $modelos[] = $row;
            }
        }
        return $modelos;
    }

    public function addModelo($modelo)
    {
        // Se corrigió la lista de variables a enlazar en bind_param para que coincida con la consulta
        $stmt = $this->db->prepare("INSERT INTO modelos (modelo) VALUES (?)");
        $stmt->bind_param("s", $modelo);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function getModeloById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM modelos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateModelo($id, $modelo)
    {
        // Se corrigió la firma del método para que coincida con los parámetros que se necesitan
        $stmt = $this->db->prepare("UPDATE modelos SET modelo = ? WHERE id = ?");
        $stmt->bind_param("si", $modelo, $id);
        return $stmt->execute();
    }


    public function eliminarModelo($id)
    {
        $stmt = $this->db->prepare("DELETE FROM modelos WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}