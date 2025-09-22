<?php

// FormularioImportar.php Esta es una estrategia concreta donde se cargan DESDE EL FORMULARIO para ingresar los productos que no sean del proveedor SYSCOM


require_once 'ProveedorImportadorInterface.php';

class FormularioImportador implements ProveedorImportadorInterface {
    
    // Traemos el metodo de la estrategia !!!!
    public function importarProductos(array $data): array {
        // Lógica para guardar los productos desde los datos del formulario

        return [];
    }
}