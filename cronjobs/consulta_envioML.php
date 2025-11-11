<?php
require '../app/services/MeliApiClient.php'; // incluir la clase

class consultaEnvioML
{
    private $token;
    private $meliClient;

    public function __construct($token)
    {

        $secrets = require __DIR__ . '/../cronjobs/secrets.php'; 
        $token = $secrets['prod_mercado_libre']['prodtToken'];

        // $this->token = $token;
        $this->meliClient = new MeliApiClient($token);
    }

    public function costoEnvio ($userID, $params = [])
    {
        $userID = 2424408169;

        // Parametros
        $params = [
            // 'dimensions' => '10x10x10,500',   // largo x ancho x alto, peso
            'item_price' => 589,
            'listing_type_id' => 'gold_special',
            'mode' => 'me2',
            'condition' => 'new',
            'logistic_type' => 'drop_off',
            'verbose' => 'true',
            'item_id' => 'MLM4162699042'
        ];

        // $respuesta = $meliClient->getFreeShippingOptions($userID, $params);
        $respuesta = $this->meliClient->getFreeShippingOptions($userID, $params);


        // Ver el resultado
        echo '<pre>';
        print_r($respuesta);
        echo '</pre>';

    }


}


// --- Instancia y prueba ---
$token="";
$envio = new consultaEnvioML($token);

$params = [];
$userID ="";

$envio->costoEnvio($userID, $params);
