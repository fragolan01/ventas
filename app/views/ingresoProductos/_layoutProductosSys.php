<?php require_once '../app/views/shared/header.php'; ?>

<div class="container-fluid">
    <div class="row">

        <nav id="sidebarMenu" class="col-lg-2 d-md-block bg-light sidebar-personalizado collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <form action="/ventas/Syscom/importarProductos" method="POST" id="form-syscom-import">
                                <input type="hidden" name="proveedor_id" value="3">
                                <span onclick="document.getElementById('form-syscom-import').submit();" style="cursor:pointer;">
                                    Ingreso de Producto(s)
                                </span>
                            </form>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/ventas/Syscom/listaProductos">
                             Lista de productos
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/ventas/Syscom/listaDePrecios">
                             Lista de Precios
                        </a>
                    </li>

                </ul>
            </div>
        </nav>

        <main class="col-lg-10 px-md-4">
            <?php
            // Este es el espacio donde se cargará la vista específica
            // Solo carga la vista si la variable $view ha sido definida por el controlador
            if (isset($view)) {
                require_once $view;
            }
            ?>
        </main>
    </div>
</div>

<?php require_once '../app/views/shared/footer.php'; ?>