<h1>Editar Producto</h1>

<form action="/ventas/productos/update" method="POST">

    <input type="hidden" name="id" value="<?php echo htmlspecialchars($producto['id']); ?>">
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Información General
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label">Título:</label>
                    <input type="text" class="form-control" name="title" id="title"  value="<?php echo htmlspecialchars($producto['title']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Categoría:</label>
                    <input type="text" class="form-control" name="category_id" id="category_id" value="<?php echo htmlspecialchars($producto['category_id']); ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="conditions" class="form-label">Condición:</label>
                    <select class="form-select" name="conditions" id="conditions" required>
                        <option value="new" <?php if ($producto['conditions'] == 'new') echo 'selected'; ?>>Nuevo</option>
                        <option value="used" <?php if ($producto['conditions'] == 'used') echo 'selected'; ?>>Usado</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="listing_type_id" class="form-label">Tipo de Publicación:</label>
                    <select class="form-select" name="listing_type_id" id="listing_type_id" required>
                        <option value="gold_special" <?php if ($producto['listing_type_id'] == 'gold_special') echo 'selected'; ?>>Oro Especial</option>
                        <option value="gold_premium" <?php if ($producto['listing_type_id'] == 'gold_premium') echo 'selected'; ?>>Oro Premium</option>
                        <option value="free" <?php if ($producto['listing_type_id'] == 'free') echo 'selected'; ?>>Gratis</option>
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
                    <input type="number" step="0.01" class="form-control" name="price" id="price" value="<?php echo htmlspecialchars($producto['price']); ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="currency_id" class="form-label">Moneda:</label>
                    <select class="form-select" name="currency_id" id="currency_id" required>
                         <option value="MXN" <?php if ($producto['currency_id'] == 'MXN') echo 'selected'; ?>>MXN</option>
                         <option value="USD" <?php if ($producto['currency_id'] == 'USD') echo 'selected'; ?>>USD</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="available_quantity" class="form-label">Cantidad disponible:</label>
                    <input type="number" class="form-control" name="available_quantity" id="available_quantity" value="<?php echo htmlspecialchars($producto['available_quantity']); ?>" required>
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
                    <input type="text" class="form-control" name="warranty_type" id="warranty_type"  value="<?php echo htmlspecialchars($producto['warranty_type']); ?>" required>

                   <!-- <select class="form-select" name="warranty_type" id="warranty_type">
                        <option value="2230280" <php /* if ($producto['warranty_type'] == '2230280') echo 'selected'; */?>>Fabricante</option>
                        <option value="2230280" <php /* if ($producto['warranty_type'] == '2230280') echo '2230280'; */?>>Vendedor</option>
                        <option value="" < /* php if ($producto['warranty_type'] == '') echo 'selected'; */ ?>>Sin garantía</option>
                    </select> -->
                </div>
                <div class="col-md-4 mb-3">
                    <label for="warranty_time" class="form-label">Tiempo de Garantía (meses):</label>
                    <input type="number" class="form-control" name="warranty_time" id="warranty_time" value="<?php echo htmlspecialchars($producto['warranty_time']); ?>">
                </div>
                 <div class="col-md-4 mb-3">
                    <label for="shipping_mode" class="form-label">Modo de Envío:</label>
                    <select class="form-select" name="shipping_mode" id="shipping_mode" required>
                        <option value="me1" <?php if ($producto['shipping_mode'] == 'me1') echo 'selected'; ?>>Mercado Envíos 1</option>
                        <option value="custom" <?php if ($producto['shipping_mode'] == 'custom') echo 'selected'; ?>>Personalizado</option>
                        <option value="not_specified" <?php if ($producto['shipping_mode'] == 'not_specified') echo 'selected'; ?>>No especificado</option>
                    </select>
                </div>
            </div>
             <div class="row">
                 <div class="col-md-4 mb-3">
                    <label for="shipping_free" class="form-label">Envío Gratis:</label>
                    <select class="form-select" name="shipping_free" id="shipping_free" required>
                        <option value="1" <?php if ($producto['shipping_free'] == '1') echo 'selected'; ?>>Sí</option>
                        <option value="0" <?php if ($producto['shipping_free'] == '0') echo 'selected'; ?>>No</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="buying_mode" class="form-label">Modo de Compra:</label>
                    <select class="form-select" name="buying_mode" id="buying_mode" required>
                        <option value="buy_it_now" <?php if ($producto['buying_mode'] == 'buy_it_now') echo 'selected'; ?>>Compra Inmediata</option>
                        <option value="auction" <?php if ($producto['buying_mode'] == 'auction') echo 'selected'; ?>>Subasta</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">Estado:</label>
                    <select class="form-select" name="status" id="status" required>
                        <option value="activo" <?php if ($producto['status'] == 'activo') echo 'selected'; ?>>Activo</option>
                        <option value="pausado" <?php if ($producto['status'] == 'pausado') echo 'selected'; ?>>Pausado</option>
                        <option value="inactivo" <?php if ($producto['status'] == 'inactivo') echo 'selected'; ?>>Inactivo</option>
                        <option value="error" <?php if ($producto['status'] == 'error') echo 'selected'; ?>>Error</option>
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
                <textarea class="form-control" name="description" id="description" rows="3"><?php echo htmlspecialchars($producto['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="pictures" class="form-label">Fotos (URLs separadas por coma):</label>
                <textarea class="form-control" name="pictures" id="pictures" rows="2"><?php echo htmlspecialchars($producto['pictures']); ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="attributes" class="form-label">Atributos (JSON o clave=valor):</label>
                     <textarea class="form-control" name="attributes" id="attributes" rows="2"><?php echo htmlspecialchars($producto['attributes']); ?></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="product_id" class="form-label">Product ID (Catálogo ML):</label>
                    <input type="text" class="form-control" name="product_id" id="product_id" value="<?php echo htmlspecialchars($producto['product_id']); ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button type="submit" class="btn btn-success me-md-2">Guardar Producto</button>
        <a href="/ventas/productos" class="btn btn-secondary">Cancelar</a>
    </div>
</form>