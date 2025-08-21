<?php

require_once 'Model.php';

class ProveedorModel extends Model
{
    public function getProveedores()
    {
        $sql = "SELECT * FROM proveedores";
        $result = $this->db->query($sql);
        $proveedores = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $proveedores[] = $row;
            }
        }
        return $proveedores;
    }

    public function addProveedor($nombre_proveedor)
    {
        // Se corrigió la lista de variables a enlazar en bind_param para que coincida con la consulta
        $stmt = $this->db->prepare("INSERT INTO proveedores (nombre_proveedor) VALUES (?)");
        $stmt->bind_param("s", $nombre_proveedor);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function getProveedorById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM proveedores WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateProveedor($id, $nombre_proveedor)
    {
        // Se corrigió la firma del método para que coincida con los parámetros que se necesitan
        $stmt = $this->db->prepare("UPDATE proveedores SET nombre_proveedor = ? WHERE id = ?");
        $stmt->bind_param("si", $nombre_proveedor, $id);
        return $stmt->execute();
    }


    public function eliminarProveedor($id)
    {
        $stmt = $this->db->prepare("DELETE FROM proveedores WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}