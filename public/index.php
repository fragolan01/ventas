<?php

// 1. Obtener la URL
$url = isset($_GET['url']) ? $_GET['url'] : 'home/index';

// 2. Limpiar la URL y dividirla en un array
$url = explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));

// 3. Definir controlador, método y parámetros
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
        // Llamar al método con los parámetros
        call_user_func_array([$controller, $method], $params);
    } else {
        // Método no encontrado, cargar página de error 404
        echo "Método no encontrado.";
    }
} else {
    // Controlador no encontrado, cargar página de error 404
    echo "Controlador no encontrado.";
}