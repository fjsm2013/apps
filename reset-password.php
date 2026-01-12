<?php
require_once 'lib/config.php';     // must provide $db (PDO instance)
require_once 'lib/Auth.php';
require_once "lib/PasswordResetManager.php";

$database = new Database();
$db = $database->getConnection();
// Init class
$resetManager = new PasswordResetManager($db);

// 3 MODES:
//  A) Request reset (user submits email)
//  B) Click reset link (token in URL → show password form)
//  C) Submit new password (POST with token)

$msg = null;

/***************************************************
 * A) USER REQUESTS RESET (email submission)
 ***************************************************/
if (isset($_POST['action']) && $_POST['action'] === "request") {

    $resetManager->createResetRequest($_POST['email']);

    // Same generic message to avoid enumeration
    $msg = "If the email exists, a password reset link has been sent.";
}

/***************************************************
 * B) TOKEN PRESENT → USER CLICKED LINK
 ***************************************************/
$validToken = false;
$userId = null;

if (isset($_GET['token'])) {

    $token = $_GET['token'];

    $userId = $resetManager->validateToken($token);

    if ($userId) {
        $validToken = true;
    } else {
        $msg = "Invalid or expired token.";
    }
}

/***************************************************
 * C) USER SUBMITS NEW PASSWORD
 ***************************************************/
if (isset($_POST['action']) && $_POST['action'] === "reset") {

    $token = $_POST['token'];

    $userId = $resetManager->validateToken($token);

    if ($userId) {
        $resetManager->resetPassword($userId, $_POST['password'], $token);
        $msg = "Password reset successful. You may now log in.";
        $validToken = false;
    } else {
        $msg = "Invalid or expired token.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - FROSH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    .login-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .login-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    </style>
</head>

<body>
    <div class="login-container d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="login-card p-4">

                        <div class="text-center mb-4">
                            <h2>FROSH</h2>
                            <p class="text-muted">Forgot Password</p>
                        </div>

                        <?php if ($msg): ?>
                        <div class="alert alert-info"><?php echo htmlspecialchars($msg); ?></div>
                        <?php endif; ?>

                        <!-- MODE A: Request a reset -->
                        <?php if (!isset($_GET['token']) && empty($validToken)): ?>
                        <form method="post">
                            <input type="hidden" name="action" value="request">

                            <label class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email" required>

                            <br>
                            <button type="submit" class="btn btn-dark w-100 py-2">
                                Send Reset Link
                            </button>
                        </form>
                        <?php endif; ?>

                        <!-- MODE B: Show new password form -->
                        <?php if ($validToken): ?>
                        <form method="post" id="resetForm">
                            <input type="hidden" name="action" value="reset">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">

                            <label>New Password:</label>
                            <input type="password" id="password" class="form-control" name="password" required
                                minlength="8">

                            <br>

                            <label>Confirm New Password:</label>
                            <input type="password" id="password_confirm" class="form-control" name="password_confirm"
                                required minlength="8">

                            <p id="errorMsg" class="text-danger mt-2 text-center" style="display:none;">
                                Passwords do not match.
                            </p>

                            <br>
                            <button type="submit" class="btn btn-dark w-100 py-2">
                                Cambiar Clave
                            </button>
                        </form>
                        <?php endif; ?>

                        <a href='login.php'>Back to Login</a>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                &copy; <?php echo date('Y'); ?> SOC2 Compliance System
                            </small>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Password Validation -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const resetForm = document.getElementById("resetForm");

        if (resetForm) {
            resetForm.addEventListener("submit", function(e) {
                const pass = document.getElementById("password").value;
                const confirm = document.getElementById("password_confirm").value;
                const errorMsg = document.getElementById("errorMsg");

                if (pass !== confirm) {
                    e.preventDefault(); // Stop form submission
                    errorMsg.style.display = "block";
                } else {
                    errorMsg.style.display = "none";
                }
            });
        }
    });
    </script>


</body>

</html>