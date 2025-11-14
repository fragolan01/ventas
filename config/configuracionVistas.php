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
            'view' => 'ingresoProductos/formulario_manual.php',
            'layout' => 'ingresoProductos/_layoutProductosSys.php'
        ],
        'syscom' => [
            'view' => 'ingresoProductos/importar_syscom.php',
            'viewProduct' => 'ingresoProductos/lista_productos.php',
            'layout' => 'ingresoProductos/_layoutProductosSys.php'
        ],
        'items' => [
            'view' => 'items/importar_items.php',
            'viewItems' => 'items/lista_items.php',
            'viewEnvios' => 'items/detalles_envios.php',
            'viewResultadosEnvios' => 'Items/lista_costo_envios.php',

            'layout' => 'items/_layoutItemsMeli.php',

         

        ]

    ]
];