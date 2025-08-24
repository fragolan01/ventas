<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Editar Publicacion</h1>

    <form action="/publicacion/update" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($tipoPublicacion['id']); ?>">
        <div class="mb-3">
            <label for="tipoPubliId" class="form-label">tipo de publicacion</label>
            <input type="text" class="form-control" id="tipoPubliId" name="tipoPubliId" value="<?php echo htmlspecialchars($tipoPublicacion['tipo_publi_id']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($tipoPublicacion['name']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="canal_id" class="form-label">Canal</label>
            <input type="number" class="form-control" id="canal_id" name="canal_id" value="<?php echo htmlspecialchars($tipoPublicacion['canal_id']); ?>" required>
        </div>

        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="/publicacion" class="btn btn-secondary">Cancelar</a>
    </form>

<?php require_once '../app/views/shared/footer.php'; ?>