<div class="container mt-5">
    <h1>Crear Producto</h1>

    <form action="/ventas/ingresoProductos/store" method="POST">

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Información del Producto (Tabla `productos`)
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="modelo" class="form-label">Modelo:</label>
                        <input type="text" class="form-control" name="modelo" id="modelo" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="titulo" class="form-label">Título:</label>
                        <input type="text" class="form-control" name="titulo" id="titulo" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="marca" class="form-label">Marca:</label>
                        <input type="text" class="form-control" name="marca" id="marca" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="link_privado" class="form-label">Link Privado:</label>
                        <input type="url" class="form-control" name="link_privado" id="link_privado">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea class="form-control" name="descripcion" id="descripcion" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="caracteristicas" class="form-label">Características:</label>
                    <textarea class="form-control" name="caracteristicas" id="caracteristicas" rows="2"></textarea>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                Información de Precios (Tabla `precios_productos`)
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="precio1" class="form-label">Precio 1:</label>
                        <input type="number" step="0.01" class="form-control" name="precio1" id="precio1" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                Información de Inventario (Tabla `inventario_mini`)
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="total_existencia" class="form-label">Total Existencia:</label>
                        <input type="number" class="form-control" name="total_existencia" id="total_existencia" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="peso" class="form-label">Peso (kg):</label>
                        <input type="number" step="0.01" class="form-control" name="peso" id="peso">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="alto" class="form-label">Alto (cm):</label>
                        <input type="number" step="0.01" class="form-control" name="alto" id="alto">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="largo" class="form-label">Largo (cm):</label>
                        <input type="number" step="0.01" class="form-control" name="largo" id="largo">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="ancho" class="form-label">Ancho (cm):</label>
                        <input type="number" step="0.01" class="form-control" name="ancho" id="ancho">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                Imágenes
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="imagenes" class="form-label">URLs de Imágenes (separadas por coma):</label>
                    <textarea class="form-control" name="imagenes" id="imagenes" rows="2"></textarea>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-success me-md-2">Guardar Producto</button>
            <a href="/ventas/ingresoProductos" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>