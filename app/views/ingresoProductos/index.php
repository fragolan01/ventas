<?php require_once '../app/views/shared/header.php'; ?>

<main class="container mt-5">

    <?php 
    // Muestra el div de la fila solo si hay proveedores.
    if (isset($proveedores) && is_array($proveedores) && !empty($proveedores)): 
    ?>
    <h3><center>Selecciona el proveedor</h3></center>
    <br>
    <br>
    <div class="row text-center">

        <?php foreach ($proveedores as $proveedor): ?>
            <div class="col-lg-4">
                <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Proveedor: <?php echo htmlspecialchars($proveedor['nombre_proveedor']); ?>" preserveAspectRatio="xMidYMid slice" focusable="false">
                    <title><?php echo htmlspecialchars($proveedor['nombre_proveedor']); ?></title>
                    <rect width="100%" height="100%" fill="#777"/>
                    <text x="50%" y="50%" fill="#fff" dy=".3em">
                        <?php echo htmlspecialchars($proveedor['nombre_proveedor']); ?>
                    </text>
                </svg>

                <h2 class="mt-3"><?php echo htmlspecialchars($proveedor['id']); ?></h2>
                <p>Importa el catálogo de productos.</p>
                
                <form action="/Syscom/importarProductos" method="POST">
                    <input type="hidden" name="proveedor_id" value="<?php echo htmlspecialchars($proveedor['id']); ?>">
                    <button type="submit" class="btn btn-primary mt-2">
                        <i class="fas fa-download me-2"></i> Iniciar Importación &raquo;
                    </button>
                </form>
            </div><?php endforeach; ?>

    </div><?php else: ?>
    
    <div class="row">
        <div class="col-12 text-center">
            <p class="alert alert-info">No se encontraron proveedores disponibles.</p>
        </div>
    </div>
    
    <?php endif; ?>

</main>

<?php require_once '../app/views/shared/footer.php'; ?>