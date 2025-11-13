<?php

require_once '../app/models/EnviomeiModel.php';
require_once '../app/services/ActualizaEnviosMeli.php';

class EnviosMeliController 
{
    private $model;

    public function __construct()
    {
        // Instancia el modelo que has terminado
        $this->model = new EnviomeiModel(); 
    }

    // 1. Mostrar todos los envíos
    public function index()
    {
        $envios = $this->model->getenvios_meli();
        
        // Aquí llamas a tu vista (ej. include 'envios_view.php';)
        // y le pasas la variable $envios.
        // echo "Lista de Envíos: <pre>" . print_r($envios, true) . "</pre>";
    }

    // 2. Procesar el formulario de adición
    public function agregar()
    {
        // Esto es solo un ejemplo, los datos vendrán de $_POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item_id = $_POST['item_id'] ?? '';
            $item_price = $_POST['item_price'] ?? 0.0;
            // ... obtener el resto de variables ...

            $exito = $this->model->addEnviosMeli($item_id, $item_price, /* ... */);
            
            if ($exito) {
                // Redirigir o mostrar mensaje de éxito
                // header('Location: /envios');
            } else {
                // Mostrar mensaje de error
            }
        }
        // Llamar a la vista del formulario de agregar si es un GET
        // include 'add_form_view.php';
    }
    
    // Puedes continuar con métodos para editar (procesar el formulario de actualización)
    // y para eliminar.
}

// Ejemplo de cómo ejecutar:
// $controller = new EnviosMeliController();
// $controller->index();