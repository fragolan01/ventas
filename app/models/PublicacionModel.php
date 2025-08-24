<?php

require_once 'Model.php';

class PublicacionModel extends Model
{
    public function getTipoPublicacion()
    {
        $sql = "SELECT * FROM tipos_publicaciones";
        $result = $this->db->query($sql);
        $tiposPublicaciones = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $tiposPublicaciones[] = $row;
            }
        }
        return $tiposPublicaciones;
    }

    public function addTipoPublicacion($tipo_publi_id, $name, $canal_id)
    {
        // Se corrigió la lista de variables a enlazar en bind_param para que coincida con la consulta
        $stmt = $this->db->prepare("INSERT INTO tipos_publicaciones (tipo_publi_id, name, canal_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $tipo_publi_id, $name, $canal_id);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function getTipoPublicacionById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tipos_publicaciones WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateTipoPublicacion($id, $tipoPublicacionId, $name, $canal_id)
    {
        // Se corrigió la firma del método para que coincida con los parámetros que se necesitan
        $stmt = $this->db->prepare("UPDATE tipos_publicaciones SET tipo_publi_id = ?, name = ?, canal_id = ? WHERE id = ?");
        $stmt->bind_param("ssi", $tipo_publi_id, $name, $canal_id);
        return $stmt->execute();
    }


    public function eliminarTipoPublicacion($id)
    {
        $stmt = $this->db->prepare("DELETE FROM tipos_publicaciones WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}