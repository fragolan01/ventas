<?php

require_once '../app/models/ProductoModel.php';
require_once '../app/services/MLProductBuilder.php';
require_once '../app/services/MercadoLibrePublisher.php';

class ProductosController
{
    public function index()
    {
        $productoModel = new ProductoModel();
        $productos = $productoModel->getProductos();

        $view = '../app/views/productos/index.php';
        require_once '../app/views/productos/_layoutProductos.php';

    }

    public function crear()
    {
        $view = '../app/views/productos/crear.php';
        require_once '../app/views/productos/_layoutProductos.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitizar datos compatible con PHP5 y PHP8
            $title              = isset($_POST['title']) ? trim(htmlspecialchars($_POST['title'])) : null;
            $category_id        = isset($_POST['category_id']) ? trim(htmlspecialchars($_POST['category_id'])) : null;
            $price              = isset($_POST['price']) ? trim(htmlspecialchars($_POST['price'])) : null;
            $currency_id        = isset($_POST['currency_id']) ? trim(htmlspecialchars($_POST['currency_id'])) : null;
            $available_quantity = isset($_POST['available_quantity']) ? trim(htmlspecialchars($_POST['available_quantity'])) : null;
            $buying_mode        = isset($_POST['buying_mode']) ? trim(htmlspecialchars($_POST['buying_mode'])) : null;
            $conditions         = isset($_POST['conditions']) ? trim(htmlspecialchars($_POST['conditions'])) : null;
            $listing_type_id    = isset($_POST['listing_type_id']) ? trim(htmlspecialchars($_POST['listing_type_id'])) : null;
            $warranty_type      = isset($_POST['warranty_type']) ? trim(htmlspecialchars($_POST['warranty_type'])) : null;
            $warranty_time      = isset($_POST['warranty_time']) ? trim(htmlspecialchars($_POST['warranty_time'])) : null;

            // pictures
            $picturesInput = isset($_POST['pictures']) ? trim($_POST['pictures']) : null;
            if ($picturesInput) {
                $picturesArray = array_filter(array_map('trim', explode(',', $picturesInput)));
                $pictures = json_encode(array_map(function($url) {
                    return array('source' => $url);
                }, $picturesArray), JSON_UNESCAPED_UNICODE);
            } else {
                $pictures = '[]';
            }

            $description = isset($_POST['description']) ? trim(htmlspecialchars($_POST['description'])) : null;

            // attributes
            $brand = isset($_POST['brand']) ? trim($_POST['brand']) : '';
            $model = isset($_POST['model']) ? trim($_POST['model']) : '';

            $attributesInput = isset($_POST['attributes']) ? trim($_POST['attributes']) : null;

            if ($attributesInput) {
                $attributesArray = array_filter(array_map('trim', explode(',', $attributesInput)));
                $attributes = json_encode(array_map(function($val) {
                    return ["source" => $val];
                }, $attributesArray), JSON_UNESCAPED_UNICODE);
            } else {
                $attributes = json_encode([
                    ["id" => "BRAND", "value_name" => $brand],
                    ["id" => "MODEL", "value_name" => $model]
                ], JSON_UNESCAPED_UNICODE);
            }


            $product_id   = isset($_POST['product_id']) ? trim(htmlspecialchars($_POST['product_id'])) : null;
            $shipping_mode = isset($_POST['shipping_mode']) ? trim(htmlspecialchars($_POST['shipping_mode'])) : null;
            $shipping_free = isset($_POST['shipping_free']) ? trim(htmlspecialchars($_POST['shipping_free'])) : null;
            $status        = isset($_POST['status']) ? trim(htmlspecialchars($_POST['status'])) : null;

            if (!empty($title) && !empty($category_id) && !empty($price) && !empty($currency_id) && !empty($available_quantity) && !empty($listing_type_id) && !empty($pictures)) {
                $productoModel = new ProductoModel();

                if ($productoModel->addProducto($title, $category_id, $price, $currency_id, $available_quantity, $buying_mode, $conditions, $listing_type_id, $warranty_type, $warranty_time, $pictures, $description, $attributes, $product_id, $shipping_mode, $shipping_free, $status)) {
                    header('Location: /ventas/productos');
                    exit;
                } else {
                    echo "Error al guardar el Producto.";
                }
            } else {
                echo "Por favor, complete todos los campos.";
            }
        }
    }

    public function editar($id)
    {
        $productoModel = new ProductoModel();
        $producto = $productoModel->getProductoById((int)$id);

        if ($producto) {
            $view = '../app/views/productos/editar.php';
            require_once '../app/views/productos/_layoutProductos.php';
        } else {
            echo "Producto no encontrado.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id                = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $title             = isset($_POST['title']) ? trim(htmlspecialchars($_POST['title'])) : null;
            $category_id       = isset($_POST['category_id']) ? trim(htmlspecialchars($_POST['category_id'])) : null;
            $price             = isset($_POST['price']) ? trim(htmlspecialchars($_POST['price'])) : null;
            $currency_id       = isset($_POST['currency_id']) ? trim(htmlspecialchars($_POST['currency_id'])) : null;
            $available_quantity= isset($_POST['available_quantity']) ? trim(htmlspecialchars($_POST['available_quantity'])) : null;
            $buying_mode       = isset($_POST['buying_mode']) ? trim(htmlspecialchars($_POST['buying_mode'])) : null;
            $conditions        = isset($_POST['conditions']) ? trim(htmlspecialchars($_POST['conditions'])) : null;
            $listing_type_id   = isset($_POST['listing_type_id']) ? trim(htmlspecialchars($_POST['listing_type_id'])) : null;
            $warranty_type     = isset($_POST['warranty_type']) ? trim(htmlspecialchars($_POST['warranty_type'])) : null;
            $warranty_time     = isset($_POST['warranty_time']) ? trim(htmlspecialchars($_POST['warranty_time'])) : null;

            // pictures
            $picturesInput = isset($_POST['pictures']) ? trim($_POST['pictures']) : null;
            if ($picturesInput) {
                $picturesArray = array_filter(array_map('trim', explode(',', $picturesInput)));
                $pictures = json_encode(array_map(function($url) {
                    return array('source' => $url);
                }, $picturesArray), JSON_UNESCAPED_UNICODE);
            } else {
                $pictures = '[]';
            }

            $description = isset($_POST['description']) ? trim(htmlspecialchars($_POST['description'])) : null;

            // attributes
            $brand = isset($_POST['brand']) ? trim($_POST['brand']) : '';
            $model = isset($_POST['model']) ? trim($_POST['model']) : '';

            $attributesInput = isset($_POST['attributes']) ? trim($_POST['attributes']) : null;

            if ($attributesInput) {
                $attributesArray = array_filter(array_map('trim', explode(',', $attributesInput)));
                $attributes = json_encode(array_map(function($val) {
                    return ["source" => $val];
                }, $attributesArray), JSON_UNESCAPED_UNICODE);
            } else {
                // $attributes = '{}';
                $attributes = json_encode([
                    ["id" => "BRAND", "value_name" => $brand],
                    ["id" => "MODEL", "value_name" => $model]
                ], JSON_UNESCAPED_UNICODE);
            }

            $product_id    = isset($_POST['product_id']) ? trim(htmlspecialchars($_POST['product_id'])) : null;
            $shipping_mode = isset($_POST['shipping_mode']) ? trim(htmlspecialchars($_POST['shipping_mode'])) : null;
            $shipping_free = isset($_POST['shipping_free']) ? trim(htmlspecialchars($_POST['shipping_free'])) : null;
            $status        = isset($_POST['status']) ? trim(htmlspecialchars($_POST['status'])) : null;

            if (!empty($id) && !empty($title) && !empty($category_id) && !empty($price) && !empty($currency_id) && !empty($available_quantity) && !empty($listing_type_id) && !empty($pictures)) {
                $productoModel = new ProductoModel();

                if ($productoModel->updateProducto($id, $title, $category_id, $price, $currency_id, $available_quantity, $buying_mode, $conditions, $listing_type_id, $warranty_type, $warranty_time, $pictures, $description, $attributes, $product_id, $shipping_mode, $shipping_free, $status)) {
                    header('Location: /ventas/productos');
                    exit;
                } else {
                    echo "Error al actualizar el Producto.";
                }
            } else {
                echo "Por favor, complete todos los campos.";
            }
        }
    }

    public function eliminar($id)
    {
        $id = (int)$id;
        if ($id > 0) {
            $productoModel = new ProductoModel();
            if ($productoModel->eliminarProducto($id)) {
                header('Location: /ventas/productos');
                exit;
            } else {
                echo "Error al eliminar el Producto.";
            }
        } else {
            echo "ID de Producto no válido.";
        }
    }

    // Se llama a un miembro estatico directamemente sin llamar una instancia
    // Ultimo metodo agregado
    public function publicar($id)
    {
        // Carga las key de api MELI
        // $secrets = require '../config/secrets.php';
        
        // Obtener el token de Syscom del array de secretos
        // $token = $secrets['test_mercado_libre']['testToken'];
        $token = 'APP_USR-7626391564892909-090611-d79480f94187e411e8d9cfe94fe6fc3e-2645087980';


        $productoModel = new ProductoModel();
        $producto = $productoModel ->getProductoById($id);

        // if (!$producto) {
        //     die("Producto No encontrado");
        // }


        // Revisar que informacion tengo ****************DEBUG INFO******
        file_put_contents(__DIR__ . '/debug_datos_entrada.txt', print_r($producto, true));

        $payload = MLProductBuilder::buildPayload($producto);

            try {
                $response = MercadoLibrePublisher::postItem($payload, $token);

                // 5. Manejar la respuesta
                if (isset($response['id'])) {
                    // Publicación exitosa
                    echo "Producto publicado con éxito. ID de Mercado Libre: " . htmlspecialchars($response['id']);

                    // Opcional: Actualizar la base de datos con el ID de Mercado Libre y el estado
                    $productoModel->updateItemId($id, $response['id']);
                    $productoModel->updateStatus($id, 'activo'); // Por ejemplo
                } else {
                    // Publicación fallida. La API devuelve errores en la respuesta.
                    $error_message = json_encode($response, JSON_PRETTY_PRINT);
                    echo "Error al publicar el producto en Mercado Libre.<br>";
                    echo "Detalles del error: <pre>" . htmlspecialchars($error_message) . "</pre>";
                    
                    // Opcional: Guardar el error en la base de datos
                    $productoModel->updateError($id, $error_message);
                    $productoModel->updateStatus($id, 'error'); // Marcar el estado como 'error'
                }
            } catch (\Exception $e) {
                echo "Ocurrió un error inesperado: " . $e->getMessage();
            }

            // Probar el mapeo
            // echo "<pre>";
            // print_r($payload);
            // echo "</pre>";
            // exit; // Detiene la ejecución

            // Separar Item/descripcion
            $data = MercadoLibrePublisher::sanitizeForPost($payload);

            // Mostrar despuracion verificar que item no muestre Descripcion
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit;
    }




}
