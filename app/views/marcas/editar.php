<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Editar Marca</h1>

    <form action="/marcas/update" method="POST">
        <input type="hidden" name="id" value="<?php echo $marca['id']; ?>">
        <div class="mb-3">
            <label for="nombre" class="form-label">Proveedor</label>
            <input type="text" class="form-control" id="proveedor_id" name="proveedor_id" value="<?php echo htmlspecialchars($marca['proveedor_id']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="marca" class="form-label">Marca</label>
            <textarea class="form-control" id="nombre_marca" name="nombre_marca" rows="3" required><?php echo htmlspecialchars($marca['nombre_marca']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="/marcas" class="btn btn-secondary">Cancelar</a>
    </form>

<?php require_once '../app/views/shared/footer.php'; ?>