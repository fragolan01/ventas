<?php

// 1. Obtener la URL directamente de la petición
// La variable $_SERVER['REQUEST_URI'] captura la URL completa de la petición
$url = $_SERVER['REQUEST_URI'];

// 2. Limpiar la URL y eliminar el prefijo del proyecto
// En este caso, el prefijo es la carpeta 'ventas' del proyecto
$projectPath = '/ventas/'; 
if (strpos($url, $projectPath) === 0) {
    $url = substr($url, strlen($projectPath));
}
$url = explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));

// 3. Definir controlador, método y parámetros
// Si la URL está vacía, por defecto se usa el controlador Home y el método index
$controller = !empty($url[0]) ? ucwords($url[0]) . 'Controller' : 'HomeController';
$method = !empty($url[1]) ? $url[1] : 'index';
$params = !empty($url[2]) ? array_slice($url, 2) : [];

// 4. Crear la ruta completa al controlador
$controllerFile = '../app/controllers/' . $controller . '.php';

// 5. Verificar si el archivo del controlador existe
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controller();

    // 6. Verificar si el método existe en el controlador
    if (method_exists($controller, $method)) {
        call_user_func_array([$controller, $method], $params);
    } else {
        echo "Método no encontrado: " . $method;
    }
} else {
    echo "Controlador no encontrado: " . $controller;
}