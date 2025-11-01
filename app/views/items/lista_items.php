<h1>Lista Item publicados en MELI</h1>

    <table class="table table-striped mt-4">
      <thead>
        <tr>

          <th scope="col">item_id</th>
          <th scope="col">title</th>
          <th scope="col">family_id</th>
          <th scope="col">category_id</th>
          <th scope="col">price</th>
          <th scope="col">initial_quantity</th>
          <th scope="col">aviable_quantity</th>
          <th scope="col">sold_quantity</th>
          <th scope="col">listing_type_id</th>
          <th scope="col">permalink</th>
          <th scope="col">waranty</th>
          <th scope="col">catalog_product_id</th>
          <th scope="col">domain_id</th>
          <th scope="col">chanels</th>

        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <th scope="row"><?php echo $item['item_id ']; ?></th>
                <td><?php echo $item['title']; ?></td>
                <td><?php echo $item['family_id']; ?></td>
                <td><?php echo $item['category_id']; ?></td>
                <td><?php echo $item['price']; ?></td>
                <td><?php echo $item['initial_quantity']; ?></td>
                <td><?php echo $item['available_quantity']; ?></td>
                <td><?php echo $item['sold_quantity']; ?></td>
                <td><?php echo $item['listing_type_id']; ?></td>
                <td><?php echo $item['permalink']; ?></td>
                <td><?php echo $item['warranty']; ?></td>
                <td><?php echo $item['catalog_product_id']; ?></td>
                <td><?php echo $item['domain_id']; ?></td>
                <td><?php echo $item['channels']; ?></td>

            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
