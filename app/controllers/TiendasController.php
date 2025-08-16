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
}