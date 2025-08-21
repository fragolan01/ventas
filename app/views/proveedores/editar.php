<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Editar Proveedor</h1>

    <form action="/proveedores/update" method="POST">
        <input type="hidden" name="id" value="<?php echo $proveedor['id']; ?>">
        <div class="mb-3">
            <label for="nombre_proveedor" class="form-label">Nombre del Proveedor</label>
            <input type="text" class="form-control" id="nombre_proveedor" name="nombre_proveedor" value="<?php echo htmlspecialchars($proveedor['nombre_proveedor']); ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="/proveedores" class="btn btn-secondary">Cancelar</a>
    </form>

<?php require_once '../app/views/shared/footer.php'; ?>