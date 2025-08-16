<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Crear Nueva Tienda</h1>

   <form action="http://localhost/ventas/tiendas/store" method="POST">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre de la Tienda</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="mb-3">
        <label for="canal" class="form-label">Canal</label>
        <textarea class="form-control" id="canal" name="canal" rows="3" required></textarea>
    </div>
    <button type="submit" class="btn btn-success">Guardar Tienda</button>
    <a href="http://localhost/ventas/tiendas" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once '../app/views/shared/footer.php'; ?>