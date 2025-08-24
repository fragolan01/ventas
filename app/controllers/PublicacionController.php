<?php

require_once '../app/models/PublicacionModel.php';

class PublicacionController
{
    public function index()
    {
        $PublicacionModel = new PublicacionModel();
        $Publicaciones = $PublicacionModel->getTipoPublicacion();
        require_once '../app/views/publicacion/index.php';
    }

    public function crear()
    {
        require_once '../app/views/publicacion/crear.php';
    }

    public function store()
    {
        // 1. Verificar si los datos del formulario fueron enviados
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 2. Limpiar los datos para mayor seguridad
            $tipoPublicacionId = filter_input(INPUT_POST, 'tipo_publi_id', FILTER_SANITIZE_STRING);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $canalId = filter_input(INPUT_POST, 'canal_id', FILTER_SANITIZE_STRING);

            // 3. Validar que los datos no estén vacíos
            if (!empty($tipoPublicacionId) && !empty($name) && !empty($canalId)) { // Se cambió a isset para 'activo'
                $tipoPubliModel = new PublicacionModel();
                
                // 4. Llamar al modelo para guardar los datos. Se corrigió la llamada para que coincida con el modelo.
                if ($tipoPubliModel->addTipoPublicacion($tipoPublicacionId, $name, $canalId)) {
                    // 5. Redirigir a la página principal de Canales si fue exitoso
                    header('Location: /publicacion');
                    exit();
                } else {
                    echo "Error al guardar publicacion.";
                }
            } else {
                echo "Por favor, complete todos los campos.";
            }
        }
    }


    public function editar($id)
    {
        $publicacionModel = new PublicacionModel();
        $tipoPublicacion = $publicacionModel->getTipoPublicacionById($id);

        if ($tipoPublicacion) {
            require_once '../app/views/publicacion/editar.php';
        } else {
            echo "Tipo publicacion no encontrada.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $tipoPubliId = filter_input(INPUT_POST, 'tipo_publi_id', FILTER_VALIDATE_INT);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $canalId = filter_input(INPUT_POST, 'canal_id', FILTER_VALIDATE_INT);

            // La validación ahora busca las variables correctas
            if (!empty($id) && !empty($tipoPubliId) && !empty($name) && !empty($canalId)) {
                $publicacionModel = new PublicacionModel();
                
                if ($publicacionModel->updateTipoPublicacion($id, $tipoPubliId, $name, $canalId)) {
                    header('Location: /publicacion');
                    exit();
                } else {
                    echo "Error al actualizar la Publicacion.";
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
            $tipoPublicacionModel = new PublicacionModel();

            if ($tipoPublicacionModel->eliminarTipoPublicacion($id)) {
                header('Location: /publicacion');
                exit();
            } else {
                echo "Error al eliminar Publicacion.";
            }
        } else {
            echo "ID de publicacion no válido.";
        }
    }
}