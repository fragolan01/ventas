<?php

require_once '../app/models/ProveedorModel.php';

class IngresoProductosController
{

    public function __construct()
    {
        $this->ProveedorModel = new ProveedorModel();
    }

    public function index()
    {
        // Obtener todod los proveedores del modelo proveedores
        $proveedores = $this->ProveedorModel->getProveedores();

        // Los datos que pasan a la vista
        $data = ['provedores' => $proveedores];

        // Se carga lza vista y los datos
        require_once '../app/views/ingresoProductos/index.php';
    }


}