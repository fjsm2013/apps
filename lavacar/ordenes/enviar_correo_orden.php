<?php
function enviarCorreoOrden(string $email, string $nombreCliente, int $ordenId): bool
{
    global $user;
    
    try {
        // Obtener datos de la orden
        $dbName = $user['company']['db'];
        require_once 'lavacar/backend/OrdenManager.php';
        $ordenManager = new OrdenManager($GLOBALS['conn'], $dbName);
        
        $orden = $ordenManager->find($ordenId);
        if (!$orden) {
            throw new Exception("Orden no encontrada");
        }
        
        $servicios = $ordenManager->getServicios($ordenId);
        $totales = $ordenManager->getTotals($ordenId);
        
        // Cargar template
        $templatePath = 'lavacar/lib/templates/orden.htm';
        if (!file_exists($templatePath)) {
            throw new Exception("Template de email no encontrado");
        }
        
        $templateContent = file_get_contents($templatePath);
        
        // Preparar contenido del email
        $emailContent = generarContenidoOrden($orden, $servicios, $totales);
        
        // Reemplazar variables en el template
        $templateContent = str_replace('[COMPANY]', $user['company']['name'] ?? 'FROSH', $templateContent);
        $templateContent = str_replace('[ORDER_NUMBER]', "Orden #{$ordenId}", $templateContent);
        $templateContent = str_replace('[EMAIL_CONTENT]', $emailContent, $templateContent);
        $templateContent = str_replace('[CURRENT_YEAR]', date('Y'), $templateContent);
        
        // Enviar email usando tu función
        $subject = "Confirmación de Orden #{$ordenId} - " . ($user['company']['name'] ?? 'FROSH');
        $result = EmailSenderDFT($subject, $templateContent, [$email, $nombreCliente]);
        
        return $result;
        
    } catch (Exception $e) {
        error_log("Error enviando correo de orden: " . $e->getMessage());
        return false;
    }
}

function generarContenidoOrden(array $orden, array $servicios, array $totales): string
{
    $fechaOrden = date('d/m/Y H:i', strtotime($orden['FechaIngreso']));
    
    $contenido = "
    <p>Estimado/a <strong>{$orden['ClienteNombre']}</strong>,</p>
    
    <p>Su orden de servicio ha sido registrada exitosamente. A continuación los detalles:</p>
    
    <div class='info-box'>
        <p><strong>Número de Orden:</strong> #{$orden['ID']}</p>
        <p><strong>Fecha:</strong> {$fechaOrden}</p>
        <p><strong>Vehículo:</strong> {$orden['Placa']} - {$orden['Marca']} {$orden['Modelo']} ({$orden['Year']})</p>
        <p><strong>Color:</strong> {$orden['Color']}</p>
        <p><strong>Categoría:</strong> {$orden['TipoVehiculo']}</p>
    </div>
    
    <h3 style='color: #1e40af; margin: 25px 0 15px;'>Servicios Solicitados:</h3>
    <table style='width: 100%; border-collapse: collapse; margin: 15px 0;'>
        <thead>
            <tr style='background-color: #f8fafc;'>
                <th style='padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0;'>Servicio</th>
                <th style='padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0;'>Precio</th>
            </tr>
        </thead>
        <tbody>";
    
    foreach ($servicios as $servicio) {
        $precio = number_format($servicio['precio'], 2);
        $contenido .= "
            <tr>
                <td style='padding: 10px; border-bottom: 1px solid #f1f5f9;'>{$servicio['nombre']}</td>
                <td style='padding: 10px; text-align: right; border-bottom: 1px solid #f1f5f9;'>₡{$precio}</td>
            </tr>";
    }
    
    $subtotal = number_format($totales['subtotal'], 2);
    $impuesto = number_format($totales['impuesto'], 2);
    $total = number_format($totales['total'], 2);
    
    $contenido .= "
        </tbody>
        <tfoot>
            <tr>
                <td style='padding: 10px; font-weight: 600; border-top: 2px solid #e2e8f0;'>Subtotal:</td>
                <td style='padding: 10px; text-align: right; font-weight: 600; border-top: 2px solid #e2e8f0;'>₡{$subtotal}</td>
            </tr>
            <tr>
                <td style='padding: 10px; font-weight: 600;'>IVA (13%):</td>
                <td style='padding: 10px; text-align: right; font-weight: 600;'>₡{$impuesto}</td>
            </tr>
            <tr style='background-color: #f0fdf4;'>
                <td style='padding: 12px; font-weight: 700; font-size: 16px; color: #166534;'>Total:</td>
                <td style='padding: 12px; text-align: right; font-weight: 700; font-size: 16px; color: #166534;'>₡{$total}</td>
            </tr>
        </tfoot>
    </table>";
    
    if (!empty($orden['Observaciones'])) {
        $contenido .= "
        <div class='info-box' style='background-color: #fef3c7; border-left: 4px solid #f59e0b;'>
            <p><strong>Observaciones:</strong></p>
            <p>{$orden['Observaciones']}</p>
        </div>";
    }
    
    $contenido .= "
    <p style='margin-top: 25px;'>Nos pondremos en contacto con usted para coordinar el servicio.</p>
    
    <p>Gracias por confiar en nosotros.</p>
    
    <p style='color: #64748b; font-size: 14px; margin-top: 30px;'>
        <em>Este es un correo automático, por favor no responda a esta dirección.</em>
    </p>";
    
    return $contenido;
}
?>
