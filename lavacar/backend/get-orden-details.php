<?php
session_start();
require_once '../../lib/config.php';
require_once 'lib/Auth.php';

// Verificar autenticación
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

// Verificar que se envió el ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID de orden inválido']);
    exit;
}

$ordenId = (int)$_GET['id'];
$user = userInfo();
$dbName = $user['company']['db'];

try {
    require_once 'OrdenManager.php';
    $ordenManager = new OrdenManager($conn, $dbName);
    $orden = $ordenManager->find($ordenId);
    
    if (!$orden) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Orden no encontrada']);
        exit;
    }
    
    // Procesar servicios para mostrar en HTML
    $serviciosHtml = '';
    if (!empty($orden['Servicios'])) {
        $serviciosData = json_decode($orden['Servicios'], true);
        if (is_array($serviciosData)) {
            $serviciosHtml = '<div class="row">';
            foreach ($serviciosData as $servicio) {
                if (isset($servicio['nombre'])) {
                    $precio = isset($servicio['precio']) ? '₡' . number_format($servicio['precio'], 0, ',', '.') : 'Sin precio';
                    $serviciosHtml .= '
                        <div class="col-md-6 mb-2">
                            <div class="card border-primary border-1">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><i class="fa-solid fa-wrench me-1"></i>' . htmlspecialchars($servicio['nombre']) . '</span>
                                        <strong class="text-primary">' . $precio . '</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ';
                }
            }
            $serviciosHtml .= '</div>';
        }
    }
    
    if (empty($serviciosHtml)) {
        $serviciosHtml = '<p class="text-muted"><i class="fa-solid fa-info-circle me-1"></i>Sin servicios especificados</p>';
    }
    
    $orden['servicios_html'] = $serviciosHtml;
    
    echo json_encode([
        'success' => true,
        'orden' => $orden
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor: ' . $e->getMessage()
    ]);
}
?>