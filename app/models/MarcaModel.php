<?php

require_once 'Model.php';

class MarcaModel extends Model
{
    public function getMarcas()
    {
        $sql = "SELECT * FROM marcas";
        $result = $this->db->query($sql);
        $marcas = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $marcas[] = $row;
            }
        }
        return $marcas;
    }

    public function addMarca($proveedor_id, $nombre_marca)
    {
        // Se corrigió la lista de variables a enlazar en bind_param para que coincida con la consulta
        $stmt = $this->db->prepare("INSERT INTO marcas (proveedor_id, nombre_marca) VALUES (?, ?)");
        $stmt->bind_param("is", $proveedor_id, $nombre_marca);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function getMarcaById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM marcas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateMarca($id, $proveedor_id, $nombre_marca)
    {
        // Se corrigió la firma del método para que coincida con los parámetros que se necesitan
        $stmt = $this->db->prepare("UPDATE marcas SET proveedor_id = ?, nombre_marca = ? WHERE id = ?");
        $stmt->bind_param("isi", $proveedor_id, $nombre_marca, $id);
        return $stmt->execute();
    }


    public function eliminarMarca($id)
    {
        $stmt = $this->db->prepare("DELETE FROM marcas WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}