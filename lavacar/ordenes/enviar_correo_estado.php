<?php
function enviarCorreoEstado(int $ordenId, int $nuevoEstado): bool
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
        
        // Verificar que el cliente tenga email
        if (empty($orden['ClienteCorreo'])) {
            error_log("Cliente sin email para orden #{$ordenId}");
            return false;
        }
        
        $servicios = $ordenManager->getServicios($ordenId);
        $totales = $ordenManager->getTotals($ordenId);
        
        // Cargar template
        $templatePath = 'lavacar/lib/templates/orden.htm';
        if (!file_exists($templatePath)) {
            throw new Exception("Template de email no encontrado");
        }
        
        $templateContent = file_get_contents($templatePath);
        
        // Preparar contenido del email según el estado
        $emailContent = generarContenidoEstado($orden, $servicios, $totales, $nuevoEstado);
        
        // Obtener información del estado
        $estadoInfo = getEstadoInfo($nuevoEstado);
        
        // Reemplazar variables en el template
        $templateContent = str_replace('[COMPANY]', $user['company']['name'] ?? 'FROSH', $templateContent);
        $templateContent = str_replace('[ORDER_NUMBER]', "Orden #{$ordenId} - {$estadoInfo['texto']}", $templateContent);
        $templateContent = str_replace('[EMAIL_CONTENT]', $emailContent, $templateContent);
        $templateContent = str_replace('[CURRENT_YEAR]', date('Y'), $templateContent);
        
        // Enviar email
        $subject = "Actualizacion de Orden #{$ordenId} - {$estadoInfo['texto']} - " . ($user['company']['name'] ?? 'FROSH');
        $result = EnviarEmail($subject, $templateContent, [$orden['ClienteCorreo'], $orden['ClienteNombre']]);
        
        // También enviar copia a la empresa
        EnviarEmail($subject, $templateContent, ["myinterpal@gmail.com", "Administración"]);
        
        return true;
        
    } catch (Exception $e) {
        error_log("Error enviando correo de estado: " . $e->getMessage());
        return false;
    }
}

function generarContenidoEstado(array $orden, array $servicios, array $totales, int $estado): string
{
    $fechaOrden = date('d/m/Y H:i', strtotime($orden['FechaIngreso']));
    $estadoInfo = getEstadoInfo($estado);
    
    $contenido = "
    <p>Estimado/a <strong>{$orden['ClienteNombre']}</strong>,</p>
    
    <p>Le informamos que su orden de servicio ha sido actualizada:</p>
    
    <div class='info-box' style='background-color: {$estadoInfo['bg_color']}; border-left: 4px solid {$estadoInfo['border_color']};'>
        <h3 style='color: {$estadoInfo['text_color']}; margin: 0 0 10px;'>
            <i class='fa-solid {$estadoInfo['icono']}'></i> {$estadoInfo['titulo']}
        </h3>
        <p style='margin: 0; color: {$estadoInfo['text_color']};'>{$estadoInfo['descripcion']}</p>
    </div>
    
    <div class='info-box'>
        <p><strong>Número de Orden:</strong> #{$orden['ID']}</p>
        <p><strong>Fecha de Orden:</strong> {$fechaOrden}</p>
        <p><strong>Vehículo:</strong> {$orden['Placa']} - {$orden['Marca']} {$orden['Modelo']} ({$orden['Year']})</p>
        <p><strong>Estado Actual:</strong> <span style='color: {$estadoInfo['text_color']}; font-weight: 600;'>{$estadoInfo['texto']}</span></p>
    </div>";
    
    // Agregar información específica según el estado
    switch ($estado) {
        case 2: // En Proceso
            $contenido .= "
            <div class='info-box' style='background-color: #dbeafe; border-left: 4px solid #3b82f6;'>
                <p><strong>¿Qué significa esto?</strong></p>
                <p>Su vehículo ya está siendo atendido por nuestro equipo técnico. Nos pondremos en contacto con usted cuando el servicio esté completado.</p>
            </div>";
            break;
            
        case 3: // Terminado
            $contenido .= "
            <div class='info-box' style='background-color: #d1fae5; border-left: 4px solid #10b981;'>
                <p><strong>¡Su vehículo está listo!</strong></p>
                <p>El servicio ha sido completado exitosamente. Puede pasar a recoger su vehículo en nuestras instalaciones.</p>
                <!-- <p><strong>Horario de atención:</strong> Lunes a Viernes de 8:00 AM a 6:00 PM, Sábados de 8:00 AM a 4:00 PM</p> -->
            </div>";
            break;
            
        case 4: // Cerrado
            $contenido .= "
            <div class='info-box' style='background-color: #f3f4f6; border-left: 4px solid #6b7280;'>
                <p><strong>Orden Finalizada</strong></p>
                <p>Su orden ha sido cerrada exitosamente. Gracias por confiar en nuestros servicios.</p>
            </div>";
            break;
    }
    
    // Mostrar resumen de servicios solo si no es estado cerrado
    if ($estado != 4) {
        $contenido .= "
        <h3 style='color: #1e40af; margin: 25px 0 15px;'>Servicios:</h3>
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
        
        $total = number_format($totales['total'], 2);
        $contenido .= "
            </tbody>
            <tfoot>
                <tr style='background-color: #f0fdf4;'>
                    <td style='padding: 12px; font-weight: 700; font-size: 16px; color: #166534;'>Total:</td>
                    <td style='padding: 12px; text-align: right; font-weight: 700; font-size: 16px; color: #166534;'>₡{$total}</td>
                </tr>
            </tfoot>
        </table>";
    }
    
    if (!empty($orden['Observaciones'])) {
        $contenido .= "
        <div class='info-box' style='background-color: #fef3c7; border-left: 4px solid #f59e0b;'>
            <p><strong>Observaciones:</strong></p>
            <p>{$orden['Observaciones']}</p>
        </div>";
    }
    
    $contenido .= "
    <p style='margin-top: 25px;'>Si tiene alguna pregunta, no dude en contactarnos.</p>
    
    <p>Gracias por confiar en nosotros.</p>
    
    <p style='color: #64748b; font-size: 14px; margin-top: 30px;'>
        <em>Este es un correo automático, por favor no responda a esta dirección.</em>
    </p>";
    
    return $contenido;
}

function getEstadoInfo(int $estado): array
{
    $estados = [
        1 => [
            'texto' => 'Pendiente',
            'titulo' => 'Orden Recibida',
            'descripcion' => 'Su orden está en cola para ser procesada.',
            'icono' => 'fa-clock',
            'bg_color' => '#fef3c7',
            'border_color' => '#f59e0b',
            'text_color' => '#92400e'
        ],
        2 => [
            'texto' => 'En Proceso',
            'titulo' => 'Servicio en Progreso',
            'descripcion' => 'Su vehículo está siendo atendido por nuestro equipo.',
            'icono' => 'fa-gear',
            'bg_color' => '#dbeafe',
            'border_color' => '#3b82f6',
            'text_color' => '#1e40af'
        ],
        3 => [
            'texto' => 'Terminado',
            'titulo' => '¡Servicio Completado!',
            'descripcion' => 'Su vehículo está listo para ser retirado.',
            'icono' => 'fa-check-circle',
            'bg_color' => '#d1fae5',
            'border_color' => '#10b981',
            'text_color' => '#065f46'
        ],
        4 => [
            'texto' => 'Cerrado',
            'titulo' => 'Orden Finalizada',
            'descripcion' => 'La orden ha sido completada y cerrada.',
            'icono' => 'fa-lock',
            'bg_color' => '#f3f4f6',
            'border_color' => '#6b7280',
            'text_color' => '#374151'
        ]
    ];
    
    return $estados[$estado] ?? $estados[1];
}
?>