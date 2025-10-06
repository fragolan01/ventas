<?php
// app/controllers/SyscomController.php

session_start();

require_once '../app/models/SyscomModel.php';
require_once '../app/services/SyscomApiClient.php';
require_once '../app/services/ImportadorFactory.php';

// Esta linea debe estar en router principal
require_once __DIR__ . '/../../config/configuracionVistas.php';

class SyscomController {
    public function importarProductos() {
        
        //configuración global
        global $conf; 
        
        // ruta de vistas si está en el router
        global $VIEW_PATH;         
        
        $resultados = [];
        $proveedorId = null;

        // si el proveedor ya esta en sesion
        if (isset($_SESSION['proveedor_id'])) {
            $proveedorId = $_SESSION['proveedor_id'];
        }

        // Lógica de peticiones POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['proveedor_id'])) {
                $_SESSION['proveedor_id'] = $_POST['proveedor_id'];
                $proveedorId = $_SESSION['proveedor_id'];
            }
            if (isset($_POST['producto_id']) && $proveedorId !== null) {
                // importación 
                $importador = ImportadorFactory::getImportador($proveedorId);
                $data = ['proveedor_id' => $proveedorId, 'producto_id_input' => $_POST['producto_id']];
                $resultados = $importador->importarProductos($data);
            }
        }
        
        // ---  Vistas Centralizada ---
        // desicon de layout a usar en  base al proveedorId, una sola vez.
        if ($proveedorId == 3) {
            $view = VIEW_PATH . $conf['modules']['syscom']['view'];
            $layout = VIEW_PATH . $conf['modules']['syscom']['layout'];
        } else {
            // Para cualquier otro proveedor, carga el formulario manual.
            $view = VIEW_PATH . $conf['modules']['ingresoProductos']['view'];
            $layout = VIEW_PATH . $conf['modules']['ingresoProductos']['layout'];
        }

        // El require_once final y único que carga todo.
        // Las variables $view y $resultados ahora están disponibles para la vista.
        require_once $layout;
    }


        public function listaProductos() {

        //configuración global
        global $conf; 

        // ruta de vistas si está en el router
        global $VIEW_PATH;  

        $syscomModel = new SyscomModel();
        $productos = $syscomModel->obtenerTodosLosProductos();
        
        // // 2. Definir vista y layout (Configuración)
        $view = VIEW_PATH . $conf['modules']['syscom']['viewProduct'];
        $layout = VIEW_PATH . $conf['modules']['ingresoProductos']['layout'];
        
        // 3. Cargar el layout
        require_once $layout;

        
    }

}