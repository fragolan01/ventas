<?php

require_once '../app/models/SyscomModel.php';
require_once '../app/services/SyscomApiClient.php';

class SyscomController
{

    private $syscomModel;
    private $syscomApiClient;

    // Constructor para iniciar el modelo y el servicio
    public function __construct()
    {
        // El modelo ya tiene la conexion a la BD
        $this ->syscomModel = new SyscomModel();

        // Carga las key de api syscom
        $secrets = require '../config/secrets.php';

        // Obtener el token de Syscom del array de secretos
        $token = $secrets['syscom']['api_token'];

        $this->syscomApiClient = new SyscomApiClient($token);

    }


    /**
     * Muestra el formulario para ingresar los productos y procesa la solicitud de importación.
     */

    public function importarProductos()
    {
        $resultados = [];
        $productos_id = 0;

        // Post en formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'])) {
            $producto_id_input = trim($_POST['producto_id']);
            $producto_id_array = array_map('trim', explode(',', $producto_id_input));
            $producto_id_array = array_filter($producto_id_array); // Eliminar vacíos

            $producto_id_procesados = count($producto_id_array);

            // 2. Obtener los IDs ya existentes en la base de datos
            $productosExistentes = $this->syscomModel->obtenerProductosPorIds($producto_id_array);
            $idsExistentes = array_column($productosExistentes, 'producto_id');

            $producto_id_a_consultar = array_diff($producto_id_array, $idsExistentes);

            // 3. Iterar sobre ID PRODUDCTOS que no existen y consultarlos a la API
            foreach ($producto_id_a_consultar as $producto_id) {
                $producto_data = $this->syscomApiClient->getProductoData($producto_id);
                
                if ($producto_data) {
                    // 4. Insertar o actualizar el producto en la base de datos
                    if ($this->syscomModel->insertaOActualizaProducto($producto_data)) {
                        $resultados[] = ['producto_id' => $producto_id, 'estado' => 'success', 'mensaje' => 'Importado/Actualizado correctamente.'];
                    } else {
                        $resultados[] = ['producto_id' => $producto_id, 'estado' => 'error', 'mensaje' => 'Error al guardar en la base de datos.'];
                    }
                } else {
                    $resultados[] = ['producto_id' => $producto_id, 'estado' => 'error', 'mensaje' => 'Error al obtener datos de la API.'];
                }
            }
        }

        // pASAR RESULTADOS A LA vista
        include __DIR__ . '\..\views/IngresoProductos/importar_syscom.php';

    }



}