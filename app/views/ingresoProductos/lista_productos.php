<h1>Lista de productos</h1>

    <table class="table table-striped mt-4">
      <thead>
        <tr>

          <th scope="col">proveedor_id</th>
          <th scope="col">modelo</th>
          <th scope="col">total_existencia</th>
          <th scope="col">titulo</th>
          <th scope="col">marca</th>
          <th scope="col">link_privado</th>
          <th scope="col">descricion</th>
          <th scope="col">caracteristicas</th>
          <th scope="col">imagenes</th>
          <th scope="col">peso</th>
          <th scope="col">alto</th>
          <th scope="col">largo</th>
          <th scope="col">ancho</th>

        </tr>
      </thead>
      <tbody>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <th scope="row"><?php echo $producto['proveedor_id']; ?></th>
                <td><?php echo $producto['modelo']; ?></td>
                <td><?php echo $producto['total_existencia']; ?></td>
                <td><?php echo $producto['titulo']; ?></td>
                <td><?php echo $producto['marca']; ?></td>

                <td>
                    <a href="<?php echo $producto['link_privado']; ?>" target="Descripcion producto">
                        <?php echo "ver producto"; ?>
                    </a>
                </td>


                <td><?php echo substr(strip_tags($producto['descripcion']),0,250); ?></td>
                <td><?php echo $producto['caracteristicas']; ?></td>
                <td><?php echo $producto['imagens']; ?></td>
                <td><?php echo $producto['peso']; ?></td>
                <td><?php echo $producto['alto']; ?></td>
                <td><?php echo $producto['largo']; ?></td>
                <td><?php echo $producto['ancho']; ?></td>


            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
