<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Crear Nueva Marca</h1>

   <form action="/marcas/store" method="POST">
    <div class="mb-3">
        <label for="proveedor_id" class="form-label">Proveedor</label>
        <input type="text" class="form-control" id="proveedor_id" name="proveedor_id" required>
    </div>
    <div class="mb-3">
        <label for="nombre_marca" class="form-label">Marca</label>
        <textarea class="form-control" id="nombre_marca" name="nombre_marca" rows="3" required></textarea>
    </div>

    <button type="submit" class="btn btn-success">Guardar Marca</button>
    <a href="/ventas/marcas" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once '../app/views/shared/footer.php'; ?>