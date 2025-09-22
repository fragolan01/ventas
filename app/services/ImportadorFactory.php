<?php

// require_once '../app/services/FormularioImportador.php';
// En Importar Factory se importan las estrategias concretas y en donde se elige la estrategia concreta para cargar los datos del proveedor
// ImportadorFactory.php se requiere en SyscomController.php (Contexto) para que delegue la tarea a la estratagia concreta elegida.

require_once 'SyscomImportador.php';
require_once 'FormularioImportador.php';


class ImportadorFactory {
    public static function getImportador(int $proveedorId): ProveedorImportadorInterface {
        switch ($proveedorId) {
            case 3: // ID para Syscom
                return new SyscomImportador();
            default:
                return new FormularioImportador();
        }
    }
}