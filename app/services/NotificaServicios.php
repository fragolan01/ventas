<?php

class NotificationService
{
    // --- CONFIGURACIÓN DE CORREO SMTP
    private $vempresa = "Fragolan Linking People";
    private $vemail = "actualizaciones@fragolan.com";
    private $recipient = "actualizaciones@fragolan.com"; 

    /**
     * 
     * @param string $itemId 
     * @param float $
     * @param float 
     * @return bool 
     */
    public function sendCostChangeNotification(
        string $itemId, 
        float $oldCost, 
        float $newCost,
        string $itemTitle //--- El titulo
    ): bool
    {
        // PREPARACIÓN DEL CONTENIDO
        $mailid = time() + 1;
        $tipoCambio = ($newCost > $oldCost) ? 'Incremento' : 'Decremento';
        
        // ASUNTO 
        $asunto = "Email de Actualizaciones de " . $this->vempresa . " (Mail ID: " . $mailid . ")";
        
        // Mensaje
        $mensaje = "El sistema realizó las siguientes Actualizaciones:<br><br>";

        // actualización específica ---
        $mensaje .= "
            <strong>ALERTA DE CAMBIO DE COSTO DE ENVÍO</strong><br>
            El ítem **{$itemId}** ha experimentado un **{$tipoCambio}** en su costo de envío:<br><br>
            <table border='1' cellpadding='10' cellspacing='0'>
                <tr>
                    <td><strong>Publicacion:</strong></td>
                    <td>" . htmlspecialchars($itemTitle) . "</td> </tr>
                <tr>
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
        // Destinatario
        $to = $this->recipient; 
        
        $elhtml = $mensaje; // Contenido HTML
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . $from . "\r\n"; 
        $headers .= "Reply-To: " . $from . "\r\n";

        //  REAL USANDO LA FUNCIÓN NATIVA
        $mailSuccess = mail($to, $asunto, $elhtml, $headers);

        if (!$mailSuccess) {
            // errores log
            error_log("NotificationService ERROR: Fallo al enviar correo de cambio de costo para ítem {$itemId} usando mail().");
        } else {
            error_log("NotificationService INFO: Correo de cambio de costo para ítem {$itemId} enviado con éxito a {$to} usando mail().");
        }
        
        return $mailSuccess;
    }
}