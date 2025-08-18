<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Gestión de Tiendas</h1>
    <a href="/tiendas/crear" class="btn btn-primary mb-3">Agregar Nueva Tienda</a>

    <table class="table table-striped mt-4">
      <thead>
        <tr>
          <th scope="col">id</th>
          <th scope="col">Nombre</th>
          <th scope="col">Canales de ventas</th>
          <th scope="col">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tiendas as $tienda): ?>
            <tr>
                <th scope="row"><?php echo $tienda['id']; ?></th>
                <td><?php echo $tienda['nombre']; ?></td>
                <td><?php echo $tienda['canal_id']; ?></td>
                <td>
                    <a href="http://localhost/ventas/tiendas/editar/<?php echo $tienda['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                     <a href="http://localhost/ventas/tiendas/eliminar/<?php echo $tienda['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar esta tienda?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

<?php require_once '../app/views/shared/footer.php'; ?>