<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Tipos de Publicacion</h1>
    <a href="/publicacion/crear" class="btn btn-primary mb-3">Agregar Nueva Publicacion</a>

    <table class="table table-striped mt-4">
      <thead>
        <tr>
          <th scope="col">id</th>
          <th scope="col">Tipo Publicacion</th>
          <th scope="col">Nombre</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($Publicaciones as $publicacion): ?>
            <tr>
                <th scope="row"><?php echo $publicacion['id']; ?></th>
                <td><?php echo $publicacion['tipo_publi_id']; ?></td>
                <td><?php echo $publicacion['name']; ?></td>
                <td><?php echo $publicacion['canal_id']; ?></td>
                <td>
                    <a href="/publicacion/editar/<?php echo $publicacion['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                     <a href="/publicacion/eliminar/<?php echo $publicacion['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar esta publiccacion?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

<?php require_once '../app/views/shared/footer.php'; ?>