<?php require_once '../app/views/shared/header.php'; ?>

<div class="container mt-5">
    <h1>Importar Productos desde Syscom</h1>
    <p>Ingresa uno o varios IDs de productos, separados por comas, para importarlos a la base de datos.</p>

    <form action="/Syscom/importarProductos" method="POST">
        <div class="mb-3">
            <label for="producto_id" class="form-label">IDs de Producto Syscom:</label>
            <input 
                type="text"
                class="form-control" 
                name="producto_id" 
                id="producto_id" 
                placeholder="Ejemplo: 230246, 12345, 98765" 
                required 
                pattern="[0-9, ]+" 
                title="Solo se permiten números y comas."
            >
        </div>
        <button type="submit" class="btn btn-primary">Importar Productos</button>
    </form>

    <?php if (!empty($resultados)): ?>
        <hr class="mt-5">
        <h2>Resultados de la Importación</h2>
        <ul class="list-group">
            <?php foreach ($resultados as $resultado): ?>
                <?php
                    $clase_alerta = ($resultado['estado'] === 'success') ? 'list-group-item-success' : 'list-group-item-danger';
                ?>
                <li class="list-group-item d-flex justify-content-between align-items-center <?php echo $clase_alerta; ?>">
                    <div>
                        <strong>ID: <?php echo htmlspecialchars($resultado['producto_id']); ?></strong>
                        <br>
                        <span><?php echo htmlspecialchars($resultado['mensaje']); ?></span>
                    </div>
                    <?php if ($resultado['estado'] === 'success'): ?>
                        <span class="badge bg-success">Éxito</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Error</span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?php require_once '../app/views/shared/footer.php'; ?>