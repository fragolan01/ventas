<?php

class MLProductBuilder {

    public static function buildPayload(array $row): array
    {
        $payload = [
            "title" => $row['title'],
            "category_id" => $row['category_id'],
            "price" => (float) $row['price'],
            "currency_id" => $row['currency_id'],
            "available_quantity" => (int) $row['available_quantity'],
            "buying_mode" => $row['buying_mode'],
            "condition" => $row['conditions'],
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

        // Descripción
        if (!empty($row['description'])) {
            $payload["description"] = [
                "plain_text" => $row['description']
            ];
        }

        // Warranty type
        if (!empty($row['warranty_type'])) {
            $payload["sale_terms"][] = [
                "id" => "WARRANTY_TYPE",
                "value_id" => $row['warranty_type']
            ];
        }

        // Warranty time con unidades
        if (!empty($row['warranty_time'])) {
            $warrantyTime = trim($row['warranty_time']);

            // Si es solo número, le agregamos " meses"
            if (is_numeric($warrantyTime)) {
                $warrantyTime .= " meses";
            } else {
                // Normalizamos: ejemplo "12" -> "12 meses", "30 dias" -> "30 días"
                $warrantyTime = str_replace(
                    ["dias", "dia", "años", "año"],
                    ["días", "día", "años", "año"],
                    strtolower($warrantyTime)
                );
            }

            $payload["sale_terms"][] = [
                "id" => "WARRANTY_TIME",
                "value_name" => $warrantyTime
            ];
        }

        // Fotos
        if (!empty($row['pictures'])) {
            $pics = json_decode($row['pictures'], true);
            if (is_array($pics)) {
                $payload["pictures"] = $pics;
            }
        }

        // Atributos
        if (!empty($row['attributes'])) {
            $attrs = json_decode($row['attributes'], true);
            if (is_array($attrs)) {
                // Validar que cada atributo tenga "id" y "value_name"
                $validAttrs = array_filter($attrs, function($a) {
                    return isset($a['id']) && isset($a['value_name']);
                });
                $payload["attributes"] = array_values($validAttrs);
            }
        }

        return $payload;
    }
}
