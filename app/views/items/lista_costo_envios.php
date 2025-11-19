<?php 
// CRÍTICO: Asegura que el array $data se desempaquete en variables como $lista_envios
if (isset($data) && is_array($data)) {
    extract($data); 
}

// Verifica si la variable $lista_envios existe (si no existe, se inicializa como un array vacío)
$lista_envios = $lista_envios ?? [];
?>

<div class="container mt-5">
    <h1>Costos Envío por Items</h1>

    <?php if (empty($lista_envios)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle-fill"></i> No hay registros de costos de envío para mostrar.
        </div>
    <?php else: ?>
        <h3>Total Items: <?php echo count($lista_envios); ?></h3>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ITEM ID</th>
                        <th>Precio Venta</th>
                        <th>Mode</th>
                        <th>Logistic Type</th>
                        <th>Costo Lista (ML)</th>
                        <th>Peso Facturable</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_envios as $envio): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($envio['item_id']); ?></td>
                        <td>$<?php echo number_format($envio['item_price'] ?? 0, 2); ?></td>
                        <td><?php echo htmlspecialchars($envio['mode']); ?></td>
                        <td><?php echo htmlspecialchars($envio['logistic_type']); ?></td>
                        <td>
                            <strong><?php echo number_format($envio['list_cost'] ?? 0, 2); ?></strong> 
                            <?php echo htmlspecialchars($envio['currency_id'] ?? 'N/A'); ?>
                        </td>
                        <td><?php echo number_format($envio['billable_weight'] ?? 0, 2); ?> kg</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="/ventas/Items/detalleDeEnvios" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Actualizar Costos de Envío
        </a>
    </div>
</div>