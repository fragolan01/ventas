<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Canales de Venta</h1>
    <a href="/canales/crear" class="btn btn-primary mb-3">Agregar Nuevo Canal</a>

    <table class="table table-striped mt-4">
      <thead>
        <tr>
          <th scope="col">id</th>
          <th scope="col">Nombre</th>
          <th scope="col">Descripcion</th>
          <th scope="col">Logo</th>
          <th scope="col">Sitio</th>
          <th scope="col">Estado</th>
          <th scope="col">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($canales as $canal): ?>
            <tr>
                <th scope="row"><?php echo $canal['id']; ?></th>
                <td><?php echo $canal['nombre']; ?></td>
                <td><?php echo $canal['descripcion']; ?></td>
                <td><?php echo $canal['logo_url']; ?></td>
                <td><?php echo $canal['api_base_url']; ?></td>
                <td><?php echo $canal['activo']; ?></td>               
                <td>
                    <a href="/canales/editar/<?php echo $canal['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                     <a href="/canales/eliminar/<?php echo $canal['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar esta tienda?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

<?php require_once '../app/views/shared/footer.php'; ?>