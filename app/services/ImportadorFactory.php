<?php

// require_once '../app/services/FormularioImportador.php';

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