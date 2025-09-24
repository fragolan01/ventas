<?php
/**
 * Archivo de configuración Vistas para la aplicación modularizada.
 * Definimos valores por defecto y datos para cada uno de nuestros módulos.
 */
// const VIEW_PATH = 'app/views/';
const LAYOUT_PATH = VIEW_PATH . 'layouts/';
const SHARED_PATH = VIEW_PATH . 'shared/';

// define array de configuración para todo.
$conf = [
    'default' => [
        'modulo' => 'home',
        'layout' => 'shared/_layout.php'
    ],
    'modules' => [
        'home' => [
            'view' => 'home/index.php' //  home en su propia carpeta
        ],
        'ingresoProductos' => [
            'view' => 'ingresoProductos/index.php',
            'layout' => 'ingresoProductos/_layoutProductosSys.php'
        ],
        'syscom' => [
            'view' => 'ingresoProductos/importar_syscom.php',
            'layout' => 'ingresoProductos/_layoutProductosSys.php'
        ]
    ]
];