<?php

require_once 'Model.php';

class ProductoModel extends Model
{
    public function getProductos()
    {
        $sql = "SELECT * FROM productos";
        $result = $this->db->query($sql);
        $productos = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
        }
        return $productos;
    }

    public function addProducto($title, $category_id, $price, $currency_id, $available_quantity, $buying_mode, $conditions, $listing_type_id, $warranty_type, $warranty_time, $pictures, $description, $attributes, $product_id, $shipping_mode, $shipping_free, $status)
    {
        $stmt = $this->db->prepare("INSERT INTO productos 
        (title, category_id, price, currency_id, available_quantity, buying_mode, conditions, listing_type_id, warranty_type, warranty_time, pictures, description, attributes, product_id, shipping_mode, shipping_free, status) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

        // usar "s" para todo, salvo números enteros ("i")
        $stmt->bind_param(
            "ssdsisssssssssisi",
            $title, $category_id, $price, $currency_id,
            $available_quantity, $buying_mode, $conditions,
            $listing_type_id, $warranty_type, $warranty_time,
            $pictures, $description, $attributes, $product_id,
            $shipping_mode, $shipping_free, $status
        );

        return $stmt->execute();
    }

    public function getProductoById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Compatibilidad PHP 5 (sin get_result)
        $result = $stmt->get_result();
        if ($result) {
            return $result->fetch_assoc();
        } else {
            $meta = $stmt->result_metadata();
            $fields = [];
            $row = [];
            while ($field = $meta->fetch_field()) {
                $fields[] = &$row[$field->name];
            }
            call_user_func_array([$stmt, 'bind_result'], $fields);
            if ($stmt->fetch()) {
                return $row;
            }
        }
        return null;
    }

    public function updateProducto($id, $title, $category_id, $price, $currency_id, $available_quantity, $buying_mode, $conditions, $listing_type_id, $warranty_type, $warranty_time, $pictures, $description, $attributes, $product_id, $shipping_mode, $shipping_free, $status)
    {
        $stmt = $this->db->prepare("UPDATE productos SET 
            title = ?, category_id = ?, price = ?, currency_id = ?, 
            available_quantity = ?, buying_mode = ?, conditions = ?, 
            listing_type_id = ?, warranty_type = ?, warranty_time = ?, 
            pictures = ?, description = ?, attributes = ?, 
            product_id = ?, shipping_mode = ?, shipping_free = ?, status = ?  
            WHERE id = ?");

        $stmt->bind_param(
            "ssdsisssssssssisii",
            $title, $category_id, $price, $currency_id, $available_quantity,
            $buying_mode, $conditions, $listing_type_id,
            $warranty_type, $warranty_time, $pictures, $description,
            $attributes, $product_id, $shipping_mode, $shipping_free,
            $status, $id
        );

        return $stmt->execute();
    }

    public function eliminarProducto($id)
    {
        $stmt = $this->db->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
