<?php
// app/controllers/SyscomController.php

// session_start();

require_once '../app/models/ItemModel.php';
require_once '../app/services/MeliApiClient.php';
require_once '../app/services/MeliItemImportador.php';


// app/controllers/ItemsController.php (CORREGIDO)

class ItemsController {

   public function index() {
        $this->importarItems();
    }


    public function importarItems() {
        
        global $conf; 
        global $VIEW_PATH;
        global $LAYOUT_PATH;

        $resultados = [];
        $items = []; 

        // 1. Obtener la configuración del módulo (array completo)
        $config_items = $conf['modules']['items']; 
        $modulo = 'items';
        
        $layout = VIEW_PATH . $config_items['layout']; 

        // 3. Definir el contenido que va DENTRO del layout
        $viewContent = VIEW_PATH . $config_items['view'];


        require_once $layout;
    }

// ...
    public function procesarImportacionMeli() {
        
        global $conf; 
        global $VIEW_PATH;
        global $LAYOUT_PATH; // Asegurar que exista
        
        $resultados = [];
        $items = []; 
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['item_ids'])) {
            // Si llega sin POST, simplemente recargar el formulario
            $this->importarItems();
            return;
        }

        $data = ['item_id_input' => $_POST['item_ids']]; 

        try {
            $meliImportador = new MeliImportador();
            $resultados = $meliImportador->importarItemsPorLista($data);
        } catch (\Throwable $e) {
            $resultados['fatal_error'] = ['success' => false, 'message' => "Error fatal del sistema al importar: " . $e->getMessage()];
            error_log("Error fatal en MeliImportador: " . $e->getMessage());
        }

        // --- Carga de Vistas y Layout (Mismo patrón que importarItems) ---
        $config_items = $conf['modules']['items']; 
        $modulo = 'items';
        
        $layout = VIEW_PATH . $config_items['layout']; 
        $viewContent = VIEW_PATH . $config_items['view']; // Definir $viewContent aquí también
        
        require_once $layout;
    }
// ...

    
/*
    public function procesarImportacionItems() {
        
        // Variables globales necesarias
        global $conf; 
        global $VIEW_PATH;
        global $LAYOUT_PATH;
        
        $resultados = [];
        $items = []; 

        // 1. Validar solicitud y obtener datos (esperamos 'item_ids' del formulario)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['item_ids'])) {
            // Si no es un POST válido, redirigir o recargar el formulario vacío
            $this->importarItems();
            return;
        }

        $data = [
            // 'item_id_input' es la clave que MeliImportador.php espera
            'item_id_input' => $_POST['item_ids'] 
        ];

        // 2. Procesar con MeliImportador
        try {
            // Se asume que MeliImportador ya incluye ItemModel y MeliApiClient
            $meliImportador = new MeliImportador();
            
            // $resultados contiene el reporte (ID => ['success' => bool, 'message' => string])
            $resultados = $meliImportador->importarItemsPorLista($data);

        } catch (\Throwable $e) {
            // Manejo de error fatal (ej. fallo de autoload o error de configuración)
            $resultados['fatal_error'] = ['success' => false, 'message' => "Error fatal del sistema al importar: " . $e->getMessage()];
            error_log("Error fatal en MeliImportador: " . $e->getMessage());
        }

        // 3. Recargar la vista del formulario para mostrar el reporte ($resultados)
        
        // Cargar la configuración de la vista de formulario
        $config_items = $conf['modules']['items']; 
        $modulo = 'items';
        
        // Las variables $layout y $viewContent son las mismas que usa importarItems()
        $layout = LAYOUT_PATH . $config_items['layout']; 
        $viewContent = VIEW_PATH . $config_items['view']; 

        // Requerir el layout para mostrar el formulario Y los resultados
        require_once $layout;
    }
 */

}