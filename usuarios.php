<?php
require_once 'lib/config.php';
require_once 'lib/Auth.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

if (!$auth->isLoggedIn() || !$auth->hasRole('admin')) {
    header("Location: login.php");
    exit();
}

// Handle form actions
if ($_POST) {
    if (isset($_POST['create_user'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $role = $_POST['role'];
        $department = trim($_POST['department']);

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, password_hash, first_name, last_name, role, department) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($query);
        $stmt->execute([$username, $email, $password_hash, $first_name, $last_name, $role, $department]);

        header("Location: user-management.php");
        exit();
    }

    if (isset($_POST['update_user'])) {
        $user_id = $_POST['user_id'];
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $role = $_POST['role'];
        $department = trim($_POST['department']);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        $query = "UPDATE users 
                  SET first_name = ?, last_name = ?, role = ?, department = ?, is_active = ?
                  WHERE id = ?";

        $stmt = $db->prepare($query);
        $stmt->execute([$first_name, $last_name, $role, $department, $is_active, $user_id]);

        header("Location: user-management.php");
        exit();
    }

    if (isset($_POST['reset_password'])) {
        $user_id = $_POST['user_id'];
        $new_password = $_POST['new_password'];

        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

        $query = "UPDATE users SET password_hash = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$password_hash, $user_id]);

        header("Location: user-management.php");
        exit();
    }
}

// Get all users
$users_query = "SELECT * FROM users ORDER BY first_name, last_name";
$users = $db->query($users_query)->fetchAll(PDO::FETCH_ASSOC);

//roles
$roles_query = "SELECT * FROM roles";
$roles = $db->query($roles_query)->fetchAll(PDO::FETCH_ASSOC);


$departments_query = "SELECT * FROM departments";
$departments = $db->query($departments_query)->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - SOC2 Compliance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">User Management</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="fas fa-user-plus me-1"></i>Add User
                    </button>
                </div>

                <!-- Users Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">System Users</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <span
                                                class="badge 
                                        <?php echo $user['role'] == 'admin' ? 'bg-danger' : ($user['role'] == 'compliance_manager' ? 'bg-warning' : ($user['role'] == 'auditor' ? 'bg-info' : 'bg-secondary')); ?>">
                                                <?php echo ucfirst($user['role']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['department']); ?></td>
                                        <td>
                                            <span
                                                class="badge <?php echo $user['is_active'] ? 'bg-success' : 'bg-danger'; ?>">
                                                <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo $user['last_login'] ? date('M j, Y g:i A', strtotime($user['last_login'])) : 'Never'; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                                    data-bs-target="#editUserModal"
                                                    data-user-id="<?php echo $user['id']; ?>"
                                                    data-first-name="<?php echo htmlspecialchars($user['first_name']); ?>"
                                                    data-last-name="<?php echo htmlspecialchars($user['last_name']); ?>"
                                                    data-role="<?php echo $user['role']; ?>"
                                                    data-department="<?php echo htmlspecialchars($user['department']); ?>"
                                                    data-is-active="<?php echo $user['is_active']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-outline-warning" data-bs-toggle="modal"
                                                    data-bs-target="#resetPasswordModal"
                                                    data-user-id="<?php echo $user['id']; ?>"
                                                    data-username="<?php echo htmlspecialchars($user['username']); ?>">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Create New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Role</label>
                                <select class="form-select" name="role" required>
                                    <option value="user">User</option>
                                    <option value="auditor">Auditor</option>
                                    <option value="manager">Manager</option>
                                    <option value="admin">Administrator</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" class="form-control" name="department" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="create_user" class="btn btn-primary">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" id="edit_first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" id="edit_last_name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Role</label>
                                <select class="form-select" name="role" id="edit_role" required>

                                    <?php
                                    foreach ($roles as $key => $value) {
                                        printf("<option value='%s'>%s</option>", $key, $value);
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department</label>
                                <select class="form-select" name="department" id="edit_department" required>

                                    <?php
                                    foreach ($departments as $key => $value) {
                                        printf("<option value='%s'>%s</option>", $key, $value);
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active"
                                    value="1">
                                <label class="form-check-label" for="edit_is_active">
                                    Active User
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_user" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="user_id" id="reset_user_id">
                    <div class="modal-header">
                        <h5 class="modal-title">Reset Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Reset password for user: <strong id="reset_username"></strong></p>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="new_password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="reset_password" class="btn btn-warning">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Edit User Modal Handler
    var editUserModal = document.getElementById('editUserModal');
    editUserModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var userId = button.getAttribute('data-user-id');
        var firstName = button.getAttribute('data-first-name');
        var lastName = button.getAttribute('data-last-name');
        var role = button.getAttribute('data-role');
        var department = button.getAttribute('data-department');
        var isActive = button.getAttribute('data-is-active');

        document.getElementById('edit_user_id').value = userId;
        document.getElementById('edit_first_name').value = firstName;
        document.getElementById('edit_last_name').value = lastName;
        document.getElementById('edit_role').value = role;
        document.getElementById('edit_department').value = department;
        document.getElementById('edit_is_active').checked = (isActive === '1');
    });

    // Reset Password Modal Handler
    var resetPasswordModal = document.getElementById('resetPasswordModal');
    resetPasswordModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var userId = button.getAttribute('data-user-id');
        var username = button.getAttribute('data-username');

        document.getElementById('reset_user_id').value = userId;
        document.getElementById('reset_username').textContent = username;
    });
    </script>
</body>

</html>