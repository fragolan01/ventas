<?php

require_once '../app/models/TiendaModel.php';

class TiendasController
{
    public function index()
    {
        // 1. Instanciar el modelo de tiendas
        $tiendaModel = new TiendaModel();
        
        // 2. Obtener los datos de las tiendas desde el modelo
        $tiendas = $tiendaModel->getTiendas();

        // 3. Pasar los datos a la vista y cargarla
        require_once '../app/views/tiendas/index.php';
    }
}