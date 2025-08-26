<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Crear Nueva Publicacion</h1>

   <form action="/publicacion/store" method="POST">
    <div class="mb-3">
        <label for="tipo_publi_id" class="form-label">tipo de Publicacion</label>
        <input type="text" class="form-control" id="tipo_publi_id" name="tipo_publi_id" required>
    </div>
    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <textarea class="form-control" id="name" name="name" rows="3" required></textarea>
    </div>
    <div class="mb-3">
        <label for="canal_id" class="form-label">Canal Id</label>
        <input type="number" class="form-control" id="canal_id" name="canal_id" required>
    </div>

    <button type="submit" class="btn btn-success">Guardar Publicacion</button>
    <a href="/publicacion" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once '../app/views/shared/footer.php'; ?>