<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Lista de Modelos</h1>
    <a href="/modelos/crear" class="btn btn-primary mb-3">Agregar Nuevo Modelo</a>

    <table class="table table-striped mt-4">
      <thead>
        <tr>
          <th scope="col">id</th>
          <th scope="col">Modelo</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($modelos as $modelo): ?>
            <tr>
                <th scope="row"><?php echo $modelo['id']; ?></th>
                <td><?php echo $modelo['modelo']; ?></td>
                <td>
                    <a href="/modelos/editar/<?php echo $modelo['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                     <a href="/modelos/eliminar/<?php echo $modelo['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar este modelo?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

<?php require_once '../app/views/shared/footer.php'; ?>