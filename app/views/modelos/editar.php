<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Editar Modelo</h1>

    <form action="/modelos/update" method="POST">
        <input type="hidden" name="id" value="<?php echo $modelo['id']; ?>">
        <div class="mb-3">
            <label for="modelo" class="form-label">Nombre del modelo</label>
            <input type="text" class="form-control" id="modelo" name="modelo" value="<?php echo htmlspecialchars($modelo['modelo']); ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="/modelos" class="btn btn-secondary">Cancelar</a>
    </form>

<?php require_once '../app/views/shared/footer.php'; ?>