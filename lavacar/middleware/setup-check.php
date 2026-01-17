<?php
/**
 * Setup Check Middleware
 * Verifica si la configuración inicial está completa antes de permitir usar ciertas funciones
 */

function checkSetupCompletion($conn, $dbName, $redirectToWizard = true) {
    try {
        // Verificar configuración de empresa
        $empresa = CrearConsulta($conn, 
            "SELECT COUNT(*) as count FROM `{$dbName}`.`configuracion_empresa`", []
        );
        $empresaCount = $empresa ? $empresa->fetch_assoc()['count'] : 0;
        
        // Verificar servicios activos (usar tabla servicios existente)
        $servicios = CrearConsulta($conn, 
            "SELECT COUNT(*) as count FROM `{$dbName}`.`servicios`", []
        );
        $serviciosCount = $servicios ? $servicios->fetch_assoc()['count'] : 0;
        
        // Verificar precios configurados (usar tabla precios existente)
        $precios = CrearConsulta($conn, 
            "SELECT COUNT(*) as count FROM `{$dbName}`.`precios`", []
        );
        $preciosCount = $precios ? $precios->fetch_assoc()['count'] : 0;
        
        // Verificar si la configuración de usuarios está completa (usuarios se gestionan centralmente)
        $usuariosConfig = CrearConsulta($conn, 
            "SELECT valor FROM `{$dbName}`.`configuracion_sistema` WHERE clave = 'usuarios_configurados'", []
        );
        $usuariosCompleto = $usuariosConfig ? ($usuariosConfig->fetch_assoc()['valor'] ?? '0') === '1' : false;
        
        $setupStatus = [
            'empresa_configurada' => $empresaCount > 0,
            'servicios_configurados' => $serviciosCount > 0,
            'precios_configurados' => $preciosCount > 0,
            'usuarios_configurados' => $usuariosCompleto,
            'setup_completo' => false
        ];
        
        $setupStatus['setup_completo'] = $setupStatus['empresa_configurada'] && 
                                        $setupStatus['servicios_configurados'] && 
                                        $setupStatus['precios_configurados'] &&
                                        $setupStatus['usuarios_configurados'];
        
        // Si no está completo y se debe redirigir
        if (!$setupStatus['setup_completo'] && $redirectToWizard) {
            // Determinar a qué paso redirigir
            $step = 1;
            if ($setupStatus['empresa_configurada']) $step = 2;
            if ($setupStatus['servicios_configurados']) $step = 3;
            if ($setupStatus['precios_configurados']) $step = 4;
            
            header("Location: setup-wizard.php?step={$step}&required=1");
            exit;
        }
        
        return $setupStatus;
        
    } catch (Exception $e) {
        error_log("Setup check error: " . $e->getMessage());
        return [
            'empresa_configurada' => false,
            'servicios_configurados' => false,
            'precios_configurados' => false,
            'usuarios_configurados' => false,
            'setup_completo' => false,
            'error' => $e->getMessage()
        ];
    }
}

function getSetupProgress($conn, $dbName) {
    $status = checkSetupCompletion($conn, $dbName, false);
    
    $steps = [
        1 => [
            'title' => 'Configuración de Empresa',
            'completed' => $status['empresa_configurada'],
            'description' => 'Información básica, horarios y configuración operativa'
        ],
        2 => [
            'title' => 'Servicios',
            'completed' => $status['servicios_configurados'],
            'description' => 'Servicios que ofreces en tu lavadero'
        ],
        3 => [
            'title' => 'Precios',
            'completed' => $status['precios_configurados'],
            'description' => 'Tarifas por servicio y tipo de vehículo'
        ],
        4 => [
            'title' => 'Usuarios',
            'completed' => $status['usuarios_configurados'] ?? true, // Siempre true porque se gestionan centralmente
            'description' => 'Configuración de notificaciones (usuarios se gestionan centralmente)'
        ]
    ];
    
    $completedSteps = array_sum(array_column($steps, 'completed'));
    $totalSteps = count($steps);
    $progress = ($completedSteps / $totalSteps) * 100;
    
    return [
        'steps' => $steps,
        'completed_steps' => $completedSteps,
        'total_steps' => $totalSteps,
        'progress' => $progress,
        'is_complete' => $status['setup_completo']
    ];
}

function showSetupAlert($conn, $dbName) {
    $progress = getSetupProgress($conn, $dbName);
    
    if (!$progress['is_complete']) {
        $nextStep = 1;
        foreach ($progress['steps'] as $stepNum => $step) {
            if (!$step['completed']) {
                $nextStep = $stepNum;
                break;
            }
        }
        
        echo "
        <div class='alert alert-warning alert-dismissible fade show setup-alert' role='alert'>
            <div class='d-flex align-items-center'>
                <div class='flex-grow-1'>
                    <h6 class='alert-heading mb-1'>
                        <i class='fas fa-exclamation-triangle me-2'></i>
                        Configuración Inicial Pendiente
                    </h6>
                    <p class='mb-2'>
                        Para crear órdenes necesitas completar la configuración inicial. 
                        Progreso: {$progress['completed_steps']}/{$progress['total_steps']} pasos completados.
                    </p>
                    <div class='progress mb-2' style='height: 6px;'>
                        <div class='progress-bar bg-warning' style='width: {$progress['progress']}%'></div>
                    </div>
                </div>
                <div class='ms-3'>
                    <a href='setup-wizard.php?step={$nextStep}' class='btn btn-warning btn-sm'>
                        <i class='fas fa-magic me-1'></i>Continuar Configuración
                    </a>
                </div>
            </div>
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
        </div>";
    }
}

function requireSetupCompletion($conn, $dbName, $feature = 'esta función') {
    $status = checkSetupCompletion($conn, $dbName, false);
    
    if (!$status['setup_completo']) {
        $missingSteps = [];
        if (!$status['empresa_configurada']) $missingSteps[] = 'Configuración de Empresa';
        if (!$status['servicios_configurados']) $missingSteps[] = 'Servicios';
        if (!$status['precios_configurados']) $missingSteps[] = 'Precios';
        if (!($status['usuarios_configurados'] ?? true)) $missingSteps[] = 'Configuración de Usuarios';
        
        $message = "Para usar {$feature} necesitas completar: " . implode(', ', $missingSteps);
        
        // Si es una petición AJAX
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $message,
                'setup_required' => true,
                'redirect_url' => 'setup-wizard.php?step=1&required=1'
            ]);
            exit;
        } else {
            // Redirigir a wizard
            header("Location: setup-wizard.php?step=1&required=1&feature=" . urlencode($feature));
            exit;
        }
    }
    
    return true;
}
?>