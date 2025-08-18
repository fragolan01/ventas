<?php

require_once '../app/models/TiendaModel.php';

class TiendasController
{
    public function index()
    {
        $tiendaModel = new TiendaModel();
        $tiendas = $tiendaModel->getTiendas();
        require_once '../app/views/tiendas/index.php';
    }

    public function crear()
    {
        require_once '../app/views/tiendas/crear.php';
    }

    public function store()
    {
        // 1. Verificar si los datos del formulario fueron enviados
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 2. Limpiar los datos para mayor seguridad
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $canal = filter_input(INPUT_POST, 'canal', FILTER_SANITIZE_STRING);

            // 3. Validar que los datos no estén vacíos
            if (!empty($nombre) && !empty($canal)) {
                $tiendaModel = new TiendaModel();
                
                // 4. Llamar al modelo para guardar los datos
                if ($tiendaModel->addTienda($nombre, $canal)) {
                    // 5. Redirigir a la página principal de tiendas si fue exitoso
                    header('Location: http://localhost/ventas/tiendas');
                    exit();
                } else {
                    echo "Error al guardar la tienda.";
                }
            } else {
                echo "Por favor, complete todos los campos.";
            }
        }
    }


    public function editar($id)
    {
        // 1. Instanciar el modelo de tiendas
        $tiendaModel = new TiendaModel();
        
        // 2. Obtener los datos de la tienda específica
        $tienda = $tiendaModel->getTiendaById($id);

        // 3. Verificar si la tienda existe
        if ($tienda) {
            // 4. Cargar la vista de edición con los datos de la tienda
            require_once '../app/views/tiendas/editar.php';
        } else {
            // En caso de que la tienda no exista, redirigir o mostrar un error
            echo "Tienda no encontrada.";
        }
    }

    public function update()
    {
        // Verificar que los datos del formulario fueron enviados
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Limpiar los datos del formulario para mayor seguridad
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $canal = filter_input(INPUT_POST, 'canal_id', FILTER_SANITIZE_STRING);

            // Validar que todos los campos necesarios estén presentes
            if (!empty($id) && !empty($nombre) && !empty($canal)) {
                $tiendaModel = new TiendaModel();
                
                // Llamar al modelo para actualizar los datos
                if ($tiendaModel->updateTienda($id, $nombre, $canal)) {
                    // Redirigir a la página principal de tiendas
                    header('Location: http://localhost/ventas/tiendas');
                    exit();
                } else {
                    echo "Error al actualizar la tienda.";
                }
            } else {
                echo "Por favor, complete todos los campos.";
            }
        }
    }



    public function eliminar($id)
    {
        // 1. Validar que el ID sea un número
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if ($id) {
            // 2. Instanciar el modelo
            $tiendaModel = new TiendaModel();

            // 3. Llamar al método de eliminación del modelo
            if ($tiendaModel->eliminarTienda($id)) {
                // 4. Redirigir a la página principal si fue exitoso
                header('Location: http://localhost/ventas/tiendas');
                exit();
            } else {
                echo "Error al eliminar la tienda.";
            }
        } else {
            echo "ID de tienda no válido.";
        }
    }



}