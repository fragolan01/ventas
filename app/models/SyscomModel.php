<?php
require_once 'Model.php';

class SyscomModel extends Model
{
    // === PRODUCTOS ORQUESTADOR ===
    public function insertaOActualizaProducto($data)
    {
        $producto_id_interno = $this->obtenerProductoId($data['producto_id']);

        if ($producto_id_interno) {
            $this->actualizaProducto($data);
            return $producto_id_interno; // Devuelve el ID INTERNO existente.
        } else {
            return $this->insertaProducto($data); // insertaProducto ahora devuelve el nuevo ID.
        }
    }

    // === PRODUCTOS BUSCADOR ===
    public function obtenerProductoId($producto_id)
    {
        $sql = "SELECT id FROM productos WHERE producto_id = ?"; 
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log("Error al preparar la consulta de obtenerProductoId: " . $this->db->error);
            return null;
        }

        $stmt->bind_param("i", $producto_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $producto = $result->fetch_assoc();
        $stmt->close();

        return $producto['id'] ?? null;
    }

    // === 🔹 NUEVO MÉTODO PARA MÚLTIPLES PRODUCTOS ===
    public function obtenerProductosPorIds(array $ids)
    {
        if (empty($ids)) return [];

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $types = str_repeat('i', count($ids));

        $sql = "SELECT producto_id FROM productos WHERE producto_id IN ($placeholders)";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log("Error al preparar obtenerProductosPorIds: " . $this->db->error);
            return [];
        }

        $stmt->bind_param($types, ...$ids);
        $stmt->execute();
        $result = $stmt->get_result();
        $productos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $productos;
    }

    // === PRODUCTOS INSERCIÓN ===
    private function insertaProducto($data)
    {
        if (!isset($data['proveedor_id'])) {
            error_log("Falta el proveedor_id en los datos del producto.");
            return false;
        }

        $sql = "INSERT INTO productos (producto_id, proveedor_id, modelo, total_existencia, titulo, marca, imagens, link_privado, descripcion, caracteristicas, peso, alto, largo, ancho) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log("Error al preparar la consulta de inserción: " . $this->db->error);
            return false;
        }

        if (is_array($data['caracteristicas'])) {
            $data['caracteristicas'] = json_encode($data['caracteristicas'], JSON_UNESCAPED_UNICODE);
        }

        $stmt->bind_param("iisssssssssddd",
            $data['producto_id'], $data['proveedor_id'], $data['modelo'], $data['total_existencia'],
            $data['titulo'], $data['marca'], $data['imagens'], $data['link_privado'],
            $data['descripcion'], $data['caracteristicas'], $data['peso'], $data['alto'],
            $data['largo'], $data['ancho'] 
        );

        $result = $stmt->execute();
        $last_id = $result ? $this->db->insert_id : false;
        $stmt->close();
        return $last_id; 
    }

    // === PRODUCTOS ACTUALIZACIÓN ===
    private function actualizaProducto($data)
    {
        $sql = "UPDATE productos SET modelo = ?, total_existencia = ?, titulo = ?, marca = ?, imagens = ?, link_privado = ?, descripcion = ?, caracteristicas = ?, peso = ?, alto = ?, largo = ?, ancho = ?
                WHERE producto_id = ?";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log("Error al preparar la actualización de producto: " . $this->db->error);
            return false;
        }

        if (is_array($data['caracteristicas'])) {
            $data['caracteristicas'] = json_encode($data['caracteristicas'], JSON_UNESCAPED_UNICODE);
        }

        $stmt->bind_param("ssssssssddddi",
            $data['modelo'], $data['total_existencia'], $data['titulo'], $data['marca'],
            $data['imagens'], $data['link_privado'], $data['descripcion'], $data['caracteristicas'],
            $data['peso'], $data['alto'], $data['largo'], $data['ancho'],
            $data['producto_id']
        );

        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // ----------------------------------------------------------------------------------
    // === PRECIOS MÉTODOS ===
    // ----------------------------------------------------------------------------------

    public function obtenerPrecioPorProductoId(int $producto_id_interno)
    {
        $sql = "SELECT id FROM precios_productos WHERE producto_id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $producto_id_interno);
        $stmt->execute();
        $result = $stmt->get_result();
        $precio = $result->fetch_assoc();
        $stmt->close();

        return $precio['id_precio'] ?? null;
    }

    public function insertaOActualizaPrecio(int $producto_id_interno, array $datos_precios)
    {
        $id_precio_existente = $this->obtenerPrecioPorProductoId($producto_id_interno);

        if ($id_precio_existente) {
            return $this->actualizaPrecio($producto_id_interno, $datos_precios);
        } else {
            return $this->insertaPrecio($producto_id_interno, $datos_precios);
        }
    }

    private function insertaPrecio(int $producto_id_interno, array $datos_precios)
    {
        $sql = "INSERT INTO precios_productos
                (producto_id, precio1, precio_especial, precio_descuento, precio_lista) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar la consulta de inserción de precios: " . $this->db->error);
            return false;
        }

        $stmt->bind_param("idddd",
            $producto_id_interno,
            $datos_precios['precio_1'],
            $datos_precios['precio_especial'], 
            $datos_precios['precio_descuento'],
            $datos_precios['precio_lista']
        );

        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }


    private function actualizaPrecio(int $producto_id_interno, array $datos_precios)
    {
        $sql = "UPDATE precios_productos SET
                precio1 = ?, precio_especial = ?, precio_descuento = ?, precio_lista = ? 
                WHERE producto_id = ?";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar la actualización de precios: " . $this->db->error);
            return false;
        }

        $stmt->bind_param("ddddi",
            $datos_precios['precio_1'],
            $datos_precios['precio_especial'], 
            $datos_precios['precio_descuento'],
            $datos_precios['precio_lista'],
            $producto_id_interno
        );

        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }


    // ----------------------------------------------------------------------------------
    // === INVENTARIO MINIMO ===
    // ----------------------------------------------------------------------------------


    public function obtenerInventarioMinId(int $producto_id_interno)
    {
        $sql = "SELECT id FROM inventario_mini WHERE producto_id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $producto_id_interno);
        $stmt->execute();
        $result = $stmt->get_result();
        $precio = $result->fetch_assoc();
        $stmt->close();

        return $precio['id_invmin'] ?? null;
    }

    public function insertaInventarioMin(int $producto_id_interno, array $dato_invmin)
    {
        // Inserta inventario minimo
          if (!isset($dato_invmin['proveedor_id']) || $dato_invmin['proveedor_id'] != 3) {
            return false;
        }

        // Revisar si ya existe
        $id_invmin_existente = $this->obtenerInventarioMinId($producto_id_interno);
        if ($id_invmin_existente) {
            return false; // Ya existe, no inserta de nuevo
        }

         return $this->insertaInvMin($producto_id_interno, 50);

    }

    private function insertaInvMin(int $producto_id_interno, int $inv_min)
    {

        $sql = "INSERT INTO inventario_mini (producto_id, inv_min) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log("Error al preparar inserción inventario mínimo: " . $this->db->error);
            return false;
        }

        $stmt->bind_param("ii", $producto_id_interno, $inv_min);
        $result = $stmt->execute();
        $stmt->close();
        return $result;        

    }

    /**
     * Muestra todos los productos de la tabla
     */
    public function obtenerTodosLosProductos()
    {
        $sql = "SELECT * FROM productos";
        $result = $this->db->query($sql);
        $productos =[];

        if($result -> num_rows > 0){
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
        }

        return $productos;      
    }


    // Lista de precios
    public function obtenerListaDePrecios()
    {
        $sql = "SELECT
                pp.fecha AS FECHA, pro.producto_id AS 'ID PRODUCTO', 
                pro.titulo AS  NOMBRE, pp.precio_descuento AS PRECIO 
                FROM precios_productos AS pp
                LEFT JOIN productos AS pro
                ON pp.producto_id = pro.id
                ORDER BY pp.fecha";
        $result = $this->db->query($sql);
        $precios =[];

        if($result -> num_rows > 0){
            while ($row = $result->fetch_assoc()) {
                $precios[] = $row;
            }
        }

        return $precios;      

    }

}
