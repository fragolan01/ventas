<h1>Lista de Precios</h1>

    <table class="table table-striped mt-4">
    <thead>
        <tr>
            <th scope="col">FECHA</th>
            <th scope="col">ID PRODUCTO</th>
            <th scope="col">NOMBRE</th>
            <th scope="col">PRECIO</th>
            <th scope="col">IMV MIN</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($precios as $precio): ?>
            <tr>
                <th scope="row"><?php echo $precio['FECHA']; ?></th> 
                <td><?php echo $precio['ID PRODUCTO']; ?></td>
                <td><?php echo $precio['NOMBRE']; ?></td>
                <td><?php echo $precio['PRECIO']; ?></td>
                <td><?php echo $precio['INVMIN']; ?></td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>