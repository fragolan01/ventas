<?php

class NotificationService
{
    // --- CONFIGURACIÓN DE CORREO SMTP (Se usan para el encabezado 'From') ---
    // NOTA: Estas variables ya no configuran el SMTP, solo el remitente.
    private $vempresa = "Fragolan Linking People";
    private $vemail = "actualizaciones@fragolan.com";
    private $recipient = "actualizaciones@fragolan.com"; // Asumimos que quieres enviarlo a la misma cuenta de 'actualizaciones'

    /**
     * Envía una notificación por correo electrónico sobre el cambio de costo de envío.
     * Utiliza la función mail() de PHP para aprovechar la configuración SMTP nativa del servidor.
     *
     * @param string $itemId ID del ítem que cambió.
     * @param float $oldCost Costo de envío anterior.
     * @param float $newCost Costo de envío actual (nuevo).
     * @return bool True si el email fue enviado con éxito, False en caso contrario.
     */
    public function sendCostChangeNotification(
        string $itemId, 
        float $oldCost, 
        float $newCost
    ): bool
    {
        // --- 1. PREPARACIÓN DEL CONTENIDO DINÁMICO ---
        $mailid = time() + 1;
        $tipoCambio = ($newCost > $oldCost) ? 'Incremento' : 'Decremento';
        
        // --- ASUNTO ---
        $asunto = "Email de Actualizaciones de " . $this->vempresa . " (Mail ID: " . $mailid . ")";
        
        // --- CUERPO DEL MENSAJE (Tu formato base) ---
        $mensaje = "El sistema realizó las siguientes Actualizaciones:<br><br>";

        // --- Agregar la actualización específica ---
        $mensaje .= "
            <strong>ALERTA DE CAMBIO DE COSTO DE ENVÍO</strong><br>
            El ítem **{$itemId}** ha experimentado un **{$tipoCambio}** en su costo de envío:<br><br>
            <table border='1' cellpadding='10' cellspacing='0'>
                <tr>
                    <td><strong>ID del Ítem:</strong></td>
                    <td>{$itemId}</td>
                </tr>
                <tr>
                    <td><strong>Costo Anterior:</strong></td>
                    <td>\${$oldCost} MXN</td>
                </tr>
                <tr>
                    <td><strong>Costo Nuevo:</strong></td>
                    <td>\${$newCost} MXN</td>
                </tr>
                <tr>
                    <td><strong>Tipo de Cambio:</strong></td>
                    <td style='color: " . (($tipoCambio == 'Incremento') ? 'red' : 'green') . "'>
                        <strong>{$tipoCambio}</strong>
                    </td>
                </tr>
            </table>
            <br>
        ";
        
        // --- 2. PREPARACIÓN DE HEADERS ---
        $from = $this->vemail;
        $to = $this->recipient; // El destinatario final
        
        $elhtml = $mensaje; // Contenido HTML
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . $from . "\r\n"; 
        $headers .= "Reply-To: " . $from . "\r\n";

        // --- 3. ENVÍO REAL USANDO LA FUNCIÓN NATIVA mail() ---
        $mailSuccess = mail($to, $asunto, $elhtml, $headers);

        if (!$mailSuccess) {
            // Este log es importante para el cronjob si falla el envío
            error_log("NotificationService ERROR: Fallo al enviar correo de cambio de costo para ítem {$itemId} usando mail().");
        } else {
            error_log("NotificationService INFO: Correo de cambio de costo para ítem {$itemId} enviado con éxito a {$to} usando mail().");
        }
        
        return $mailSuccess;
    }
}