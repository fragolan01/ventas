<?php

require_once 'ProveedorImportadorInterface.php';

class FormularioImportador implements ProveedorImportadorInterface {
    public function importarProductos(array $data): array {
        // Lógica para guardar los productos desde los datos del formulario

        return [];
    }
}