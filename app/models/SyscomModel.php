<?php
require_once 'Model.php';

class SyscomModel extends Model
{
    /**
     * Inserta un producto o lo actualiza si ya existe en la tabla `productos`.
     * * @param array $data Los datos del producto a insertar/actualizar.
     * @return bool Verdadero si la operación fue exitosa, falso en caso contrario.
     */
    public function insertaOActualizaProducto($data)
    {
        // 1. Verificar si el producto ya existe en la BD
        $siExiste = $this->obtenerProductoId($data['producto_id']);

        if ($siExiste) {
            // 2. Si es verdadero, lo actualiza
            return $this->actualizaProducto($data);
        } else {
            // 3. Si no existe, lo inserta
            return $this->insertaProducto($data);
        }
    }

    /**
     * Obtiene un producto de la tabla productos por su ID de producto.
     *
     * @param int $producto_id
     * @return array|null
     */
    public function obtenerProductoId($producto_id)
    {
        $sql = "SELECT * FROM productos WHERE producto_id = ?";
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
        return $producto;
    }

    /**
     * Inserta un nuevo producto en la tabla productos.
     */
    private function insertaProducto($data)
    {
        $sql = "INSERT INTO productos
                 (producto_id, modelo, total_existencia, titulo, marca, imagen, link_privado, descripcion, caracteristicas, peso, alto, largo, ancho) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar la consulta de inserción: " . $this->db->error);
            return false;
        }

        if (is_array($data['caracteristicas'])) {
            $data['caracteristicas'] = json_encode($data['caracteristicas'], JSON_UNESCAPED_UNICODE);
        }
      
        $stmt->bind_param("isssssssssddd",
            $data['producto_id'], 
            $data['modelo'],
            $data['total_existencia'],
            $data['titulo'],
            $data['marca'],
            $data['imagen'],
            $data['link_privado'],
            $data['descripcion'],
            $data['caracteristicas'],
            $data['peso'],
            $data['alto'],
            $data['largo'],
            $data['ancho'] 
        );
      
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Actualiza un producto existente.
     */
    private function actualizaProducto($data)
    {
        $sql = "UPDATE productos SET
            modelo = ?, total_existencia = ?, titulo = ?, marca = ?, imagen = ?, link_privado = ?, descripcion = ?, caracteristicas = ?, peso = ?, alto = ?, largo = ?, ancho = ?
            WHERE producto_id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Ocurrió un error en la actualización: " . $this->db->error);
            return false;
        }
        
        if (is_array($data['caracteristicas'])) {
            $data['caracteristicas'] = json_encode($data['caracteristicas'], JSON_UNESCAPED_UNICODE);
        }

        $stmt->bind_param("ssssssssddddi",
            $data['modelo'],
            $data['total_existencia'],
            $data['titulo'],
            $data['marca'],
            $data['imagen'],
            $data['link_privado'],
            $data['descripcion'],
            $data['caracteristicas'],
            $data['peso'],
            $data['alto'],
            $data['largo'],
            $data['ancho'],
            $data['tienda_id']        );

        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }


    /**
     * Se obtiene uno o varios productos de la tabla productos
     * * @param array $producto_ids array de id's 
     * @return array Un array de productos encontrados
     */
    public function obtenerProductosPorIds(array $producto_ids)
    {
        // Convertir los productos a enteros para evitar inyección SQL
        $sanitized_ids = array_map('intval', $producto_ids);

        // Retornar array vacío en caso de no tener IDs 
        if (empty($sanitized_ids)) {
            return [];
        }

        // Crear placeholders para los IDs
        $placeholders = implode(',', array_fill(0, count($sanitized_ids), '?'));

        $sql = "SELECT * FROM productos WHERE producto_id IN ($placeholders)";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            error_log("Error al preparar la consulta de obtenerProductosPorIds: " . $this->db->error);
            return [];
        }

        // Crear string de tipos para bind_param
        $types = str_repeat('i', count($sanitized_ids));

        // Vínculo de parámetros
        $params = array_merge([$types], $sanitized_ids);
        call_user_func_array([$stmt, 'bind_param'], $this->refValues($params));

        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }

        $stmt->close();
        
        return $productos;
    }

    /**
     * Parámetros por referencia
     */
    private function refValues($arr)
    {
        if (strnatcmp(phpversion(),'5.3') >= 0) {
            $refs = array();
            foreach($arr as $key => $value) {
                $refs[$key] = &$arr[$key];
            }
            return $refs;
        }
        return $arr;
    }
}
?>