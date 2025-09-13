<?php require_once '../app/views/shared/header.php'; ?>

<main class="container mt-5">

    <div class="row text-center">

        <div class="col-lg-4">
            <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 140x140" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Syscom</title><rect width="100%" height="100%" fill="#777"/><text x="50%" y="50%" fill="#fff" dy=".3em">Syscom</text></svg>

            <h2 class="mt-3">Importar de Syscom</h2>
            <p>Importa el catálogo completo de productos del proveedor Syscom para su posterior gestión y publicación.</p>
            
            <form action="/syscom/importar" method="POST">
                <button type="submit" class="btn btn-primary mt-2">
                    <i class="fas fa-download me-2"></i> Iniciar Importación &raquo;
                </button>
            </form>
        </div><div class="col-lg-4">
            <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 140x140" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Proveedor B</title><rect width="100%" height="100%" fill="#777"/><text x="50%" y="50%" fill="#fff" dy=".3em">Proveedor B</text></svg>

            <h2 class="mt-3">Importar de Proveedor B</h2>
            <p>Aún no implementado. Próximamente podrás importar productos de otros proveedores.</p>
            <p><a class="btn btn-secondary mt-2 disabled" href="#" aria-disabled="true">En desarrollo &raquo;</a></p>
        </div><div class="col-lg-4">
            <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 140x140" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Proveedor C</title><rect width="100%" height="100%" fill="#777"/><text x="50%" y="50%" fill="#fff" dy=".3em">Proveedor C</text></svg>

            <h2 class="mt-3">Importar de Proveedor C</h2>
            <p>Sección reservada para futuras integraciones con otros catálogos de proveedores.</p>
            <p><a class="btn btn-secondary mt-2 disabled" href="#" aria-disabled="true">En desarrollo &raquo;</a></p>
        </div></div><hr class="featurette-divider">

</main>

<?php require_once '../app/views/shared/footer.php'; ?>