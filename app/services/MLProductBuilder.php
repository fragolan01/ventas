<?php

class MLProductBuilder {

    /**
     * Construye el payload para la API de Mercado Libre a partir de un arreglo de producto.
     * @param array $row Un arreglo asociativo con los datos del producto de la base de datos.
     * @return array El payload estructurado para la API de Mercado Libre.
     */
    public static function buildPayload(array $row): array
    {
        $payload = [
            "title" => $row['title'],
            "category_id" => $row['category_id'],
            "price" => (float) $row['price'], // Casting explícito para mayor seguridad
            "currency_id" => $row['currency_id'],
            "available_quantity" => (int) $row['available_quantity'],
            "buying_mode" => $row['buying_mode'],
            "condition" => $row['conditions'], // Usar "conditions" de la DB
            "listing_type_id" => $row['listing_type_id'],
            "sale_terms" => [],
            "pictures" => [],
            "attributes" => [],
            "shipping" => [
                "mode" => $row['shipping_mode'],
                "local_pick_up" => (bool) $row['shipping_local_pickup'],
                "free_shipping" => (bool) $row['shipping_free']
            ]
        ];

        // Mapeo de la descripción
        if (!empty($row['description'])) {
            $payload["description"] = [
                "plain_text" => $row['description']
            ];
        }

        // Mapeo de términos de venta
        if (!empty($row['warranty_type'])) {
            $payload["sale_terms"][] = [
                "id" => "WARRANTY_TYPE",
                "value_name" => $row['warranty_type']
            ];
        }
        if (!empty($row['warranty_time'])) {
            $payload["sale_terms"][] = [
                "id" => "WARRANTY_TIME",
                "value_name" => $row['warranty_time']
            ];
        }
        
        // Mapeo de las fotos (descomprime el JSON)
        if (!empty($row['pictures'])) {
            $pics = json_decode($row['pictures'], true);
            if (is_array($pics)) {
                // El JSON de la DB ya tiene el formato [{source: ...}, {source: ...}]
                $payload["pictures"] = $pics;
            }
        }
        
        // Mapeo de los atributos (descomprime el JSON)
        if (!empty($row['attributes'])) {
            $attrs = json_decode($row['attributes'], true);
            if (is_array($attrs)) {
                $payload["attributes"] = $attrs;
            }
        }

        // Mapeo del Product ID
        if (!empty($row['product_id'])) {
            $payload["variations"] = [
                [
                    "product_id" => $row['product_id']
                ]
            ];
        }

        return $payload;
    }
}