<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Panel de Control de Productos</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/productos/crear" class="btn btn-sm btn-success">
            <i class="fas fa-plus-circle"></i> Crear Nuevo Producto
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        Lista de Productos
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-sm">
                <thead class="bg-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Título</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Condición</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($productos)): ?>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <th scope="row"><?php echo htmlspecialchars($producto['id']); ?></th>
                                <td><?php echo htmlspecialchars($producto['title']); ?></td>
                                <td><?php echo htmlspecialchars($producto['category_id']); ?></td>
                                <td><?php echo htmlspecialchars($producto['price']); ?></td>
                                <td><?php echo htmlspecialchars($producto['available_quantity']); ?></td>
                                <td><?php echo htmlspecialchars($producto['conditions']); ?></td>
                                <td>
                                    <span class="badge 
                                        <?php if ($producto['status'] == 'activo') echo 'bg-success'; ?>
                                        <?php if ($producto['status'] == 'pausado') echo 'bg-warning text-dark'; ?>
                                        <?php if ($producto['status'] == 'inactivo') echo 'bg-danger'; ?>">
                                        <?php echo htmlspecialchars(ucfirst($producto['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/ventas/productos/editar/<?php echo htmlspecialchars($producto['id']); ?>" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/ventas/productos/eliminar/<?php echo htmlspecialchars($producto['id']); ?>" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    <a href="#" class="btn btn-info btn-sm text-white" title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay productos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>