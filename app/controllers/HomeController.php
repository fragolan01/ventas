<?php

class HomeController
{
    public function index()
    {
        // Esta línea es lo que "llama" a la vista
        require_once '../app/views/home/index.php';
    }
}