<h1>Crear Producto</h1>

<form action="/productos/store" method="POST">

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Información General
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label">Título:</label>
                    <input type="text" class="form-control" name="title" id="title" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Categoría:</label>
                    <input type="text" class="form-control" name="category_id" id="category_id" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="conditions" class="form-label">Condición:</label>
                    <select class="form-select" name="conditions" id="conditions" required>
                        <option value="new">Nuevo</option>
                        <option value="used">Usado</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="listing_type_id" class="form-label">Tipo de Publicación:</label>
                    <select class="form-select" name="listing_type_id" id="listing_type_id" required>
                        <option value="gold_special">Oro Especial</option>
                        <option value="gold_premium">Oro Premium</option>
                        <option value="free">Gratis</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            Precio y Cantidad
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="price" class="form-label">Precio:</label>
                    <input type="number" step="0.01" class="form-control" name="price" id="price" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="currency_id" class="form-label">Moneda:</label>
                    <select class="form-select" name="currency_id" id="currency_id" required>
                        <option value="MXN">MXN</option>
                        <option value="USD">USD</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="available_quantity" class="form-label">Cantidad disponible:</label>
                    <input type="number" class="form-control" name="available_quantity" id="available_quantity" required>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-warning text-white">
            Garantía y Envío
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="warranty_type" class="form-label">Tipo de Garantía:</label>
                    <input type="number" step="0.01" class="form-control" name="warranty_type" id="warranty_type" required>


                </div>
                <div class="col-md-4 mb-3">
                    <label for="warranty_time" class="form-label">Tiempo de Garantía (meses):</label>
                    <input type="number" class="form-control" name="warranty_time" id="warranty_time" value="12">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="shipping_mode" class="form-label">Modo de Envío:</label>
                    <select class="form-select" name="shipping_mode" id="shipping_mode" required>
                        <option value="me1">Mercado Envíos 1</option>
                        <option value="custom">Personalizado</option>
                        <option value="not_specified">No especificado</option>
                    </select>
                </div>
            </div>
            <div class="row">
                 <div class="col-md-4 mb-3">
                    <label for="shipping_free" class="form-label">Envío Gratis:</label>
                    <select class="form-select" name="shipping_free" id="shipping_free" required>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="buying_mode" class="form-label">Modo de Compra:</label>
                    <select class="form-select" name="buying_mode" id="buying_mode" required>
                        <option value="buy_it_now">Compra Inmediata</option>
                        <option value="auction">Subasta</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">Estado:</label>
                    <select class="form-select" name="status" id="status" required>
                        <option value="activo">Activo</option>
                        <option value="pausado">Pausado</option>
                        <option value="inactivo">Inactivo</option>

                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            Contenido
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="description" class="form-label">Descripción:</label>
                <textarea class="form-control" name="description" id="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="pictures" class="form-label">Fotos (URLs separadas por coma):</label>
                <textarea class="form-control" name="pictures" id="pictures" rows="2"></textarea>
            </div>
            <div class="row">

                <div class="col-md-6 mb-3">
                    <label for="attributes" class="form-label">Atributos:</label>
                    <textarea class="form-control" name="attributes" id="attributes" rows="2"></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="brand" class="form-label">Marca:</label>
                    <textarea class="form-control" name="brand" id="brand" rows="2" required></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="model" class="form-label">Modelo:</label>
                    <textarea class="form-control" name="model" id="model" rows="2" required></textarea>
                </div>


                <div class="col-md-6 mb-3">
                    <label for="product_id" class="form-label">Product ID (Catálogo ML):</label>
                    <input type="text" class="form-control" name="product_id" id="product_id">
                </div>
            </div>
        </div>
    </div>

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button type="submit" class="btn btn-success me-md-2">Guardar Producto</button>
        <a href="/productos" class="btn btn-secondary">Cancelar</a>
    </div>
</form>