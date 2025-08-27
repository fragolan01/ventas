<?php require_once '../app/views/shared/header.php'; ?>

<div class="container-fluid">
    <div class="row">

        <nav id="sidebarMenu" class="col-lg-2 d-md-block bg-light sidebar-personalizado collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/ventas/productos">
                            Lista de Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/ventas/productos/crear">
                            Registro de Productos
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-lg-10 px-md-4">
            <?php
            // Este es el espacio donde se cargará la vista específica
            if (isset($view)) {
                require_once $view;
            }
            ?>
        </main>
    </div>
</div>

<?php require_once '../app/views/shared/footer.php'; ?>