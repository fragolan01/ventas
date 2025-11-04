<h1>Lista Item publicados en MELI</h1>

    <table class="table table-striped mt-4">
      <thead>
        <tr>

          <th scope="col">item_id</th>--
          <th scope="col">titulo</th>
          <th scope="col">family id</th>
          <th scope="col">category id</th>
          <th scope="col">Precio</th>
          <th scope="col">Cantidad inicial</th>
          <th scope="col">Cantidad Disponible</th>
          <th scope="col">Cantidad vendida</th>
          <th scope="col">Tipo de lista</th>
          <th scope="col">link</th>
          <th scope="col">garantia</th>
          <th scope="col">Catalogo Prod.</th>
          <th scope="col">Dominio</th>
          <th scope="col">Canales</th>

        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <th scope="row"><?php echo $item['item_id']; ?></th> 
                <td><?php echo $item['title']; ?></td>
                <td><?php echo $item['family_id']; ?></td>
                <td><?php echo $item['category_id']; ?></td>
                <td><?php echo $item['price']; ?></td>
                <td><?php echo $item['initial_quantity']; ?></td>
                <td><?php echo $item['available_quantity']; ?></td> <td><?php echo $item['sold_quantity']; ?></td>
                <td><?php echo $item['listing_type_id']; ?></td>

                <td>
                    <a href="<?php echo htmlspecialchars($item['permalink']); ?>" target="_blank">Publicación</a>
                </td>

                <td><?php echo $item['warranty']; ?></td> <td><?php echo $item['catalog_product_id']; ?></td>
                <td><?php echo $item['domain_id']; ?></td>
                <td><?php echo $item['channels']; ?></td>
            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
