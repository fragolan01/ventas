<?php

require_once '../app/models/ProveedorModel.php';

class ProveedoresController
{
    public function index()
    {
        $proveedorModel = new ProveedorModel();
        $proveedores = $proveedorModel->getProveedores();
        require_once '../app/views/proveedores/index.php';
    }

    public function crear()
    {
        require_once '../app/views/proveedores/crear.php';
    }

    public function store()
    {
        // 1. Verificar si los datos del formulario fueron enviados
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 2. Limpiar los datos para mayor seguridad
            $nombre_proveedor = filter_input(INPUT_POST, 'nombre_proveedor', FILTER_SANITIZE_STRING);

            // 3. Validar que los datos no estén vacíos
            if (!empty($nombre_proveedor) ) { 
                $proveedorModel = new ProveedorModel();
                
                // 4. Llamar al modelo para guardar los datos. Se corrigió la llamada para que coincida con el modelo.
                if ($proveedorModel->addProveedor($nombre_proveedor)) {
                    // 5. Redirigir a la página principal de Proveedores si fue exitoso
                    header('Location: /proveedores');
                    exit();
                } else {
                    echo "Error al guardar la Proveedor.";
                }
            } else {
                echo "Por favor, complete todos los campos.";
            }
        }
    }


    public function editar($id)
    {
        $proveedorModel = new ProveedorModel();
        $proveedor = $proveedorModel->getProveedorById($id);

        if ($proveedor) {
            require_once '../app/views/proveedores/editar.php';
        } else {
            echo "Proveedor no encontrada.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $nombre_proveedor = filter_input(INPUT_POST, 'nombre_proveedor', FILTER_SANITIZE_STRING);

            if (!empty($id) && !empty($nombre_proveedor)) {
                $proveedorModel = new ProveedorModel();
                
                // Se corrigió la llamada para que coincida con el modelo
                if ($proveedorModel->updateProveedor($id, $nombre_proveedor)) {
                    // Se corrigió la sintaxis de la redirección
                    header('Location: /proveedores');
                    exit();
                } else {
                    echo "Error al actualizar la Proveedor.";
                }
            } else {
                echo "Por favor, complete todos los campos.";
            }
        }
    }


    public function eliminar($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if ($id) {
            $proveedorModel = new ProveedorModel();

            if ($proveedorModel->eliminarProveedor($id)) { 
                header('Location: /proveedores');
                exit();
            } else {
                echo "Error al eliminar la Proveedor.";
            }
        } else {
            echo "ID de Proveedor no válido.";
        }
    }
}