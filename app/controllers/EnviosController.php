<?php

require_once '../app/models/EnvioModel.php';
require_once '../app/models/tipoDeCambioModel.php';



class EnviosController
{
    public function index()
    {
        $EnvioModel = new EnvioModel();
        $envios = $EnvioModel->getEnvios();
        require_once '../app/views/envios/index.php';
    }

    public function crear()
    {
        // Obtener Tipo de cambio
        $tipoDeCambioModel = new TipoDeCambioModel();
        $tipoDeCambio = $tipoDeCambioModel->getTipoDeCambio();


        require_once '../app/views/envios/crear.php';

    }

    // En el método store()
    public function store()
    {
        // 1. Verificar si los datos del formulario fueron enviados
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 2. Limpiar los datos para mayor seguridad
            $nombre_envio = filter_input(INPUT_POST, 'nombre_envio', FILTER_SANITIZE_STRING);
            // Uso de FILTER_VALIDATE_FLOAT para el costo (decimales)
            $costo = filter_input(INPUT_POST, 'costo', FILTER_VALIDATE_FLOAT); 
            // Uso de FILTER_VALIDATE_INT para la moneda (entero)
            $moneda_id = filter_input(INPUT_POST, 'moneda_id', FILTER_VALIDATE_INT); 

            // 3. Validar que los datos no sean null o falsos
            // Se usa is_numeric para que el 0 no se considere vacío
            if ($nombre_envio !== false && is_numeric($costo) && is_numeric($moneda_id) ) { 
                $EnvioModel = new EnvioModel();
                
                // 4. Llamar al modelo para guardar los datos.
                if ($EnvioModel->addEnvio($nombre_envio, $costo, $moneda_id)) {
                    // 5. Redirigir a la página principal si fue exitoso
                    header('Location: /envios');
                    exit();
                } else {
                    echo "Error al guardar el Envio.";
                }
            } else {
                echo "Por favor, complete todos los campos.";
            }
        }
    }

    public function editar($id)
    {
        $EnvioModel = new EnvioModel();
        $envio = $EnvioModel->getEnvioById($id);

        if ($envio) {
            require_once '../app/views/envios/editar.php';
        } else {
            echo "Envio no encontrada.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $nombre_envio = filter_input(INPUT_POST, 'nombre_envio', FILTER_SANITIZE_STRING);
            $costo = filter_input(INPUT_POST, 'costo', FILTER_SANITIZE_STRING);


            if (!empty($id) && !empty($nombre_envio) && !empty($costo)) {
                $EnvioModel = new EnvioModel();
                
                // Se corrigió la llamada para que coincida con el modelo
                if ($EnvioModel->updateEnvio($id, $nombre_envio, $costo)) {
                    // Se corrigió la sintaxis de la redirección
                    header('Location: /envios');
                    exit();
                } else {
                    echo "Error al actualizar la Envio.";
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
            $EnvioModel = new EnvioModel();

            if ($EnvioModel->eliminarEnvio($id)) {
                header('Location: /envios');
                exit();
            } else {
                echo "Error al eliminar la Envio.";
            }
        } else {
            echo "ID de Envio no válido.";
        }
    }



}

