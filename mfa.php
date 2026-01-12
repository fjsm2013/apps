<?php
require_once 'lib/config.php';
require_once 'lib/Auth.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

$error = '';

if ($_POST && isset($_POST['verify'])) {
    $code = $_POST['code'];

    if ($auth->verifyMFA($code)) {

        // load user info stored in cookie
        $user = [
            'id' => $_COOKIE['mfa_user_id'],
            'username' => $_COOKIE['mfa_username'],
            'role' => $_COOKIE['mfa_role']
        ];

        $auth->finalizeLogin($user);

        // clear temp cookies
        setcookie("mfa_user_id", "", time() - 3600, "/");
        setcookie("mfa_username", "", time() - 3600, "/");
        setcookie("mfa_role", "", time() - 3600, "/");

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid or expired verification code.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>MFA Verification</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-5" style="max-width: 450px;">
        <div class="card p-4">
            <h3 class="text-center mb-3">Multi-Factor Authentication</h3>
            <p>A verification code has been sent to your email.</p>

            <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="post">
                <input type="text" class="form-control mb-3" name="code" maxlength="6" required
                    placeholder="Enter 6-Digit Code">

                <button class="btn btn-primary w-100" name="verify">Verify</button>
            </form>
        </div>
    </div>

</body>

</html>