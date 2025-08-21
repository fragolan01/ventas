<?php

require_once '../app/models/ModeloModel.php';

class ModelosController
{
    public function index()
    {
        $modeloModel = new ModeloModel();
        $modelos = $modeloModel->getmodelos();
        require_once '../app/views/modelos/index.php';
    }

    public function crear()
    {
        require_once '../app/views/modelos/crear.php';
    }

    public function store()
    {
        // 1. Verificar si los datos del formulario fueron enviados
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 2. Limpiar los datos para mayor seguridad
            $modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_STRING);

            // 3. Validar que los datos no estén vacíos
            if (!empty($modelo) ) { 
                $modeloModel = new ModeloModel();
                
                // 4. Llamar al modelo para guardar los datos. Se corrigió la llamada para que coincida con el modelo.
                if ($modeloModel->addModelo($modelo)) {
                    // 5. Redirigir a la página principal de Modelos si fue exitoso
                    header('Location: /Modelos');
                    exit();
                } else {
                    echo "Error al guardar la modelo.";
                }
            } else {
                echo "Por favor, complete todos los campos del modelo";
            }
        }
    }


    public function editar($id)
    {
        $modeloModel = new ModeloModel();
        $modelo = $modeloModel->getModeloById($id);

        if ($modelo) {
            require_once '../app/views/modelos/editar.php';
        } else {
            echo "modelo no encontrada.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_STRING);

            if (!empty($id) && !empty($modelo)) {
                $modeloModel = new ModeloModel();
                
                // Se corrigió la llamada para que coincida con el modelo
                if ($modeloModel->updatemodelo($id, $modelo)) {
                    // Se corrigió la sintaxis de la redirección
                    header('Location: /modelos');
                    exit();
                } else {
                    echo "Error al actualizar la modelo.";
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
            $modeloModel = new ModeloModel();

            if ($modeloModel->eliminarModelo($id)) {
                header('Location: /modelos');
                exit();
            } else {
                echo "Error al eliminar la modelo.";
            }
        } else {
            echo "ID de modelo no válido.";
        }
    }
}