<?php

require_once '../app/models/ProductoModel.php';

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
            $attributesInput = isset($_POST['attributes']) ? trim($_POST['attributes']) : null;
            if ($attributesInput) {
                $attributesArray = array_filter(array_map('trim', explode(',', $attributesInput)));
                $attributes = json_encode(array_map(function($val) {
                    return array('source' => $val);
                }, $attributesArray), JSON_UNESCAPED_UNICODE);
            } else {
                $attributes = '{}';
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
            $attributesInput = isset($_POST['attributes']) ? trim($_POST['attributes']) : null;
            if ($attributesInput) {
                $attributesArray = array_filter(array_map('trim', explode(',', $attributesInput)));
                $attributes = json_encode(array_map(function($val) {
                    return array('source' => $val);
                }, $attributesArray), JSON_UNESCAPED_UNICODE);
            } else {
                $attributes = '{}';
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
}
