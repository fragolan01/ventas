<?php
// Estratategia o metodo que ayuda a definir cual es metodos que las estrategias concretas deben implementar
// En este caso es para definir que formulario o vista elegir al cargar los prodoctos de un proveedor 
// La Estrategia es Ingresar los producrtos que surte un proveedor.

interface ProveedorImportadorInterface {
    // Recibimos una variable $data tipo array que contiene los datos (productos a ingresar)
    // que retorna un arreglo de datos
    public function importarProductos(array $data): array;
}

// con esta interfaz de importar productos es ir a cada una de las estrategias concretas e iplementar esta intefaz que tiene el importar productos