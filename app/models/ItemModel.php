<?php

require_once 'Model.php';

class ItemModel extends Model
{

    
    
    // El Importador ahora llama a este método directamente:
    public function insertarNuevoItem($data) 
    {
        return $this->insertaItem($data);
    }
    
    // === ITEM BUSCADOR ===
    public function obtenerItemId($item_id)
    {
        $sql = "SELECT item_id FROM item_meli WHERE item_id = ?"; 
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log("Error al preparar la consulta de obtenerItemId: " . $this->db->error);
            return null;
        }

        $stmt->bind_param("s", $item_id); 
        $stmt->execute();
        $result = $stmt->get_result();
        $producto = $result->fetch_assoc();
        $stmt->close();

        return $producto['item_id'] ?? null;
    }

    // multiples items
    public function obtenerItemsPorIds(array $ids)
    {
        if (empty($ids)) return [];

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $types = str_repeat('s', count($ids)); 

        $sql = "SELECT item_id FROM item_meli WHERE item_id IN ($placeholders)";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log("Error al preparar obtenerItemsPorIds: " . $this->db->error);
            return [];
        }
        
        // Usar array_values() para resetear las claves si $ids no tiene índices secuenciales
        $stmt->bind_param($types, ...$ids); 
        $stmt->execute();
        $result = $stmt->get_result();
        $productos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $productos;
    }
    

    // === ITEMS INSERCIÓN ===
    private function insertaItem($data)
    {
        $sql = "INSERT INTO item_meli (
        item_id, 
        site_id, 
        title, 
        family_name, 
        family_id, 
        seller_id, 
        category_id, 
        user_product_id, 
        official_store_id, 
        price, 
        base_price, 
        original_price, 
        inventory_id, 
        initial_quantity,
        available_quantity,
        sold_quantity, 
        buying_mode, 
        listing_type_id, 
        start_time,
        condicion,
        permalink,
        thumbnail_id,
        video_id,
        descriptions,
        accepts_mercadopago,
        shipping,
        logistic_type,
        international_delivery_mode,
        estado,
        warranty,
        catalog_product_id,
        domain_id,
        date_created, 
        channels) 
        
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log("Error al preparar la consulta de inserción: " . $this->db->error);
            return false;
        }

        $types = "sssiissidddssiiisssssssissssssssss";
        
        $stmt->bind_param($types,
            $data['item_id'], 
            $data['site_id'], 
            $data['title'], 
            $data['family_name'],
            $data['family_id'], 
            $data['seller_id'], 
            $data['category_id'], 
            $data['user_product_id'],
            $data['official_store_id'], 
            $data['price'], 
            $data['base_price'], 
            $data['original_price'],
            $data['inventory_id'], 
            $data['initial_quantity'], 
            $data['available_quantity'],
            $data['sold_quantity'], 
            $data['buying_mode'], 
            $data['listing_type_id'], 
            $data['start_time'], 
            $data['condicion'], 
            $data['permalink'],
            $data['thumbnail_id'], 
            $data['video_id'], 
            $data['descriptions'], 
            $data['accepts_mercadopago'], 
            $data['shipping'],
            $data['logistic_type'], 
            $data['international_delivery_mode'], 
            $data['estado'], 
            $data['warranty'], 
            $data['catalog_product_id'], 
            $data['domain_id'],
            $data['date_created'], 
            $data['channels']            
        );

        $result = $stmt->execute();
        
        $last_id = $this->db->insert_id; 
        $return_id = $result ? $last_id : false;
        
        $stmt->close();
        return $return_id; 
    }



    
    // Muestra todos los ITEMS de la tabla
    public function obtenerTodosLosItems()
    {
        $sql = "SELECT * FROM item_meli";
        $result = $this->db->query($sql);
        $productos =[];

        if($result -> num_rows > 0){
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
        }

        return $productos;       
    }



// Datos Consulta costo envio
public function datosCostoEnvio($item_id) 
{
    $sql = "SELECT price, listing_type_id, condicion, shipping, logistic_type,
            -- Campos muestra
            	title
            FROM item_meli 
            WHERE item_id = ?";

    $stmt = $this->db->prepare($sql);

    if (!$stmt) {
        error_log("Error al preparar la consulta de datosCostoEnvio: " . $this->db->error);
        return null;
    }

    $stmt->bind_param("s", $item_id);

    if (!$stmt->execute()) {
        error_log("Error al ejecutar datosCostoEnvio: " . $stmt->error);
        return null;
    }

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    if ($data) {
        // Mapeo de nombres para el Servicio (item_price y las 5 variables)
        return [
            'item_id' => $item_id,
            'item_price' => $data['price'],
            'listing_type_id' => $data['listing_type_id'],
            // Mapeo 'shipping' (de la DB) a 'mode' (que espera la API y envios_meli)
            'mode' => $data['shipping'], 
            'condicion' => $data['condicion'], 
            'logistic_type' => $data['logistic_type'],
            'title'=> $data['title']
        ];
    }

    return null;
}


}