<?php

require_once 'ProveedorImportadorInterface.php';
require_once '../app/models/SyscomModel.php';
require_once '../app/services/SyscomApiClient.php';

class SyscomImportador implements ProveedorImportadorInterface {

    private $syscomModel;
    private $syscomApiClient;

    public function __construct() {
        $this->syscomModel = new SyscomModel();
        $secrets = require '../config/secrets.php';
        $token = $secrets['syscom']['api_token'];
        $this->syscomApiClient = new SyscomApiClient($token);
    }

    public function importarProductos(array $data): array {
        $resultados = [];

        $proveedor_id = $data['proveedor_id'];
        $producto_id_input = $data['producto_id_input']; // string de IDs

        // Convertir a array de IDs limpios
        $producto_id_array = array_map('trim', explode(',', $producto_id_input));
        $producto_id_array = array_filter($producto_id_array);

        // 🔹 NUEVO: obtener productos existentes de forma correcta (recibe array)
        $productosExistentes = $this->syscomModel->obtenerProductosPorIds($producto_id_array);
        $idsExistentes = array_column($productosExistentes, 'producto_id');
        $producto_id_a_consultar = array_diff($producto_id_array, $idsExistentes);

        foreach ($producto_id_a_consultar as $producto_id) {
            $producto_data = $this->syscomApiClient->getProductoData($producto_id);
            
            if ($producto_data) {
                $producto_data['proveedor_id'] = $proveedor_id;

                $producto_id_interno = $this->syscomModel->insertaOActualizaProducto($producto_data);

                if ($producto_id_interno) {
                    $precio_guardado = $this->syscomModel->insertaOActualizaPrecio(
                        $producto_id_interno,
                        $producto_data['precios']
                    );

                    if ($precio_guardado) {
                        $resultados[] = [
                            'producto_id' => $producto_id,
                            'estado' => 'success',
                            'mensaje' => 'Importado/Actualizado Producto y Precio correctamente.'
                        ];
                    } else {
                        $resultados[] = [
                            'producto_id' => $producto_id,
                            'estado' => 'warning',
                            'mensaje' => 'Producto guardado, pero error al guardar el precio.'
                        ];
                    }
                } else {
                    $resultados[] = [
                        'producto_id' => $producto_id,
                        'estado' => 'error',
                        'mensaje' => 'Error al guardar el producto en la base de datos.'
                    ];
                }
            } else {
                $resultados[] = [
                    'producto_id' => $producto_id,
                    'estado' => 'error',
                    'mensaje' => 'Error al obtener datos de la API.'
                ];
            }
        }

        return $resultados;
    }
}
