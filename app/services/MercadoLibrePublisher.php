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

        // Converte el array a JSON para el cuerpo de la solicitud
        $data = json_encode($itemPayload);
        
        // Temporal para depurar **************************** DEPURAR ************************************ DEPURAR
        file_put_contents(__DIR__ . '/payload_debug.txt', $data);


        // contexto de flujo para la solicitud HTTP
        $options = [
            'http' => [
                'header'  => "Authorization: Bearer {$accessToken}\r\nContent-Type: application/json",
                'method'  => 'POST',
                'content' => $data,

                // Ingonar los errores y no pare la publicacion
                'ignore_errors' => true,
            ],
        ];

        $context  = stream_context_create($options);

        // error_log(print_r($itemPayload, true));
        // error_log(json_encode($itemPayload));

        // envio de la solicitudd
        $response = file_get_contents($endpoint, false, $context);

        // $ch = curl_init($endpoint);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //     "Authorization: Bearer {$accessToken}",
        //     "Content-Type: application/json"
        // ]);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($itemPayload));
        // curl_setopt($ch, CURLOPT_POST, true);

        // Errores
        if ($response === false) {
            // Se puede usar error_get_last() para obtener detalles del error
            $error_details = error_get_last();
            throw new \Exception('Error al realizar la solicitud a la API de Mercado Libre: ' . $error_details['message']);
        }

        // $response = curl_exec($ch);

        // if (curl_errno($ch)) {
        //     throw new \Exception('Error cURL: ' . curl_error($ch));
        // }

        // curl_close($ch);
        return json_decode($response, true);
    }

}
