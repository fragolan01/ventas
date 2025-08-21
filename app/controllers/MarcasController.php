<?php

require_once '../app/models/MarcaModel.php';

class MarcasController
{
    public function index()
    {
        $marcaModel = new MarcaModel();
        $marcas = $marcaModel->getMarcas();
        require_once '../app/views/marcas/index.php';
    }

    public function crear()
    {
        require_once '../app/views/marcas/crear.php';
    }

    public function store()
    {
        // 1. Verificar si los datos del formulario fueron enviados
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 2. Limpiar los datos para mayor seguridad
            $proveedor_id = filter_input(INPUT_POST, 'proveedor_id', FILTER_SANITIZE_STRING);
            $nombre_marca = filter_input(INPUT_POST, 'nombre_marca', FILTER_SANITIZE_STRING);

            // 3. Validar que los datos no estén vacíos
            if (!empty($proveedor_id) && !empty($nombre_marca) ) {
                $marcaModel = new MarcaModel();
                
                // 4. Llamar al modelo para guardar los datos. Se corrigió la llamada para que coincida con el modelo.
                if ($marcaModel->addMarca($proveedor_id, $nombre_marca)) {
                    // 5. Redirigir a la página principal de las Marcas si fue exitoso
                    header('Location: /Marcas');
                    exit();
                } else {
                    echo "Error al guardar la marca.";
                }
            } else {
                echo "Por favor, complete todos los campos.";
            }
        }
    }


    public function editar($id)
    {
        $marcaModel = new MarcaModel();
        $marca = $marcaModel->getMarcaById($id);

        if ($marca) {
            require_once '../app/views/marcas/editar.php';
        } else {
            echo "Marca no encontrada.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $proveedor_id = filter_input(INPUT_POST, 'proveedor_id', FILTER_SANITIZE_STRING);
            $nombre_marca = filter_input(INPUT_POST, 'nombre_marca', FILTER_SANITIZE_STRING);

            if (!empty($id) && !empty($proveedor_id) && !empty($nombre_marca)) {
                $marcaModel = new MarcaModel();
                
                // Se corrigió la llamada para que coincida con el modelo
                if ($marcaModel->updateMarca($id, $proveedor_id, $nombre_marca)) {
                    // Se corrigió la sintaxis de la redirección
                    header('Location: /marcas');
                    exit();
                } else {
                    echo "Error al actualizar la marca.";
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
            $marcaModel = new MarcaModel();

            if ($marcaModel->eliminarMarca($id)) {
                header('Location: /marcas');
                exit();
            } else {
                echo "Error al eliminar la marca.";
            }
        } else {
            echo "ID de la marca no válido.";
        }
    }
}