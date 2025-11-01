<?php

require_once '../app/models/ItemModel.php';
require_once '../app/services/MeliApiClient.php';

class MeliImportador 
{
    private $itemModel; 
    private $meliApiClient;

    public function __construct()
    {
        $this->itemModel = new ItemModel(); 
        
        // Usar __DIR__ para rutas seguras en servicios
        $secrets = require __DIR__ . '/../../config/secrets.php'; 
        $token = $secrets['prod_mercado_libre']['prodtToken'];
        
        $this->meliApiClient = new MeliApiClient($token); 
    }

    //  no está forzado por una interfaz de proveedor
    public function importarItemsPorLista(array $data): array
    {
        $resultados = [];

        $item_id_input = $data['item_id_input'];

        // Convertir el string a un array limpio de items
        $item_id_input = array_map('trim', explode(',', $item_id_input));
        $item_id_input = array_filter($item_id_input);

        // Obtener items ya existentes (usando el modelo)
        // ************** crear esta función en ItemModel.php
        $itemsExistentes = $this->itemModel->obtenerItemsPorIds($item_id_input); 
        

        $id = array_column($itemsExistentes, 'id'); 

        // Solo consultar los que aún no existen
        $itemId_a_consultar = array_diff($item_id_input, $id);

        foreach ($itemId_a_consultar as $itemId) {
            
            $item_data = $this->meliApiClient->consultarApiMeli($itemId);

            // 1. Manejo de Errores de la API
            if (isset($item_data['error']) && $item_data['error'] === true) {
                $resultados[$itemId] = ['success' => false, 'message' => "Fallo ML API ({$item_data['http_code']}): {$item_data['message']}"];
                continue;
            }
            if (!$item_data) {
                $resultados[$itemId] = ['success' => false, 'message' => "Fallo de conexión o respuesta API inválida."];
                continue;
            }

            //2. Mapeo de datos
            $datosParaDB = [
                'item_id' => $item_data['id'],
                'site_id' => $item_data['site_id'],
                'title' => $item_data['title'],
                'family_name' => $item_data['family_name'],
                'family_id' => $item_data['family_id'],
                'seller_id' => $item_data['seller_id'],
                'category_id' => $item_data['category_id'],
                'user_product_id' => $item_data['user_product_id'],
                'official_store_id' => $item_data['official_store_id'],
                'price' => $item_data['price'],
                'base_price' => $item_data['base_price'],
                'original_price' => $item_data['original_price'],
                'inventory_id' => $item_data['inventory_id'],
                'initial_quantity' => $item_data['initial_quantity'],
                'available_quantity' => $item_data['available_quantity'],
                'sold_quantity' => $item_data['sold_quantity'],
                'buying_mode' => $item_data['buying_mode'],
                'listing_type_id' => $item_data['listing_type_id'],
                'start_time' => $item_data['start_time'],
                'condicion' => $item_data['condition'],
                'permalink' => $item_data['permalink'],
                'thumbnail_id' => $item_data['thumbnail_id'],
                'video_id' => $item_data['video_id'],
                'descriptions' => $item_data['descriptions'],
                'accepts_mercadopago' => $item_data['accepts_mercadopago'],
                'international_delivery_mode' => $item_data['international_delivery_mode'],
                'warranty' => $item_data['warranty'],
                'catalog_product_id' => $item_data['catalog_product_id'],
                'domain_id' => $item_data['domain_id'],
                'date_created' => $item_data['date_created'],
                'channels' => implode(', ', $item_data['channels'])
                // 'channels' => $item_data['channels']
            ];

            // 3. Insertar el item en la base de datos (Modelo)
            // ************ CREAR METODO EN MODELO ItemModel.php
            $insertado = $this->itemModel->insertarNuevoItem($datosParaDB); 

            if ($insertado) {
                $resultados[$itemId] = ['success' => true, 'message' => "Item importado con éxito."];
            } else {
                $resultados[$itemId] = ['success' => false, 'message' => "Error al guardar en la base de datos."];
            }
        }

        return $resultados;
    }
}