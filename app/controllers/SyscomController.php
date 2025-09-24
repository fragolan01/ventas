<?php
// app/controllers/SyscomController.php

session_start();

require_once '../app/models/SyscomModel.php';
require_once '../app/services/SyscomApiClient.php';
require_once '../app/services/ImportadorFactory.php';

// Carga la configuración de vistas una sola vez.
// Es mejor hacerlo en el router para que esté disponible globalmente.
// Si no puedes, úsalo con global.
require_once __DIR__ . '/../../config/configuracionVistas.php';

class SyscomController {
    public function importarProductos() {
        global $conf; // Accede al array de configuración global
        
        $resultados = [];
        $proveedorId = null;

        if (isset($_SESSION['proveedor_id'])) {
            $proveedorId = $_SESSION['proveedor_id'];
        }

        // Lógica para manejar las peticiones POST y obtener resultados
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['proveedor_id'])) {
                $_SESSION['proveedor_id'] = $_POST['proveedor_id'];
                $proveedorId = $_SESSION['proveedor_id'];
            }
            if (isset($_POST['producto_id']) && $proveedorId !== null) {
                $importador = ImportadorFactory::getImportador($proveedorId);
                $data = ['proveedor_id' => $proveedorId, 'producto_id_input' => $_POST['producto_id']];
                $resultados = $importador->importarProductos($data);
            }
        }
        
        // Ahora, carga la vista y el layout al final, una sola vez.
        // Decide qué vista y layout usar basándote en el proveedorId.
        if ($proveedorId == 3) {
            global $conf;

            // $view = $conf['modules']['syscom']['view'];
            $view = VIEW_PATH . $conf['modules']['syscom']['view'];
            $layout = $conf['modules']['syscom']['layout'];
        } else {
            // Usa el layout del formulario manual si no es Syscom
            $view = $conf['modules']['ingresoProductos']['view'];
            $layout = $conf['modules']['ingresoProductos']['layout'];
        }
        // require_once $layout;
        
        // El `require_once` final y único que carga todo.
        // la variable $view y $resultados existan para la vista.
        require_once __DIR__ . '/../../app/views/' . $layout;
    }
}