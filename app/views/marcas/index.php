<?php require_once '../app/views/shared/header.php'; ?>

    <h1>marcas de Venta</h1>
    <a href="/marcas/crear" class="btn btn-primary mb-3">Agregar Nueva Marca</a>

    <table class="table table-striped mt-4">
      <thead>
        <tr>
          <th scope="col">id</th>
          <th scope="col">Proveedor</th>
          <th scope="col">Marca</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($marcas as $marca): ?>
            <tr>
                <th scope="row"><?php echo $marca['id']; ?></th>
                <td><?php echo $marca['proveedor_id']; ?></td>
                <td><?php echo $marca['nombre_marca']; ?></td>
                <td>
                    <a href="/marcas/editar/<?php echo $marca['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                     <a href="/marcas/eliminar/<?php echo $marca['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar esta Marca?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

<?php require_once '../app/views/shared/footer.php'; ?>