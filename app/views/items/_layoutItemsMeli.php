<?php require_once '../app/views/shared/header.php'; ?>

<div class="container-fluid">
    <div class="row">

        <nav id="sidebarMenu" class="col-lg-2 d-md-block bg-light sidebar-personalizado collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    
                    <li class="nav-item">
                        <a class="nav-link" href="/ventas/Items/importarItems">
                            Ingreso de items(s)
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="/ventas/Items/listaItems">
                            Lista de Items
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/ventas/Items/detalleDeEnvios">
                            Inserta Costo Envios
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/ventas/Items/listaCostoEnvios">
                            LISTA COSTO
                        </a>
                    </li>

                </ul>
            </div>
        </nav>

        <main class="col-lg-10 px-md-4">
            <?php
            if (isset($viewContent)) {
                require_once $viewContent;
            }
            ?>
        </main>
    </div>
</div>

<?php require_once '../app/views/shared/footer.php'; ?>