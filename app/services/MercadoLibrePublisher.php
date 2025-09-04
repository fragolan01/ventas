<?php

class MercadoLibrePublisher
{
 

// app/services/MercadoLibrePublisher.php
public static function sanitizeForPost(array $p): array
{
    // 1. Eliminar campos vacíos
    $p = array_filter($p, function($value) {
        return $value !== null && $value !== '';
    });

    // 2. Ajustar el modo de envío (shipping.mode)
    if (isset($p['shipping']['mode'])) {
        if ($p['shipping']['mode'] === '0' || $p['shipping']['mode'] === 0) {
            $p['shipping']['mode'] = 'me2';
        }
    } else {
        $p['shipping']['mode'] = 'me2';
    }
    
    // Convertir a booleanos
    $p['shipping']['free_shipping'] = !empty($p['shipping']['free_shipping']);
    $p['shipping']['local_pick_up'] = !empty($p['shipping']['local_pick_up']);
    
    return $p; // Devuelve el array de forma directa, sin el 'item'
}

    public static function postItem(array $itemPayload, $accessToken, $endpoint = 'https://api.mercadolibre.com/items')
    {
        // Sanitizar antes de mandar
        $itemPayload = self::sanitizeForPost($itemPayload);

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$accessToken}",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($itemPayload));
        curl_setopt($ch, CURLOPT_POST, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('Error cURL: ' . curl_error($ch));
        }

        curl_close($ch);
        return json_decode($response, true);
    }

}
