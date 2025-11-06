<h1>Lista Item publicados en MELI</h1>

    <table class="table table-striped mt-4">
      <thead>
        <tr>
          <th scope="col">Estado</th>
          <th scope="col">Item_id</th>
          <th scope="col">Titulo</th>
          
          <th scope="col">Category id</th>
          <th scope="col">Precio</th>
          <th scope="col">Cantidad inicial</th>
          <th scope="col">Cantidad Disponible</th>
          <th scope="col">Cantidad vendida</th>
          <th scope="col">Tipo de lista</th>
          <th scope="col">Link</th>
          <th scope="col">Garantia</th>
          <th scope="col">Categorias</th>
          <th scope="col">Tipo Envio</th>
          <th scope="col">Creado</th>

        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <th scope="row"><?php echo $item['estado']; ?></th> 
                <th scope="row"><?php echo $item['item_id']; ?></th> 
                <td><?php echo $item['title']; ?></td>
                
                <td><?php echo $item['category_id']; ?></td>
                <td><?php echo $item['price']; ?></td>
                <td><?php echo $item['initial_quantity']; ?></td>
                <td><?php echo $item['available_quantity']; ?></td> <td><?php echo $item['sold_quantity']; ?></td>
                <td><?php echo $item['listing_type_id']; ?></td>
                <td>
                    <a href="<?php echo htmlspecialchars($item['permalink']); ?>" target="_blank">Publicación</a>
                </td>
                <td><?php echo $item['warranty']; ?></td> 
                
                <td><?php echo $item['domain_id']; ?></td>
                <td><?php echo $item['shipping']; ?></td>
                <td><?php echo $item['date_created']; ?></td>
            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
