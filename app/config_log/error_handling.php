<?php

// Habilitar el reporte de todos los errores de PHP
error_reporting(E_ALL);

// No mostrar los errores en la pantalla. Esto es crucial por seguridad en producción.
ini_set('display_errors', '0');

// Habilitar el registro de errores
ini_set('log_errors', '1');

// Especificar la ruta completa del archivo de registro de errores
// Asegúrate de que la carpeta 'logs' tenga permisos de escritura (755 o 777)
ini_set('error_log', '../app/log_error/php_errors.log');

// Opcional: para que se pueda ver un error sencillo si display_errors está desactivado
// Es una buena práctica en desarrollo, pero en producción, es mejor solo loguearlos.
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // Aquí puedes agregar un mensaje personalizado para el usuario
    // Por ejemplo: echo "Ha ocurrido un error inesperado.";
    // O simplemente loguearlo y salir
    error_log("Error: [$errno] $errstr en $errfile:$errline", 0);
    return true;
});