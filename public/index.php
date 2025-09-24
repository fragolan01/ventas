<?php

// ruta configuracion vistas
define('CONF_VISTAS_PATH' , __DIR__ . '../../config/configuracionVistas.php');

// define('CONF_VISTAS_PATH' , __DIR__ . '/../app/views');

// Define las vistas
define('VIEW_PATH', __DIR__ . '/../app/views/');

// Aincluir archivo.
if (file_exists(CONF_VISTAS_PATH)) {
    require_once CONF_VISTAS_PATH;
} else {
    // Handle the error if the file is not found.
    die('Error: The configuration file was not found.');
}


// 1. Obtener la URL directamente de la petición
$url = $_SERVER['REQUEST_URI'];

// 2. Definir la ruta base del proyecto según el entorno
$basePath = '';
// En un entorno de desarrollo, la URL incluye el nombre de la carpeta
if (strpos($url, '/ventas/') === 0) {
    $basePath = '/ventas/';
}

// 3. Limpiar la URL para que solo contenga el controlador/metodo/parametros
$url = substr($url, strlen($basePath));
// Corrección: limpiar la barra inicial antes de procesar
$url = explode('/', filter_var(trim($url, '/'), FILTER_SANITIZE_URL));

// 4. Definir controlador, método y parámetros
$controller = !empty($url[0]) ? ucwords($url[0]) . 'Controller' : 'HomeController';
$method = !empty($url[1]) ? $url[1] : 'index';
$params = !empty($url[2]) ? array_slice($url, 2) : [];

// 5. Crear la ruta completa al controlador
$controllerFile = '../app/controllers/' . $controller . '.php';

// 6. Verificar si el archivo del controlador existe
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controller();

    // 7. Verificar si el método existe en el controlador
    if (method_exists($controller, $method)) {
        call_user_func_array([$controller, $method], $params);
    } else {
        echo "Método no encontrado: " . $method;
    }
} else {
    echo "Controlador no encontrado: " . $controller;
}