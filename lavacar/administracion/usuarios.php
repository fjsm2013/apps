<?php
session_start();
require_once '../../lib/config.php';
require_once 'lib/Auth.php';

autoLoginFromCookie();

if (!isLoggedIn()) {
    header("Location: ../../login.php");
    exit;
}

$user = userInfo();
if (!$user) {
    logout();
    header("Location: ../../login.php");
    exit;
}

// Connect to MASTER database for user management
$link = new mysqli('localhost', 'fmorgan', '4sf7xnah', 'frosh_lavacar');

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

// Include UserManager
require_once 'lavacar/backend/UserManager.php';

// Create an instance of UserManager (using master database)
$userManager = new UserManager($link, 'frosh_lavacar');

$message = '';
$messageType = '';

// Handle form actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        switch ($action) {
            case 'agregar':
                $result = json_decode($userManager->createUser($_POST['name'], $_POST['email'], (int)$_POST['permisos']), true);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'error';
                if ($result['success'] && isset($result['temp_password'])) {
                    $message .= " - Contraseña temporal: " . $result['temp_password'];
                }
                break;
            case 'actualizar':
                $id = (int)base64_decode($_POST['ID']);
                $result = json_decode($userManager->updateUser($id, $_POST['name'], $_POST['email'], (int)$_POST['permisos']), true);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'error';
                break;
            case 'delete':
                $id = (int)base64_decode($_POST['ID']);
                $result = json_decode($userManager->deleteUser($id), true);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'error';
                break;
            case 'reset_password':
                $id = (int)base64_decode($_POST['ID']);
                $result = json_decode($userManager->resetPassword($id), true);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'error';
                if ($result['success'] && isset($result['temp_password'])) {
                    $message .= " - Nueva contraseña: " . $result['temp_password'];
                }
                break;
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = 'error';
    }
}

// Get all users for current company
$users = $userManager->getAllUsers();
$stats = $userManager->getUserStats();

// Include header
include 'lavacar/partials/header.php';
?>

<style>
    .users-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        text-align: center;
        border: 1px solid var(--frosh-gray-200, #e5e7eb);
        transition: all 0.2s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .stat-number {
        font-size: 2em;
        font-weight: bold;
        color: var(--report-warning, #D3AF37);
        margin-bottom: 8px;
    }
    
    .stat-card div:last-child {
        color: var(--frosh-gray-600, #4b5563);
        font-weight: 500;
        font-size: 0.9rem;
    }
    
    /* Empty state summary for mobile optimization */
    .empty-state-summary {
        margin-bottom: 20px;
    }
    
    .empty-summary-card {
        background: var(--frosh-gray-50, #f9fafb);
        border: 2px dashed var(--frosh-gray-300, #d1d5db);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        color: var(--frosh-gray-500, #6b7280);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-weight: 500;
    }
    
    .empty-summary-card i {
        font-size: 1.2rem;
        color: var(--frosh-gray-400, #9ca3af);
    }
    
    .users-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid var(--frosh-gray-200, #e5e7eb);
    }
    
    .table-header {
        background: var(--frosh-gray-50, #f9fafb);
        padding: 20px;
        border-bottom: 1px solid var(--frosh-gray-200, #e5e7eb);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .table-header h3 {
        color: var(--frosh-gray-900, #111827);
        font-weight: 700;
        margin: 0;
    }
    
    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    
    .btn-primary {
        background: var(--frosh-black, #000000);
        color: white;
        border-color: var(--frosh-black, #000000);
    }
    
    .btn-primary:hover {
        background: var(--frosh-dark, #1a1a1a);
        border-color: var(--frosh-dark, #1a1a1a);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .btn-secondary {
        background: var(--frosh-gray-600, #4b5563);
        color: white;
        border-color: var(--frosh-gray-600, #4b5563);
    }
    
    .btn-secondary:hover {
        background: var(--frosh-gray-700, #374151);
        border-color: var(--frosh-gray-700, #374151);
        transform: translateY(-1px);
    }
    
    .btn-danger {
        background: var(--report-danger, #ef4444);
        color: white;
        border-color: var(--report-danger, #ef4444);
    }
    
    .btn-danger:hover {
        background: #dc2626;
        border-color: #dc2626;
        transform: translateY(-1px);
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 500;
    }
    
    /* MOBILE FIRST - Cards View (Default) */
    .desktop-table-view {
        display: none;
    }
    
    .mobile-cards-view {
        display: block;
        padding: 15px;
    }
    
    .user-card {
        background: white;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid var(--frosh-gray-200, #e5e7eb);
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    
    .user-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border-color: var(--frosh-gray-300, #d1d5db);
        transform: translateY(-1px);
    }
    
    .user-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .user-info {
        flex: 1;
        min-width: 0;
    }
    
    .user-name {
        font-size: 16px;
        font-weight: 600;
        margin: 0 0 4px 0;
        color: var(--frosh-gray-900, #111827);
        word-wrap: break-word;
    }
    
    .user-email {
        font-size: 14px;
        color: var(--frosh-gray-600, #4b5563);
        margin: 0;
        word-wrap: break-word;
    }
    
    .user-card-body {
        margin-bottom: 15px;
    }
    
    .user-detail {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        border-bottom: 1px solid var(--frosh-gray-100, #f3f4f6);
        flex-wrap: wrap;
        gap: 5px;
    }
    
    .user-detail:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        font-weight: 500;
        color: var(--frosh-gray-700, #374151);
        font-size: 13px;
    }
    
    .detail-value {
        color: var(--frosh-gray-600, #4b5563);
        font-size: 13px;
        text-align: right;
        word-wrap: break-word;
    }
    
    .user-card-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-start;
    }
    
    .user-card-actions .btn {
        flex: 1;
        min-width: 80px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }
    
    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    
    .badge-admin {
        background: var(--report-warning, #D3AF37);
        color: white;
    }
    
    .badge-operator {
        background: var(--report-info, #274AB3);
        color: white;
    }
    
    .badge-user {
        background: var(--report-success, #10b981);
        color: white;
    }
    
    /* Desktop View - 768px and up */
    @media (min-width: 768px) {
        .desktop-table-view {
            display: block;
        }
        
        .mobile-cards-view {
            display: none;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--frosh-gray-200, #e5e7eb);
        }
        
        .table th {
            background: var(--frosh-gray-50, #f9fafb);
            font-weight: 600;
            color: var(--frosh-gray-700, #374151);
        }
        
        .table tbody tr:hover {
            background-color: var(--frosh-gray-50, #f9fafb);
        }
        
        .table tbody tr {
            transition: background-color 0.2s ease;
        }
        
        .badge {
            font-size: 12px;
        }
        
        .user-card-actions .btn {
            flex: none;
            min-width: auto;
        }
    }
    
    /* Tablet adjustments */
    @media (max-width: 767px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            padding: 12px;
        }
        
        .stat-number {
            font-size: 1.4em;
            margin-bottom: 4px;
        }
        
        .stat-card div:last-child {
            font-size: 0.8rem;
        }
        
        .empty-summary-card {
            padding: 15px;
            font-size: 0.9rem;
        }
        
        .table-header {
            padding: 15px;
            text-align: center;
        }
        
        .table-header h3 {
            margin: 0 0 10px 0;
            width: 100%;
            font-size: 1.3rem;
        }
        
        .users-container {
            padding: 15px;
        }
    }
    
    /* Small mobile adjustments */
    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-bottom: 15px;
        }
        
        .stat-card {
            padding: 10px 8px;
        }
        
        .stat-number {
            font-size: 1.2em;
            margin-bottom: 2px;
        }
        
        .stat-card div:last-child {
            font-size: 0.75rem;
            line-height: 1.2;
        }
        
        .empty-summary-card {
            padding: 12px;
            font-size: 0.85rem;
            flex-direction: column;
            gap: 6px;
        }
        
        .empty-summary-card i {
            font-size: 1.5rem;
        }
        
        .user-card {
            padding: 12px;
            margin-bottom: 12px;
        }
        
        .user-card-actions {
            flex-direction: column;
        }
        
        .user-card-actions .btn {
            flex: none;
            width: 100%;
        }
        
        .user-detail {
            flex-direction: column;
            align-items: flex-start;
            gap: 2px;
        }
        
        .detail-value {
            text-align: left;
        }
        
        .users-container {
            padding: 10px;
        }
        
        .table-header {
            padding: 12px;
        }
        
        .table-header h3 {
            font-size: 1.2rem;
        }
    }
    
    /* Extra small screens - hide stats if all are 0 */
    @media (max-width: 360px) {
        .stats-grid {
            display: none;
        }
        
        .empty-state-summary {
            margin-bottom: 10px;
        }
        
        .empty-summary-card {
            padding: 10px;
            font-size: 0.8rem;
        }
    }
    
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(2px);
    }
    
    .modal-content {
        background-color: white;
        margin: 15% auto;
        padding: 24px;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border: 1px solid var(--frosh-gray-200, #e5e7eb);
    }
    
    .modal-content h3 {
        color: var(--frosh-gray-900, #111827);
        font-weight: 700;
        margin-bottom: 20px;
        font-size: 1.25rem;
    }
    
    .form-group {
        margin-bottom: 16px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        color: var(--frosh-gray-700, #374151);
        font-size: 14px;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--frosh-gray-300, #d1d5db);
        border-radius: 8px;
        font-size: 14px;
        box-sizing: border-box;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        background-color: white;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--frosh-black, #000000);
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
    }
    
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid transparent;
    }
    
    .alert-success {
        background: var(--frosh-gray-50, #f9fafb);
        color: var(--report-success, #10b981);
        border-color: rgba(16, 185, 129, 0.2);
    }
    
    .alert-error {
        background: var(--frosh-gray-50, #f9fafb);
        color: var(--report-danger, #ef4444);
        border-color: rgba(239, 68, 68, 0.2);
    }
    
    /* Modal responsive adjustments */
    @media (max-width: 480px) {
        .modal-content {
            margin: 10% auto;
            width: 95%;
            padding: 20px;
        }
        
        .modal-content h3 {
            font-size: 1.1rem;
        }
    }
</style>

<div class="users-container">
    <h1>Gestión de Usuarios</h1>
    
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <!-- Statistics -->
    <?php 
    $hasUsers = $stats['total'] > 0;
    if ($hasUsers): 
    ?>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['total']; ?></div>
            <div>Total Usuarios</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['administradores']; ?></div>
            <div>Administradores</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['operadores']; ?></div>
            <div>Operadores</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['usuarios']; ?></div>
            <div>Usuarios</div>
        </div>
    </div>
    <?php else: ?>
    <div class="empty-state-summary">
        <div class="empty-summary-card">
            <i class="fas fa-users"></i>
            <span>No hay usuarios registrados</span>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Users Table -->
    <div class="users-table">
        <div class="table-header">
            <h3>Lista de Usuarios</h3>
            <button class="btn btn-primary" onclick="openModal('addModal')">
                + Agregar Usuario
            </button>
        </div>
        
        <!-- Desktop Table View -->
        <div class="desktop-table-view">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Último Login</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $userData): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($userData['name']); ?></td>
                        <td><?php echo htmlspecialchars($userData['email']); ?></td>
                        <td><?php echo htmlspecialchars($userData['user_name']); ?></td>
                        <td>
                            <span class="badge badge-<?php 
                                echo $userData['permiso'] == 1 ? 'admin' : 
                                    ($userData['permiso'] == 2 ? 'operator' : 'user'); 
                            ?>">
                                <?php echo $userData['rol_nombre']; ?>
                            </span>
                        </td>
                        <td><?php echo $userData['ultimo_login'] ? date('d/m/Y H:i', strtotime($userData['ultimo_login'])) : 'Nunca'; ?></td>
                        <td>
                            <button class="btn btn-secondary btn-sm" 
                                    onclick="editUser(<?php echo $userData['id']; ?>, '<?php echo htmlspecialchars($userData['name']); ?>', '<?php echo htmlspecialchars($userData['email']); ?>', <?php echo $userData['permiso']; ?>)">
                                Editar
                            </button>
                            <button class="btn btn-secondary btn-sm" 
                                    onclick="resetPassword(<?php echo $userData['id']; ?>)">
                                Reset Pass
                            </button>
                            <button class="btn btn-danger btn-sm" 
                                    onclick="deleteUser(<?php echo $userData['id']; ?>, '<?php echo htmlspecialchars($userData['name']); ?>')">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Mobile Cards View -->
        <div class="mobile-cards-view">
            <?php foreach ($users as $userData): ?>
            <div class="user-card">
                <div class="user-card-header">
                    <div class="user-info">
                        <h4 class="user-name"><?php echo htmlspecialchars($userData['name']); ?></h4>
                        <p class="user-email"><?php echo htmlspecialchars($userData['email']); ?></p>
                    </div>
                    <span class="badge badge-<?php 
                        echo $userData['permiso'] == 1 ? 'admin' : 
                            ($userData['permiso'] == 2 ? 'operator' : 'user'); 
                    ?>">
                        <?php echo $userData['rol_nombre']; ?>
                    </span>
                </div>
                
                <div class="user-card-body">
                    <div class="user-detail">
                        <span class="detail-label">Usuario:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($userData['user_name']); ?></span>
                    </div>
                    <div class="user-detail">
                        <span class="detail-label">Último Login:</span>
                        <span class="detail-value"><?php echo $userData['ultimo_login'] ? date('d/m/Y H:i', strtotime($userData['ultimo_login'])) : 'Nunca'; ?></span>
                    </div>
                </div>
                
                <div class="user-card-actions">
                    <button class="btn btn-secondary btn-sm" 
                            onclick="editUser(<?php echo $userData['id']; ?>, '<?php echo htmlspecialchars($userData['name']); ?>', '<?php echo htmlspecialchars($userData['email']); ?>', <?php echo $userData['permiso']; ?>)">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button class="btn btn-secondary btn-sm" 
                            onclick="resetPassword(<?php echo $userData['id']; ?>)">
                        <i class="fas fa-key"></i> Reset
                    </button>
                    <button class="btn btn-danger btn-sm" 
                            onclick="deleteUser(<?php echo $userData['id']; ?>, '<?php echo htmlspecialchars($userData['name']); ?>')">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <h3>Agregar Usuario</h3>
        <form method="POST">
            <input type="hidden" name="action" value="agregar">
            
            <div class="form-group">
                <label class="form-label">Nombre Completo:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Permisos:</label>
                <select name="permisos" class="form-control" required>
                    <option value="1">Administrador</option>
                    <option value="2">Operador</option>
                    <option value="3">Usuario</option>
                </select>
            </div>
            
            <div style="text-align: right; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addModal')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear Usuario</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <h3>Editar Usuario</h3>
        <form method="POST">
            <input type="hidden" name="action" value="actualizar">
            <input type="hidden" name="ID" id="editUserId">
            
            <div class="form-group">
                <label class="form-label">Nombre Completo:</label>
                <input type="text" name="name" id="editUserName" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email:</label>
                <input type="email" name="email" id="editUserEmail" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Permisos:</label>
                <select name="permisos" id="editUserPermisos" class="form-control" required>
                    <option value="1">Administrador</option>
                    <option value="2">Operador</option>
                    <option value="3">Usuario</option>
                </select>
            </div>
            
            <div style="text-align: right; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
    
    function editUser(id, name, email, permisos) {
        document.getElementById('editUserId').value = btoa(id); // Simple base64 encoding
        document.getElementById('editUserName').value = name;
        document.getElementById('editUserEmail').value = email;
        document.getElementById('editUserPermisos').value = permisos;
        openModal('editModal');
    }
    
    function deleteUser(id, name) {
        if (confirm('¿Está seguro de eliminar al usuario "' + name + '"?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="ID" value="${btoa(id)}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    function resetPassword(id) {
        if (confirm('¿Está seguro de restablecer la contraseña de este usuario?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="reset_password">
                <input type="hidden" name="ID" value="${btoa(id)}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modals = document.getElementsByClassName('modal');
        for (let i = 0; i < modals.length; i++) {
            if (event.target === modals[i]) {
                modals[i].style.display = 'none';
            }
        }
    }
</script>

<?php
// Include footer
include 'lavacar/partials/footer.php';
?>