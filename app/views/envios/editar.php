<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Editar Envio</h1>

    <form action="/envios/update" method="POST">
        <input type="hidden" name="id" value="<?php echo $envio['id']; ?>">
        <div class="mb-3">
            <label for="nombre_envio" class="form-label">Nombre del envio</label>
            <input type="text" class="form-control" id="nombre_envio" name="nombre_envio" value="<?php echo htmlspecialchars($envio['nombre_envio']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="costo" class="form-label">costo</label>
            <input type="text" class="form-control" id="costo" name="costo" value="<?php echo htmlspecialchars($envio['costo']); ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="/canales" class="btn btn-secondary">Cancelar</a>
    </form>

<?php require_once '../app/views/shared/footer.php'; ?>