<?php

session_start();

require_once '../app/models/ItemModel.php';
require_once '../app/models/EnviomeliModel.php';

require_once '../app/services/MeliApiClient.php';
require_once '../app/services/MeliItemImportador.php';



class ItemsController {

    // Prodiedad
    protected $token;
    protected $itemModel;       
    protected $meliApiClient;   



    public function __construct()
    {
        $this->itemModel = new ItemModel(); 
        
        // Usar __DIR__ para rutas seguras en servicios
        $secrets = require __DIR__ . '/../../cronjobs/secrets.php'; 

        // 2. Almacenar el token en la propiedad de clase
        $this->token = $secrets['prod_mercado_libre']['prodtToken'];
        
        // Usar la propiedad de clase
        $this->meliApiClient = new MeliApiClient($this->token); 
    }


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
        
        // // 2. Inicializar Servicio
        // $token = 'APP_USR-7626391564892909-111417-999c311c030938a7292e6f87cd953171-2424408169'; 
        $updater = new ActualizaEnviosMeli($this->token);
        
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



    public function listaCostoEnvios()
    {
        //configuración global
        global $conf; 
        global $VIEW_PATH; 

        // 1. OBTENER DATOS DE LA BASE DE DATOS
        $envioModel = new EnviomeliModel();
        // Llama a la función para obtener TODOS los registros de envío
        $lista_envios = $envioModel->obtenerTodosLosEnvios();


        // echo "<h1>Contenido de \$lista_envios:</h1>";
        // echo "<h2>Verifica que haya 8 registros aquí.</h2>";
        // echo "<pre style='background-color: #ffe; border: 1px solid #cc0; padding: 15px;'>";
        // var_dump($lista_envios);
        // echo "</pre>";
        // die("--- Script detenido para depuración del Array ---");
        
        // 2. PREPARAR VARIABLES PARA LA VISTA
        // $data = [
        //     'lista_envios' => $lista_envios, // Esta variable se usará en la vista
        //     // Otras variables que necesites pasar
        // ];

        // 3. CARGAR VISTAS Y LAYOUT
        
        /*
        $modulo = 'items';
        // Utiliza la configuración de la vista para el contenido
        $viewContent = VIEW_PATH . $conf['modules']['items']['viewResultadosEnvios'];
        $layout = VIEW_PATH . $conf['modules']['items']['layout'];

        */

        $modulo = 'items';


        $ruta_vista = $conf['modules']['items']['viewResultadosEnvios'];
        $ruta_layout = $conf['modules']['items']['layout'];

        $viewContent = rtrim(VIEW_PATH, '/') . '/' . trim($ruta_vista, '/');
        $layout = rtrim(VIEW_PATH, '/') . '/' . trim($ruta_layout, '/');

        
        // 4. Cargar el layout (usando el mismo patrón de require_once)
        require_once $layout; 
    }








    

}