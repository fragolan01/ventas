<?php

// SyscomImportador.php Esta es una estrategia concreta donde se cargan los productos por la API SYSCOM

require_once 'ProveedorImportadorInterface.php';
require_once '../app/models/SyscomModel.php';
require_once '../app/services/SyscomApiClient.php';

class SyscomImportador implements ProveedorImportadorInterface {

    private $syscomModel;
    private $syscomApiClient;

    // Constructor para recibir las dependencias (modelo y cliente de API)
    public function __construct() {
        // Inicializa el modelo y el cliente de API para poder usarlos.
        // No los pases por el constructor, ya que no los necesitas en la fábrica.
        $this->syscomModel = new SyscomModel();
        $secrets = require '../config/secrets.php';
        $token = $secrets['syscom']['api_token'];
        $this->syscomApiClient = new SyscomApiClient($token);
    }
    
    // Traemos el metodo de la estrategia !!!!
    public function importarProductos(array $data): array {
        $resultados = [];
        
        // Extrae los datos necesarios del array $data
        // La lógica del controlador principal debe pasar estos datos aquí
        $proveedor_id = $data['proveedor_id'];
        $producto_id_input = $data['producto_id_input']; // string de IDs aquí

        // Lógica de procesamiento que tenías en el controlador
        $producto_id_array = array_map('trim', explode(',', $producto_id_input));
        $producto_id_array = array_filter($producto_id_array);

        $productosExistentes = $this->syscomModel->obtenerProductosPorIds($producto_id_array);
        $idsExistentes = array_column($productosExistentes, 'producto_id');
        $producto_id_a_consultar = array_diff($producto_id_array, $idsExistentes);

        foreach ($producto_id_a_consultar as $producto_id) {
            $producto_data = $this->syscomApiClient->getProductoData($producto_id);
            
            if ($producto_data) {
                $producto_data['proveedor_id'] = $proveedor_id;
                
                if ($this->syscomModel->insertaOActualizaProducto($producto_data)) {
                    $resultados[] = ['producto_id' => $producto_id, 'estado' => 'success', 'mensaje' => 'Importado/Actualizado correctamente.'];
                } else {
                    $resultados[] = ['producto_id' => $producto_id, 'estado' => 'error', 'mensaje' => 'Error al guardar en la base de datos.'];
                }
            } else {
                $resultados[] = ['producto_id' => $producto_id, 'estado' => 'error', 'mensaje' => 'Error al obtener datos de la API.'];
            }
        }
        
        // Devuelve los resultados para que el controlador pueda usarlos
        return $resultados;
    }
}