<div class="container mt-5">
    <h1>Importar ITEMS Mercado Libres</h1>
    <p>Ingresa uno o varios ITEMSs, separados por comas, para importarlos a la base de datos.</p>

    <form action="/Items/procesarImportacionMeli" method="POST">
        <div class="mb-3">
            <label for="item_ids" class="form-label">ITEMs de publicaciones:</label>
            <input 
                type="text"
                class="form-control" 
                name="item_ids"             id="item_ids"               placeholder="Ejemplo: MLM3764133956, MLM2361209353, MLM2336618827" 
            >
        </div>
        <button type="submit" class="btn btn-primary">Importar Items</button>
    </form>

    <?php if (!empty($resultados)): ?>
        <hr class="mt-5">
        <h2>Resultados de la Importación</h2>
        <ul class="list-group">
            <?php foreach ($resultados as $itemId => $resultado): ?> 
                <?php
                    // Las claves de $resultado en el Importador son 'success' y 'message'
                    // Debes adaptar la lógica de la vista:
                    $clase_alerta = ($resultado['success'] === true) ? 'list-group-item-success' : 'list-group-item-danger';
                ?>
                <li class="list-group-item d-flex justify-content-between align-items-center <?php echo $clase_alerta; ?>">
                    <div>
                        <strong>ID: <?php echo htmlspecialchars($itemId); ?></strong>
                        <br>
                        <span><?php echo htmlspecialchars($resultado['message']); ?></span>
                    </div>
                    <?php if ($resultado['success'] === true): ?>
                        <span class="badge bg-success">Éxito</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Error</span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>