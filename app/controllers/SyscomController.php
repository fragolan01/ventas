<?php
// app/controllers/SyscomController.php

session_start();

require_once '../app/models/SyscomModel.php';
require_once '../app/services/SyscomApiClient.php';
require_once '../app/services/ImportadorFactory.php';

class SyscomController {
    // ... (El constructor y las propiedades siguen igual)

    public function importarProductos() {
        $resultados = [];
        $proveedorId = null;
        
        // Verifica si el proveedor_id ya está en la sesión
        if (isset($_SESSION['proveedor_id'])) {
            $proveedorId = $_SESSION['proveedor_id'];
        }

        // Manejar la solicitud POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // PRIMERA PETICIÓN: Si el formulario selecciona un proveedor
            if (isset($_POST['proveedor_id'])) {
                $_SESSION['proveedor_id'] = $_POST['proveedor_id'];
                $proveedorId = $_SESSION['proveedor_id'];

                // Decide qué vista cargar basada en el proveedor
                if ($proveedorId == 3) { // Suponiendo que Syscom es el ID 1
                    include __DIR__ . '/../views/ingresoProductos/importar_syscom.php';
                } else {
                    // Cargar la vista del formulario manual para otros proveedores
                    include __DIR__ . '/../views/ingresoProductos/formulario_manual.php';
                }
                return;
            }

            // SEGUNDA PETICIÓN: Si el formulario viene con datos de producto
            if (isset($_POST['producto_id']) && $proveedorId !== null) {
                // Lógica de importación usando el patrón Strategy
                $importador = ImportadorFactory::getImportador($proveedorId);
                $data = ['proveedor_id' => $proveedorId, 'producto_id_input' => $_POST['producto_id']];
                $resultados = $importador->importarProductos($data);
                
                // Cargar la vista correcta después de la importación
                if ($proveedorId == 3) {
                    include __DIR__ . '/../views/ingresoProductos/importar_syscom.php';
                } else {
                    include __DIR__ . '/../views/ingresoProductos/formulario_manual.php';
                }
                return;
            }
        }
        
        // Carga una vista por defecto si no es una petición POST
        // Por ejemplo, volver a la selección de proveedores
        include __DIR__ . '/../../views/ingresoProductos/index.php';
    }
}