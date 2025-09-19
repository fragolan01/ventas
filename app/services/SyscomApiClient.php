<?php


class SyscomApiClient
{
    // Propiedad para almacenar el token
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Obtiene los datos de un producto de la API de Syscom.
     * @param string $idSyscom El ID del producto a buscar.
     * @return array|null Los datos del producto, o null si no se encuentra o hay un error.
     */
    public function getProductoData($idSyscom)
    {
        $url = "https://developers.syscom.mx/api/v1/productos/$idSyscom";
        $headers = [
            "Authorization: Bearer " . $this->token,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $response = curl_exec($ch);
        // ¡CORRECCIÓN CRÍTICA! Obtener el código HTTP
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $data = json_decode($response, true);
        
        // Verificar la llamada
        if ($http_code != 200 || !$data || !isset($data['producto_id'])) {
            // Registro de error
            error_log("Error al consultar la API de Syscom para ID: $idSyscom. HTTP Code: $http_code. Response: " . $response);
            return null;
        }

        return $data;
    }
}