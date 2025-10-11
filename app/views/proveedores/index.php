<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Lista de proveedores</h1>
    <a href="/proveedores/crear" class="btn btn-primary mb-3">Agregar Nuevo Proveedor</a>

    <table class="table table-striped mt-4">
      <thead>
        <tr>
          <th scope="col">id</th>
          <th scope="col">Nombre</th>
          <th scope="col">Inventario Minimo</th>
          <th scope="col">tipo de Cambio</th>
          <th scope="col">Giro</th>
          <th scope="col">estado</th>
          
          <th scope="col">creado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($proveedores as $proveedor): ?>
            <tr>
                <th scope="row"><?php echo $proveedor['id']; ?></th>
                <td><?php echo $proveedor['nombre_proveedor']; ?></td>
                <td><?php echo $proveedor['inv_min']; ?></td>
                <td><?php echo $proveedor['tc']; ?></td>
                <td><?php echo $proveedor['giro']; ?></td>
                <td><?php echo $proveedor['estado']; ?></td>

                <td><?php echo $proveedor['creado']; ?></td>
                <td>
                    <a href="/proveedores/editar/<?php echo $proveedor['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                     <a href="/proveedores/eliminar/<?php echo $proveedor['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar este proveedor?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

<?php require_once '../app/views/shared/footer.php'; ?>