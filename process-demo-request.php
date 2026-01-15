<?php
/**
 * FROSH Demo Request Form Handler
 * Processes demo requests with simple captcha validation
 */

session_start();
require_once 'lib/config.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método no permitido';
    echo json_encode($response);
    exit;
}

try {
    // Validate captcha
    $captchaAnswer = trim($_POST['captcha_answer'] ?? '');
    $captchaExpected = $_SESSION['captcha_answer'] ?? '';
    
    if (empty($captchaAnswer) || $captchaAnswer != $captchaExpected) {
        $response['message'] = 'Respuesta de seguridad incorrecta. Por favor, intenta de nuevo.';
        echo json_encode($response);
        exit;
    }
    
    // Validate required fields
    $nombre = trim($_POST['nombre'] ?? '');
    $empresa = trim($_POST['empresa'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $tipo_negocio = trim($_POST['tipo_negocio'] ?? '');
    $vehiculos_diarios = trim($_POST['vehiculos_diarios'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');
    
    if (empty($nombre) || empty($empresa) || empty($email) || empty($tipo_negocio) || empty($vehiculos_diarios)) {
        $response['message'] = 'Por favor, completa todos los campos requeridos.';
        echo json_encode($response);
        exit;
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Por favor, ingresa un correo electrónico válido.';
        echo json_encode($response);
        exit;
    }
    
    // Check if demo_requests table exists, create if not
    $tableCheck = $conn->query("SHOW TABLES LIKE 'demo_requests'");
    if ($tableCheck->num_rows == 0) {
        $createTable = "CREATE TABLE demo_requests (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nombre_completo VARCHAR(150) NOT NULL,
            empresa VARCHAR(150) NOT NULL,
            email VARCHAR(150) NOT NULL,
            telefono VARCHAR(50) NOT NULL,
            tipo_negocio VARCHAR(100) NOT NULL,
            vehiculos_diarios VARCHAR(50) NOT NULL,
            mensaje TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        if (!$conn->query($createTable)) {
            throw new Exception('Error al crear la tabla de solicitudes');
        }
    }
    
    // Insert demo request
    $stmt = $conn->prepare(
        "INSERT INTO demo_requests (nombre_completo, empresa, email, telefono, tipo_negocio, vehiculos_diarios, mensaje) 
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    
    $stmt->bind_param('sssssss', $nombre, $empresa, $email, $telefono, $tipo_negocio, $vehiculos_diarios, $mensaje);
    
    if ($stmt->execute()) {
        // Clear captcha session
        unset($_SESSION['captcha_answer']);
        
        // Send notification emails using template
        enviarCorreoDemo($nombre, $empresa, $email, $telefono, $tipo_negocio, $vehiculos_diarios, $mensaje);
        
        $response['success'] = true;
        $response['message'] = '¡Gracias por tu interés! Nos pondremos en contacto contigo en las próximas 24 horas para coordinar tu demo personalizada.';
    } else {
        throw new Exception('Error al guardar la solicitud');
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    $response['message'] = 'Ocurrió un error al procesar tu solicitud. Por favor, intenta de nuevo más tarde.';
    error_log('Demo request error: ' . $e->getMessage());
}

echo json_encode($response);

/**
 * Enviar correos de notificación de demo usando template HTML
 */
function enviarCorreoDemo($nombre, $empresa, $email, $telefono, $tipo_negocio, $vehiculos_diarios, $mensaje) {
    try {
        // Cargar template
        $templatePath = 'lib/templates/demo-request.htm';
        if (!file_exists($templatePath)) {
            error_log("Template de email no encontrado: {$templatePath}");
            return false;
        }
        
        $templateContent = file_get_contents($templatePath);
        
        // === EMAIL PARA EL CLIENTE ===
        $clienteContent = "
        <p>Estimado/a <strong>{$nombre}</strong>,</p>
        
        <div class='highlight-box'>
            <h3>✅ ¡Solicitud Recibida!</h3>
            <p>Hemos recibido tu solicitud de demo para <strong>{$empresa}</strong>.</p>
            <p>Nuestro equipo revisará tu información y se pondrá en contacto contigo en las próximas 24 horas para coordinar una demostración personalizada.</p>
        </div>
        
        <div class='info-box'>
            <p><strong>Resumen de tu solicitud:</strong></p>
            <p><strong>Empresa:</strong> {$empresa}</p>
            <p><strong>Tipo de Negocio:</strong> {$tipo_negocio}</p>
            <p><strong>Vehículos Diarios:</strong> {$vehiculos_diarios}</p>
            <p><strong>Teléfono:</strong> {$telefono}</p>
        </div>
        
        <p><strong>¿Qué sigue?</strong></p>
        <ul>
            <li>📞 Te contactaremos para agendar la demo</li>
            <li>💻 Te mostraremos cómo FROSH puede optimizar tu operación</li>
            <li>📊 Analizaremos tus necesidades específicas</li>
            <li>🎯 Te ayudaremos a elegir el plan ideal para tu negocio</li>
        </ul>
        
        <p style='margin-top: 25px;'>Mientras tanto, si tienes alguna pregunta urgente, no dudes en contactarnos:</p>
        <p>📧 <strong>froshsystems@gmail.com</strong><br>
        📱 <strong>+506 6395 7241</strong></p>
        
        <p style='margin-top: 25px;'>¡Gracias por tu interés en FROSH LavaCar App!</p>
        
        <p style='color: #64748b; font-size: 14px; margin-top: 30px;'>
            <em>Este es un correo automático de confirmación.</em>
        </p>";
        
        $clienteEmail = str_replace('[EMAIL_TITLE]', 'Solicitud de Demo Recibida', $templateContent);
        $clienteEmail = str_replace('[EMAIL_CONTENT]', $clienteContent, $clienteEmail);
        $clienteEmail = str_replace('[CURRENT_YEAR]', date('Y'), $clienteEmail);
        
        // Enviar al cliente
        require_once 'lib/handler.php';
        EnviarEmail(
            "Confirmación de Solicitud de Demo - FROSH LavaCar App",
            $clienteEmail,
            [$email, $nombre]
        );
        
        // === EMAIL PARA EL EQUIPO FROSH ===
        $adminContent = "
        <p><strong>🎉 Nueva Solicitud de Demo Recibida</strong></p>
        
        <div class='highlight-box'>
            <h3>Información del Prospecto</h3>
        </div>
        
        <div class='info-box'>
            <p><strong>Nombre:</strong> {$nombre}</p>
            <p><strong>Empresa:</strong> {$empresa}</p>
            <p><strong>Email:</strong> <a href='mailto:{$email}'>{$email}</a></p>
            <p><strong>Teléfono:</strong> <a href='tel:{$telefono}'>{$telefono}</a></p>
            <p><strong>Tipo de Negocio:</strong> {$tipo_negocio}</p>
            <p><strong>Vehículos Diarios:</strong> {$vehiculos_diarios}</p>
        </div>";
        
        if (!empty($mensaje)) {
            $adminContent .= "
            <div class='info-box' style='background-color: #fef3c7; border-left-color: #f59e0b;'>
                <p><strong>Mensaje del Cliente:</strong></p>
                <p style='font-style: italic;'>{$mensaje}</p>
            </div>";
        }
        
        $adminContent .= "
        <div class='info-box' style='background-color: #dbeafe; border-left-color: #3b82f6;'>
            <p><strong>📅 Fecha de Solicitud:</strong> " . date('d/m/Y H:i:s') . "</p>
        </div>
        
        <p style='margin-top: 25px;'><strong>Próximos Pasos:</strong></p>
        <ol>
            <li>Contactar al prospecto en las próximas 24 horas</li>
            <li>Agendar demo personalizada</li>
            <li>Preparar presentación según tipo de negocio</li>
            <li>Hacer seguimiento post-demo</li>
        </ol>
        
        <p style='text-align: center; margin-top: 30px;'>
            <a href='mailto:{$email}' class='button'>Responder al Cliente</a>
        </p>";
        
        $adminEmail = str_replace('[EMAIL_TITLE]', 'Nueva Solicitud de Demo', $templateContent);
        $adminEmail = str_replace('[EMAIL_CONTENT]', $adminContent, $adminEmail);
        $adminEmail = str_replace('[CURRENT_YEAR]', date('Y'), $adminEmail);
        
        // Enviar al equipo FROSH
        EnviarEmail(
            "🎯 Nueva Solicitud de Demo - {$empresa}",
            $adminEmail,
            ['froshsystems@gmail.com', 'Equipo FROSH']
        );
        
        // También enviar copia a email secundario
        EnviarEmail(
            "🎯 Nueva Solicitud de Demo - {$empresa}",
            $adminEmail,
            ['myinterpal@gmail.com', 'Administración']
        );
        
        return true;
        
    } catch (Exception $e) {
        error_log("Error enviando correo de demo: " . $e->getMessage());
        return false;
    }
}

