<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Gestión de Tiendas</h1>
    <a href="http://localhost/ventas/tiendas/crear" class="btn btn-primary mb-3">Agregar Nueva Tienda</a>

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
                    <a href="#" class="btn btn-primary btn-sm">Editar</a>
                    <a href="#" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

<?php require_once '../app/views/shared/footer.php'; ?>