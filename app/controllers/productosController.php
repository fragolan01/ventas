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
        // 1. Verificar si los datos del formulario fueron enviados
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 2. Limpiar los datos para mayor seguridad
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
            $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_STRING);
            $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_STRING);
            $currency_id = filter_input(INPUT_POST, 'currency_id', FILTER_SANITIZE_STRING);
            $available_quantity = filter_input(INPUT_POST, 'available_quantity', FILTER_SANITIZE_STRING);
            $buying_mode = filter_input(INPUT_POST, 'buying_mode', FILTER_SANITIZE_STRING);
            $conditions = filter_input(INPUT_POST, 'conditions', FILTER_SANITIZE_STRING);
            $listing_type_id = filter_input(INPUT_POST, 'listing_type_id', FILTER_SANITIZE_STRING);
            $warranty_type = filter_input(INPUT_POST, 'warranty_type', FILTER_SANITIZE_STRING);
            $warranty_time = filter_input(INPUT_POST, 'warranty_time', FILTER_SANITIZE_STRING);

            // $pictures = filter_input(INPUT_POST, 'pictures', FILTER_SANITIZE_STRING);
            $picturesInput = filter_input(INPUT_POST, 'pictures', FILTER_SANITIZE_STRING);
            $picturesArray = array_filter(array_map('trim', explode(',', (string)$picturesInput)));
            $pictures = json_encode(array_map(fn($u) => ['source' => $u], $picturesArray), JSON_UNESCAPED_UNICODE);

            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
            
            // $attributes = filter_input(INPUT_POST, 'attributes', FILTER_SANITIZE_STRING);
            $attributesInput = filter_input(INPUT_POST, 'attributes', FILTER_SANITIZE_STRING);
            $attributesArray = array_filter(array_map('trim', explode(',', (string)$attributesInput)));
            $$attributes = json_encode(array_map(fn($u) => ['source' => $u], $attributesArray), JSON_UNESCAPED_UNICODE);



            $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_STRING);
            $shipping_mode = filter_input(INPUT_POST, 'shipping_mode', FILTER_SANITIZE_STRING);
            $shipping_free = filter_input(INPUT_POST, 'shipping_free', FILTER_SANITIZE_STRING);
            $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);


            // 3. Validar que los datos no estén vacíos
            if (!empty($title) && !empty($category_id) && !empty($price) && !empty($currency_id) && !empty($available_quantity) && !empty($listing_type_id) && !empty($pictures) ) { 
                $productoModel = new ProductoModel();
                
                // 4. Llamar al modelo para guardar los datos. Se corrigió la llamada para que coincida con el modelo.
                if ($productoModel->addProducto($title, $category_id, $price, $currency_id, $available_quantity, $buying_mode, $conditions, $listing_type_id, $warranty_type, $warranty_time, $pictures, $description, $attributes, $product_id, $shipping_mode, $shipping_free, $status)) {
                    // 5. Redirigir a la página principal de Producto si fue exitoso
                    header('Location: /productos');
                    exit();
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
        $producto = $productoModel->getProductoById($id);

        if ($producto) {
            $view = '../app/views/productos/editar.php';
            require_once '../app/views/productos/_layoutProductos.php';

        } else {
            echo "Producto no encontrada.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
            $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_STRING);
            $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_STRING);
            $currency_id = filter_input(INPUT_POST, 'currency_id', FILTER_SANITIZE_STRING);
            $available_quantity = filter_input(INPUT_POST, 'available_quantity', FILTER_SANITIZE_STRING);
            $buying_mode = filter_input(INPUT_POST, 'buying_mode', FILTER_SANITIZE_STRING);
            $conditions = filter_input(INPUT_POST, 'conditions', FILTER_SANITIZE_STRING);
            $listing_type_id = filter_input(INPUT_POST, 'listing_type_id', FILTER_SANITIZE_STRING);
            $warranty_type = filter_input(INPUT_POST, 'warranty_type', FILTER_SANITIZE_STRING);
            $warranty_time = filter_input(INPUT_POST, 'warranty_time', FILTER_SANITIZE_STRING);
            $pictures = filter_input(INPUT_POST, 'pictures', FILTER_SANITIZE_STRING);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
            $attributes = filter_input(INPUT_POST, 'attributes', FILTER_SANITIZE_STRING);
            $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_STRING);
            $shipping_mode = filter_input(INPUT_POST, 'shipping_mode', FILTER_SANITIZE_STRING);
            $shipping_free = filter_input(INPUT_POST, 'shipping_free', FILTER_SANITIZE_STRING);
            $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);




            if (!empty($id) && !empty($title) && !empty($category_id) && !empty($price) && !empty($currency_id) && !empty($available_quantity) && !empty($listing_type_id) && !empty($pictures)) {
                $productoModel = new ProductoModel();
                
                // Se corrigió la llamada para que coincida con el modelo
                if ($productoModel->updateProducto($id, $title, $category_id, $price, $currency_id, $available_quantity, $buying_mode, $conditions, $listing_type_id, $warranty_type, $warranty_time, $pictures, $description, $attributes, $product_id, $shipping_mode, $shipping_free, $status)) {
                    // Se corrigió la sintaxis de la redirección
                    header('Location: /productos');
                    exit();
                } else {
                    echo "Error al actualizar la Producto.";
                }
            } else {
                echo "Por favor, complete todos los campos.";
            }
        }
    }


    public function eliminar($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if ($id) {
            $productoModel = new ProductoModel();

            if ($productoModel->eliminarProducto($id)) { 
                header('Location: /productos');
                exit();
            } else {
                echo "Error al eliminar la Producto.";
            }
        } else {
            echo "ID de Producto no válido.";
        }
    }
}