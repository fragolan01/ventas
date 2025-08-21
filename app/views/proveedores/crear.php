<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Crear Nuevo Proveedor</h1>

   <form action="/proveedores/store" method="POST">
    <div class="mb-3">
        <label for="nombre_proveedor" class="form-label">Nombre del Proveedor</label>
        <input type="text" class="form-control" id="nombre_proveedor" name="nombre_proveedor" required>
    </div>

    <button type="submit" class="btn btn-success">Guardar Proveedor</button>
    <a href="/ventas/proveedores" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once '../app/views/shared/footer.php'; ?>