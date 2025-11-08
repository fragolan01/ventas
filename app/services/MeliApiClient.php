<?php

class MeliApiClient
{
    private $token;
    
    private $url_bsse = 'https://api.mercadolibre.com/';

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function request($method, $endpoint, $body = null)
    {
        $headers = [
            "Authorization: Bearer " . $this->token,
            "Accept: application/json",
            "Content-Type: application/json"
        ];

        // asignar array
        $contextOptions = [
            "http" => [
                "method" => strtoupper($method),
                "header" => implode("\r\n", $headers),
                "ignore_errors" => true
            ]
        ];

        if ($body !== null) {
            $contextOptions["http"]["content"] = json_encode($body);
        }

        //typo: $this->url_bsse → $this->url_base
        $response = @file_get_contents($this->url_bsse . $endpoint, false, stream_context_create($contextOptions));

        if ($response === false) {
            error_log("Error: no se pudo conectar con la API de Mercado Libre para item: $endpoint");
            return ['error' => true, 'message' => 'Error de conexión con la API.'];
        }

        $http_code = 0;
        if (isset($http_response_header[0])) {
            preg_match('/\s(\d{3})\s/', $http_response_header[0], $matches);
            $http_code = isset($matches[1]) ? (int)$matches[1] : 0;
        }

        $data = json_decode($response, true);

        if ($http_code < 200 || $http_code >= 300) {
            $msg = $data['message'] ?? 'Error desconocido';
            error_log("Error API ML [$http_code] en $endpoint: $msg");
            return ['error' => true, 'http_code' => $http_code, 'message' => $msg];
        }

        return $data;
    }

    /**
     * GET - Consultar un item
     */
    public function getItem($itemId)
    {
        return $this->request('GET', "items/{$itemId}");

 
    }

    /**
     * PUT - Actualizar un item
     */
    public function updateItem($itemId, array $data)
    {
        return $this->request('PUT', "items/{$itemId}", $data);
    }

    /**
     * GET - Consultar usuario
     */
    public function getUser($userId)
    {
        return $this->request('GET', "users/{$userId}");
    }


}