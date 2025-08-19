<?php

require_once '../app/models/CanalModel.php';

class CanalesController
{
    public function index()
    {
        $canalModel = new CanalModel();
        $canales = $canalModel->getCanales();
        require_once '../app/views/canales/index.php';
    }

    public function crear()
    {
        require_once '../app/views/canales/crear.php';
    }

    public function store()
    {
        // 1. Verificar si los datos del formulario fueron enviados
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 2. Limpiar los datos para mayor seguridad
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
            $logo_url = filter_input(INPUT_POST, 'logo_url', FILTER_SANITIZE_STRING);
            $api_base_url = filter_input(INPUT_POST, 'api_base_url', FILTER_SANITIZE_STRING);
            $activo = filter_input(INPUT_POST, 'activo', FILTER_SANITIZE_NUMBER_INT); // Se cambió a NUMBER_INT para 'activo'

            // 3. Validar que los datos no estén vacíos
            if (!empty($nombre) && !empty($descripcion) && !empty($logo_url) && !empty($api_base_url) && isset($activo) ) { // Se cambió a isset para 'activo'
                $canalModel = new CanalModel();
                
                // 4. Llamar al modelo para guardar los datos. Se corrigió la llamada para que coincida con el modelo.
                if ($canalModel->addCanal($nombre, $descripcion, $logo_url, $api_base_url, $activo)) {
                    // 5. Redirigir a la página principal de Canales si fue exitoso
                    header('Location: /Canales');
                    exit();
                } else {
                    echo "Error al guardar la canal.";
                }
            } else {
                echo "Por favor, complete todos los campos.";
            }
        }
    }


    public function editar($id)
    {
        $canalModel = new CanalModel();
        $canal = $canalModel->getCanalById($id);

        if ($canal) {
            require_once '../app/views/Canales/editar.php';
        } else {
            echo "Canal no encontrada.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
            $logo_url = filter_input(INPUT_POST, 'logo_url', FILTER_SANITIZE_STRING); // Se corrigió la variable para obtener 'logo_url'
            $api_base_url = filter_input(INPUT_POST, 'api_base_url', FILTER_SANITIZE_STRING);
            $activo = filter_input(INPUT_POST, 'activo', FILTER_SANITIZE_NUMBER_INT); // Se cambió a NUMBER_INT para 'activo'

            if (!empty($id) && !empty($nombre) && !empty($descripcion) && !empty($logo_url) && !empty($api_base_url) && isset($activo)) {
                $canalModel = new CanalModel();
                
                // Se corrigió la llamada para que coincida con el modelo
                if ($canalModel->updateCanal($id, $nombre, $descripcion, $logo_url, $api_base_url, $activo)) {
                    // Se corrigió la sintaxis de la redirección
                    header('Location: /canales');
                    exit();
                } else {
                    echo "Error al actualizar la canal.";
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
            $canalModel = new CanalModel();

            if ($canalModel->eliminarCanal($id)) {
                header('Location: /canales');
                exit();
            } else {
                echo "Error al eliminar la canal.";
            }
        } else {
            echo "ID de canal no válido.";
        }
    }
}