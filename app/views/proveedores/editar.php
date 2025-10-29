<?php require_once '../app/views/shared/header.php'; ?>

    <h1>Editar Proveedor</h1>

    <form action="/proveedores/update" method="POST">
        <input type="hidden" name="id" value="<?php echo $proveedor['id']; ?>">
        <div class="mb-3">
            <label for="nombre_proveedor" class="form-label">Nombre del Proveedor</label>
            <input type="text" class="form-control" id="nombre_proveedor" name="nombre_proveedor" value="<?php echo htmlspecialchars($proveedor['nombre_proveedor']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="nombre_proveedor" class="form-label">Inventario minimo</label>
            <input type="text" class="form-control" id="inv_min" name="inv_min" value="<?php echo htmlspecialchars($proveedor['inv_min']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="tc" class="form-label">tipo de Cambio</label>
            <input type="num" class="form-control" id="tc" name="tc" value="<?php echo htmlspecialchars($proveedor['tc']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="tc" class="form-label">giro de la empresa</label>
            <input type="text" class="form-control" id="giro" name="giro" value="<?php echo htmlspecialchars($proveedor['giro']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="estado_switch" class="form-label">Estado</label>

            <div class="form-check form-switch">
                
                <input type="hidden" name="estado" value="0">

                <input
                    class="form-check-input"
                    type="checkbox"
                    role="switch"
                    id="estado_switch"
                    name="estado"
                    value="1"
                    <?php echo (isset($proveedor['estado']) && $proveedor['estado'] == 1) ? 'checked' : ''; ?>
                >

                <label class="form-check-label" for="estado_switch">
                    <?php
                        // Muestra el texto "Activo" o "Inactivo" basado en el valor de la base de datos
                        // echo (isset($proveedor['estado']) && $proveedor['estado'] == 1) ? 1 : 0;
                    ?>
                </label>
            </div>
        </div>


        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="/proveedores" class="btn btn-secondary">Cancelar</a>
    </form>

<?php require_once '../app/views/shared/footer.php'; ?>