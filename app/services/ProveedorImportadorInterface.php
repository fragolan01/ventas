<?php

interface ProveedorImportadorInterface {
    public function importarProductos(array $data): array;
}