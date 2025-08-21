<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Crear Nuevo Modelo</h1>

   <form action="/modelos/store" method="POST">
    <div class="mb-3">
        <label for="modelo" class="form-label">Nombre del Modelo</label>
        <input type="text" class="form-control" id="modelo" name="modelo" required>
    </div>

    <button type="submit" class="btn btn-success">Guardar Modelo</button>
    <a href="/ventas/modelos" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once '../app/views/shared/footer.php'; ?>