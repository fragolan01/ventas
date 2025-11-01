<?php

class MeliApiClient
{
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Obtener los datos de Item Meli usando stream_context_create.
     * @param string $itemID
     * @return array|null
     */
    public function consultarApiMeli($itemId) {
        $url = "https://api.mercadolibre.com/items/" . $itemId;

        $headers = [
            "Authorization: Bearer " . $this->token,
            "Accept: application/json"
        ];

        // 1. Crear el contexto HTTP para la solicitud GET
        $context = stream_context_create([
            "http" => [
                "method" => "GET",
                // Implode une el array de encabezados con el separador necesario
                "header" => implode("\r\n", $headers), 
                "ignore_errors" => true // Crucial para poder leer la respuesta incluso si es 4xx o 5xx
            ]
        ]);

        // 2. Ejecutar la solicitud
        $response = @file_get_contents($url, false, $context);

        // 3. Manejo de errores de conexión
        if ($response === false) {
            error_log("Error: no se pudo conectar con la API de Mercado Libre para item: $itemId");
            return null;
        }

        // 4. Obtener el código de respuesta HTTP desde los encabezados de respuesta
        $http_code = 0;
        if (isset($http_response_header[0])) {
            preg_match('/\s(\d{3})\s/', $http_response_header[0], $matches);
            $http_code = isset($matches[1]) ? (int)$matches[1] : 0;
        }

        $data = json_decode($response, true);

        // 5. Manejo de errores de la API (códigos no 200)
        if ($http_code != 200) {
            // Si el código no es 200 (ej. 404), registramos el error y devolvemos la información del error.
            $errorMessage = isset($data['message']) ? $data['message'] : 'Error desconocido de la API.';
            error_log("Error al consultar la API de ML para item $itemId. HTTP: $http_code. Mensaje: $errorMessage");
            
            // Devolvemos la estructura de error para que el Importador la procese
            return ['error' => true, 'http_code' => $http_code, 'message' => $errorMessage];
        }

        // 6. Validación de la respuesta exitosa
        if (!$data || !isset($data['id'])) {
            error_log("Respuesta de ML inválida o incompleta para item $itemId.");
            return null;
        }

        return $data;
    }
}