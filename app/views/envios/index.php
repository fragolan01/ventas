<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Envios</h1>
    <a href="/envios/crear" class="btn btn-primary mb-3">Agregar Nuevo Envio</a>

    <table class="table table-striped mt-4">
      <thead>
        <tr>
          <th scope="col">id</th>
          <th scope="col">Nombre</th>
          <th scope="col">Costo</th>
          <th scope="col">moneda</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($envios as $envio): ?>
            <tr>
                <th scope="row"><?php echo $envio['id']; ?></th>
                <td><?php echo $envio['nombre_envio']; ?></td>
                <td><?php echo $envio['costo']; ?></td>
                <td><?php echo $envio['moneda_id']; ?></td>
                <td>
                    <a href="/envios/editar/<?php echo $envio['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                     <a href="/envios/eliminar/<?php echo $envio['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar el envio?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

<?php require_once '../app/views/shared/footer.php'; ?>