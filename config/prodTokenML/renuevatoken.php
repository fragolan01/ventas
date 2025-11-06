<?php
// Define URL
$url = "https://api.mercadolibre.com/oauth/token";

// Function to renew token
function renew_token($last_refresh_token) {
    global $url;
    $data = array(
        "grant_type" => "refresh_token",
        "client_id" => "7626391564892909",
        "client_secret" => "95FMvTcbv0d8y515xHHrtAGkxpglFYye",
        "refresh_token" => $last_refresh_token
    );

    // Send the POST request with JSON data
    $options = array(
        'http' => array(
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response !== FALSE) {
        $token_info = json_decode($response, true);
        $access_token = $token_info['access_token'];
        $refresh_token = $token_info['refresh_token'];

        // Save the new access_token and refresh_token to a file
        file_put_contents('tokens.json', json_encode($token_info));

        return array($access_token, $refresh_token);
    } else {
        echo "Failed to renew token";
        return array(null, null);
    }
}

// Function to read the tokens from file
function get_tokens() {
    if (file_exists('tokens.json')) {
        $token_info = json_decode(file_get_contents('tokens.json'), true);
        if (isset($token_info['access_token']) && isset($token_info['refresh_token'])) {
            return array($token_info['access_token'], $token_info['refresh_token']);
        }
    }
    return renew_token("TG-690b90a1d2ee9800014467fb-2424408169");  // Initial refresh token
}

// Get the access token and refresh token
list($access_token, $refresh_token) = get_tokens();

// If tokens are obtained successfully, ensure future renewals use the latest refresh token
if ($access_token && $refresh_token) {
    list($access_token, $refresh_token) = renew_token($refresh_token);
}

// Print the access token and refresh token
echo "Access Token: " . $access_token . "\n";
echo "Refresh Token: " . $refresh_token . "\n";
?>
