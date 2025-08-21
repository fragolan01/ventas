<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Crear Nuevo envio</h1>
<form action="/envios/store" method="POST">

    <small class="form-text text-muted">Tipo de Cambio Actual: <?php echo htmlspecialchars($tipoDeCambio); ?></small>

    <div class="mb-3">
        <label for="nombre_envio" class="form-label">Nombre del envio</label>
        <input type="text" class="form-control" id="nombre_envio" name="nombre_envio" required>
    </div>
    <div class="mb-3">
        <label for="costo" class="form-label">costo</label>
        <input type="number" class="form-control" id="costo" name="costo" step="0.01" required>
    </div>
    <div class="mb-3">
        <label for="moneda_id" class="form-label">Moneda</label>
        <input type="number" step="1" class="form-control" id="moneda_id" name="moneda_id" required>
    </div>

    <button type="submit" class="btn btn-success">Guardar envio</button>
    <a href="/envios" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once '../app/views/shared/footer.php'; ?>