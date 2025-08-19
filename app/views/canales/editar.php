<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Editar Canal</h1>

    <form action="/canalestiendas/update" method="POST">
        <input type="hidden" name="id" value="<?php echo $tienda['id']; ?>">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Tienda</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($tienda['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="canal" class="form-label">Canal</label>
            <textarea class="form-control" id="canal_id" name="canal_id" rows="3" required><?php echo htmlspecialchars($tienda['canal_id']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="/canales" class="btn btn-secondary">Cancelar</a>
    </form>

<?php require_once '../app/views/shared/footer.php'; ?>