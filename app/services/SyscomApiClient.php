<?php

class SyscomApiClient
{
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Obtiene los datos de un producto de la API de Syscom sin usar cURL.
     * @param string $idSyscom
     * @return array|null
     */
    public function getProductoData($idSyscom)
    {
        $url = "https://developers.syscom.mx/api/v1/productos/$idSyscom";

        $headers = [
            "Authorization: Bearer " . $this->token,
            "Accept: application/json"
        ];

        // Creamos el contexto HTTP
        $context = stream_context_create([
            "http" => [
                "method" => "GET",
                "header" => implode("\r\n", $headers),
                "ignore_errors" => true // Para poder leer incluso errores HTTP
            ]
        ]);

        // Ejecutar la solicitud
        $response = @file_get_contents($url, false, $context);

        // Obtener el código de respuesta HTTP
        $http_code = 0;
        if (isset($http_response_header[0])) {
            preg_match('/\s(\d{3})\s/', $http_response_header[0], $matches);
            $http_code = isset($matches[1]) ? (int)$matches[1] : 0;
        }

        if ($response === false) {
            error_log("Error: no se pudo conectar con la API de Syscom para ID: $idSyscom");
            return null;
        }

        $data = json_decode($response, true);

        if ($http_code != 200 || !$data || !isset($data['producto_id'])) {
            error_log("Error al consultar la API de Syscom para ID: $idSyscom. HTTP: $http_code. Respuesta: " . $response);
            return null;
        }

        return $data;
    }
}
