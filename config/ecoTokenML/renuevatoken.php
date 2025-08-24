<?php
// URL de renovación de token de Mercado Libre
$url = "https://api.mercadolibre.com/oauth/token";

// Credenciales de la aplicación
$client_id = '7626391564892909';
$client_secret = '95FMvTcbv0d8y515xHHrtAGkxpglFYye';

// Ruta al archivo donde se guardan los tokens
$token_file = 'tokens.json';

// Función que renueva el token usando el refresh_token
function renew_token($last_refresh_token, $url, $client_id, $client_secret, $token_file) {
    $data = array(
        "grant_type" => "refresh_token",
        "client_id" => $client_id,
        "client_secret" => $client_secret,
        "refresh_token" => $last_refresh_token
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Content-Type: application/x-www-form-urlencoded'
    ));

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($response === false) {
        echo "cURL Error: " . curl_error($ch) . "\n";
        curl_close($ch);
        return array(null, null);
    }

    curl_close($ch);

    if ($http_code == 200) {
        $token_info = json_decode($response, true);
        if (isset($token_info['access_token']) && isset($token_info['refresh_token'])) {
            // Guarda los nuevos tokens
            file_put_contents($token_file, json_encode($token_info, JSON_PRETTY_PRINT));
            return array($token_info['access_token'], $token_info['refresh_token']);
        } else {
            echo "Error: No se encontraron tokens en la respuesta.\n";
            return array(null, null);
        }
    } else {
        echo "Error HTTP " . $http_code . ": " . $response . "\n";
        return array(null, null);
    }
}

// Función que lee los tokens desde el archivo o los renueva si no existen
function get_tokens($token_file, $url, $client_id, $client_secret) {
    if (file_exists($token_file)) {
        $token_info = json_decode(file_get_contents($token_file), true);
        if (isset($token_info['access_token']) && isset($token_info['refresh_token'])) {
            return array($token_info['access_token'], $token_info['refresh_token']);
        }
    }

    // Si no existen tokens, intenta renovarlos con el refresh_token inicial (reemplaza con el tuyo)
    return renew_token("TG-6855e5a41d732f00014e48f3-2424408169", $url, $client_id, $client_secret, $token_file);
}

// Obtener tokens actuales
$tokens = get_tokens($token_file, $url, $client_id, $client_secret);
$access_token = isset($tokens[0]) ? $tokens[0] : null;
$refresh_token = isset($tokens[1]) ? $tokens[1] : null;

// Renovar el token para estar siempre actualizado
if ($access_token && $refresh_token) {
    $tokens = renew_token($refresh_token, $url, $client_id, $client_secret, $token_file);
    $access_token = isset($tokens[0]) ? $tokens[0] : null;
    $refresh_token = isset($tokens[1]) ? $tokens[1] : null;
}

// Mostrar tokens
echo "Access Token: " . ($access_token !== null ? $access_token : "N/A") . "\n";
echo "Refresh Token: " . ($refresh_token !== null ? $refresh_token : "N/A") . "\n";
?>
