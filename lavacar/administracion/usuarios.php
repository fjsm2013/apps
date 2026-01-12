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
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .stat-number {
        font-size: 2em;
        font-weight: bold;
        color: #D3AF37;
    }
    
    .users-table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .table-header {
        background: #f8f9fa;
        padding: 20px;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
    }
    
    .btn-primary {
        background: #D3AF37;
        color: white;
    }
    
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    
    .btn-danger {
        background: #dc3545;
        color: white;
    }
    
    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table th,
    .table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }
    
    .table th {
        background: #f8f9fa;
        font-weight: 600;
    }
    
    .badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .badge-admin {
        background: #D3AF37;
        color: white;
    }
    
    .badge-operator {
        background: #274AB3;
        color: white;
    }
    
    .badge-user {
        background: #10b981;
        color: white;
    }
    
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }
    
    .modal-content {
        background-color: white;
        margin: 15% auto;
        padding: 20px;
        border-radius: 8px;
        width: 80%;
        max-width: 500px;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .alert {
        padding: 12px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
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
    
    <!-- Users Table -->
    <div class="users-table">
        <div class="table-header">
            <h3>Lista de Usuarios</h3>
            <button class="btn btn-primary" onclick="openModal('addModal')">
                + Agregar Usuario
            </button>
        </div>
        
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