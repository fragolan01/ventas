<?php

require_once 'Model.php';

class ProductoModel extends Model
{
    public function getProductos()
    {
        $sql = "SELECT * FROM productos";
        $result = $this->db->query($sql);
        $productos = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
        }
        return $productos;
    }

    public function addProducto($title, $category_id, $price, $currency_id, $available_quantity, $buying_mode, $conditions, $listing_type_id, $warranty_type, $warranty_time, $pictures, $description, $attributes, $product_id, $shipping_mode, $shipping_free, $status)
    {
        // Se corrigió la lista de variables a enlazar en bind_param para que coincida con la consulta
        $stmt = $this->db->prepare("INSERT INTO productos (title, category_id, price, currency_id, available_quantity, buying_mode, conditions, listing_type_id, warranty_type, warranty_time, pictures, description, attributes, product_id, shipping_mode, shipping_free, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssdsisssssssssisi", $title, $category_id, $price, $currency_id, $available_quantity, $buying_mode, $conditions, $listing_type_id, $warranty_type, $warranty_time, $pictures, $description, $attributes, $product_id, $shipping_mode, $shipping_free, $status);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function getProductoById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateProducto($id, $title, $category_id, $price, $currency_id, $available_quantity, $buying_mode, $conditions, $listing_type_id, $warranty_type, $warranty_time, $pictures, $description, $attributes, $product_id, $shipping_mode, $shipping_free, $status)
    {
        // Se corrigió la firma del método para que coincida con los parámetros que se necesitan
        $stmt = $this->db->prepare("UPDATE productos SET title = ?, category_id = ?, price = ?, currency_id = ?, available_quantity = ?, buying_mode = ?, conditions = ?, listing_type_id = ?, warranty_type = ?, warranty_time = ?, pictures = ?, description = ?, attributes = ?, product_id = ?, shipping_mode = ?, shipping_free = ?, status = ?  WHERE id = ?");
        $stmt->bind_param("ssdsisssssssssisii", 
        $title, $category_id, $price, $currency_id, $available_quantity, $buying_mode, $conditions, $listing_type_id, $warranty_type, $warranty_time, $pictures, $description, $attributes, $product_id, $shipping_mode, $shipping_free, $status, $id);
            return $stmt->execute();
    }


    public function eliminarProducto($id)
    {
        $stmt = $this->db->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}