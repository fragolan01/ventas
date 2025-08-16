<?php

class TiendasController
{
    public function index()
    {
        // En un futuro, aquí se llamará al modelo para obtener las tiendas de la base de datos.
        // Por ahora, solo cargaremos la vista.
        require_once '../app/views/tiendas/index.php';
    }
}