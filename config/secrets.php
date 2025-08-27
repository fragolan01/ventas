<?php

// Ruta token Test
$rutaTokenTest = __DIR__ . '/testTokenML/tokens.js';
// Ruta token prod
$rutaTokenProd = __DIR__ . '/prodTokenML/tokens.js';


// Leer tokens test y prod
$tokenTestContenido = file_get_contents($rutaTokenTest);
$tokenProdContenido = file_get_contents($rutaTokenProd);

// Decodificar JSON array asocitivo test y prod
$testToken = json_decode($tokenTestContenido, true);
$prodtToken = json_decode($tokenProdContenido, true);

// Obtener valor de access tokens test y prod
$accessTokenTest = $testToken['access_token'];
$accessTokenProd = $prodtToken['access_token'];


// Este archivo guarda las key seecret de cada aplicacion
return [
    'amazon' => [
        'api_key' => 'TU_API_KEY_DE_AMAZON_AQUI',
        'secret_key' => 'TU_CLAVE_SECRETA_DE_AMAZON_AQUI'
    ],
    'prod_mercado_libre' => [
        'client_id' => '7626391564892909',
        'prodtToken' => $accessTokenProd
    ],
    'test_mercado_libre' => [
        'client_id' => '7626391564892909',
        'testToken' => $accessTokenTest
    ],

    'syscom' => [
        'api_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImRiMDQwNzI0NjA2NTVmNzczODYwNmZjNDExNjcwM2IxYTc1MmMyNDc3YTg4MzdjYTBmNWZkYjFmODlkZmVkYWNjMmMyM2Q3YzRmZmI5MTZmIn0.eyJhdWQiOiJ5ZmQwS1g4U1REYUtPZEJ0cHB2UG4wSWVFeUdiVW1CVCIsImp0aSI6ImRiMDQwNzI0NjA2NTVmNzczODYwNmZjNDExNjcwM2IxYTc1MmMyNDc3YTg4MzdjYTBmNWZkYjFmODlkZmVkYWNjMmMyM2Q3YzRmZmI5MTZmIiwiaWF0IjoxNzM4MTY3NzY3LCJuYmYiOjE3MzgxNjc3NjcsImV4cCI6MTc2OTcwMzc2Nywic3ViIjoiIiwic2NvcGVzIjpbXX0.SZIcwjCM95rniRFIJFXtVkzatb03aFraekt61Uk-WYzgQ36v1XBZt2nHb18TtPEzCJL9Qi2TzMnzAo7cOhl9RVp2audfKz-zYNVHqgN4WfCJ9XXNqrNTT_-cgXfFvY6ZEl-8HE3ixnUZWHwGK6W4anCGg9yGU2pQ09-_ZpmdbDUFO-2ZIW1tQxHXua5JjEwcPWKDQHkt_tOYZ2vk1Mb36qPXgO_5RBk8nfHSJ2IqAfvtc9MRCPdMXTyDLjual_FNDs4UIwqlNlhKU9WwguD2dve78adw41g9F6tzWYAx8XgmMzwNOOXJsvMEWNTbAS_6WifkyC5fBBkKj6q6DSgwOp0ML0FEuce34YwPHUKP6BeE7s6BnpxKRd--24NQvGReA885dI-QA0O4eKMMyIKmSgLzysSYmj6-MGzZ17sHkhv0fo51YaDguE42YDQ-SVX0T_U4KTZvrGKKvzb2iawgEHlo5l8dcrq8fO5NchksVHRFJPFmjwR4QP_Dt-IcOga_Hn4qfZkZVUDZ7yomL91Y_qXDyq8XGXdHOxPyN0RyF6m0WW_Xwu4wvSpxclhovj65pQG9k619_xsvvNj-LEXJ7J_3U-0yq5c46iAAs1p1GF_YYSCJ7KdOJ9nwkPz8CKHwriGG5nJJD5zNtyNjN3ffukg9Imhtt99VbemoR0qSaM8'
    ],
    // Agregar mas 
];