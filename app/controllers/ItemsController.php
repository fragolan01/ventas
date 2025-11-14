<?php

session_start();

require_once '../app/models/ItemModel.php';
require_once '../app/models/EnviomeliModel.php';

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
            
            //LIMPIEZA DE SESIÓN
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

        // $item_ids_input = $_POST['item_ids']; 


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

        // ruta de vistas ROUTER
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



    // Listar los items
    public function detalleDeEnvios() {

        //configuración global
        global $conf; 

        // ruta de vistas ROUTER
        global $VIEW_PATH;  

        $itemModel = new ItemModel();
        // $items = $itemModel->obtenerTodosLosItems();
        
        // Inicializar otras variables necesarias para el layout/vistas
        $modulo = 'items';
        $resultados = [];

        $viewContent = VIEW_PATH . $conf['modules']['items']['viewEnvios'];

        $layout = VIEW_PATH . $conf['modules']['items']['layout'];
        
        // 3. Cargar el layout
        require_once $layout;



    }



    // 2. Nuevo Método para procesar la consulta del costo de envío
    public function procesarCostoEnvio()
    {
        global $conf; 
        global $VIEW_PATH; 

        // Incluir el servicio (ruta revisada)
        require_once '../app/services/ActualizaEnviosMeli.php';

        // 1. Obtener IDs del formulario
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['item_ids'])) {
            header('Location: /ventas/Items/detalleDeEnvios');
            exit(); // CRÍTICO: Siempre salir después de header/redirección
        }
        
        $itemIdsString = $_POST['item_ids'];
        $itemIds = array_map('trim', explode(',', $itemIdsString));
        $resultados = [];
        
        // 2. Inicializar Servicio
        $token = 'APP_USR-7626391564892909-111315-ca14e8e5566badf720803da87cb38e73-2424408169'; 
        $updater = new ActualizaEnviosMeli($token);
        
        // 3. Procesar cada ID
        foreach ($itemIds as $itemId) {
            if (!empty($itemId)) {
                $resultados[$itemId] = $updater->updateShippingCost($itemId);
            }
        }

        // 4. CARGAR VISTA (Patrón adoptado de detalleDeEnvios y importarItems)
        
        // Inicializar otras variables necesarias para el layout/vistas
        $modulo = 'items';
        
        // Necesitas una entrada en $conf['modules']['items']['viewEnvios']
        $viewContent = VIEW_PATH . $conf['modules']['items']['viewEnvios'];
        $layout = VIEW_PATH . $conf['modules']['items']['layout'];

        // 5. Cargar el layout
        require_once $layout;
    }

// ...


    

}