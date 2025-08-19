<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Crear Nuevo Canal</h1>

   <form action="/canales/store" method="POST">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre del Canal</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="mb-3">
        <label for="canal" class="form-label">Descripcion</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
    </div>
    <div class="mb-3">
        <label for="logo_url" class="form-label">Logo</label>
        <input type="text" class="form-control" id="logo_url" name="logo_url" required>
    </div>

    <div class="mb-3">
        <label for="api_base_url" class="form-label">Sitio Web</label>
        <input type="text" class="form-control" id="api_base_url" name="api_base_url" required>
    </div>

     <div class="mb-3">
        <label for="api_base_url" class="form-label">Estado</label>
        <input type="text" class="form-control" id="activo" name="activo" required>
    </div>

    <button type="submit" class="btn btn-success">Guardar Canal</button>
    <a href="/ventas/canales" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once '../app/views/shared/footer.php'; ?>