<?php

session_start();

require_once '../app/models/ItemModel.php';
require_once '../app/services/MeliApiClient.php';
require_once '../app/services/MeliItemImportador.php';



class ItemsController {

   public function index() {
        $this->importarItems();
    }


    public function importarItems() {
        
        global $conf; 
        global $VIEW_PATH;
        // global $LAYOUT_PATH;

        $resultados = [];
        $items = []; 

        // Verifica sesion PRG
        if (isset($_SESSION['import_success']) && $_SESSION['import_success'] === true) {
            $resultados = $_SESSION['import_results'] ?? [];
            
            // **2. LIMPIEZA DE SESIÓN (Crucial para evitar reenvío)**
            unset($_SESSION['import_results']);
            unset($_SESSION['import_success']);
        }


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
        // global $LAYOUT_PATH;
        

        $resultados = [];
        $items = []; 
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['item_ids'])) {
            // Si llega sin POST, simplemente recargar el formulario
            header('Location: /ventas/Items/importarItems'); 
            exit();
        }

        $data = ['item_id_input' => $_POST['item_ids']]; 

        try {
            $meliImportador = new MeliImportador();
            $resultados = $meliImportador->importarItemsPorLista($data);
        } catch (\Throwable $e) {
            $resultados['fatal_error'] = ['success' => false, 'message' => "Error fatal del sistema al importar: " . $e->getMessage()];
            error_log("Error fatal en MeliImportador: " . $e->getMessage());
        }


        // 3. **ALMACENAR RESULTADOS y REDIRECCIÓN (PRG)**
        $_SESSION['import_results'] = $resultados;
        $_SESSION['import_success'] = true;

        header('Location: /ventas/Items/importarItems'); 
        exit();


        // --- Carga de Vistas y Layout (Mismo patrón que importarItems) ---
        // $config_items = $conf['modules']['items']; 
        // $modulo = 'items';
        
        // $layout = VIEW_PATH . $config_items['layout']; 
        // $viewContent = VIEW_PATH . $config_items['view']; // Definir $viewContent aquí también
        
        // require_once $layout;
    }
// ...


// Listar los items
    public function listaItems() {

        //configuración global
        global $conf; 

        // ruta de vistas si está en el router
        global $VIEW_PATH;  

        $itemModel = new ItemModel();
        $items = $itemModel->obtenerTodosLosItems();
        
        // Inicializar otras variables necesarias para el layout/vistas
        $modulo = 'items';
        $resultados = [];

        $viewContent = VIEW_PATH . $conf['modules']['items']['viewItems'];

        $layout = VIEW_PATH . $conf['modules']['items']['layout'];
        
        // 3. Cargar el layout
        require_once $layout;



    }


    

}