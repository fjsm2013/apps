<?php
class PasswordResetManager
{
    private $conn;
    private $table_users = "usuarios";
    private $table_resets = "password_reset_tokens";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /************************************************************
     * STEP 1 — Create reset request
     ************************************************************/
    public function createResetRequest($email)
    {
        // sanitize
        $email = htmlspecialchars($email);

        // Check for user in usuarios table
        $stmt = $this->conn->prepare("SELECT id, email, name FROM {$this->table_users} WHERE email = ? AND estado = 'activo' LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            return false; // Silent for security
        }

        $userId = $user['id'];
        $userEmail = $user['email'];
        $userName = $user['name'];

        // Delete previous tokens
        $stmt = $this->conn->prepare("DELETE FROM {$this->table_resets} WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        // Create secure token
        $token = bin2hex(random_bytes(32));

        // Insert new token (expires in 1 hour)
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $stmt = $this->conn->prepare("INSERT INTO {$this->table_resets} (user_id, token, expires_at, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $userId, $token, $expiresAt);
        $stmt->execute();

        // Generate reset link
        $hostName = "http://localhost/interpal/apps";
        if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'soc2.go2mti.com') {
            $hostName = "https://soc2.go2mti.com/interpal/apps";
        }
        $resetLink = sprintf("%s/reset-password.php?token=%s", $hostName, urlencode($token));

        // Send email
        $this->sendResetEmail($userEmail, $resetLink, $userName);

        return true;
    }

    /************************************************************
     * Send Reset Email
     ************************************************************/
    function sendResetEmail($to, $link, $name = "")
    {
        try {
            $subject = "FROSH Systems - Recuperacion de Clave";
            
            // Check if template exists
            $templatePath = "lib/templates/forgotPassword.htm";
            if (!file_exists($templatePath)) {
                // Create a simple template if it doesn't exist
                $template = $this->getDefaultResetTemplate();
            } else {
                $template = file_get_contents($templatePath);
            }
            
            $year = date('Y');
            $displayName = !empty($name) ? htmlspecialchars($name) : 'Usuario';

            $message = str_replace(
                ['{{name}}', '{{link}}', '{{current_year}}', '[NAME]', '[RESET_LINK]', '[CURRENT_YEAR]'],
                [$displayName, htmlspecialchars($link), $year, $displayName, htmlspecialchars($link), $year],
                $template
            );

            // Send email using the same function as the working examples
            $result = EnviarEmail($subject, $message, [$to, $displayName]);
            
            // Also send copy to admin for debugging
            EnviarEmail($subject . " (Copia)", $message, ["myinterpal@gmail.com", "Administración"]);
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Error enviando email de reset: " . $e->getMessage());
            return false;
        }
    }

    /************************************************************
     * Default Reset Template
     ************************************************************/
    private function getDefaultResetTemplate()
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Recuperación de Clave</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #274AB3; color: white; padding: 20px; text-align: center; }
                .content { padding: 30px; background: #f9f9f9; }
                .button { display: inline-block; background: #274AB3; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>FROSH Systems</h1>
                    <h2>Recuperacion de Clave</h2>
                </div>
                <div class="content">
                    <p>Hola {{name}},</p>
                    <p>Hemos recibido una solicitud para restablecer la Clave de tu cuenta.</p>
                    <p>Si solicitaste este cambio, haz clic en el siguiente enlace para crear una nueva Clave:</p>
                    <p style="text-align: center;">
                        <a href="{{link}}" class="button">Restablecer Clave</a>
                    </p>
                    <p><strong>Este enlace expirara en 1 hora por seguridad.</strong></p>
                    <p>Si no solicitaste este cambio, puedes ignorar este correo. Tu Clave actual seguira siendo valida.</p>
                    <p>Por tu seguridad, nunca compartas este enlace con nadie.</p>
                    <p>Saludos,<br>El equipo de FROSH Systems</p>
                </div>
                <div class="footer">
                    <p>&copy; {{current_year}} FROSH Systems. Todos los derechos reservados.</p>
                    <p>Este es un correo automatico, por favor no responder.</p>
                </div>
            </div>
        </body>
        </html>';
    }

    /************************************************************
     * STEP 2 — Validate Token
     ************************************************************/
    public function validateToken($token)
    {
        $stmt = $this->conn->prepare("
            SELECT user_id 
            FROM {$this->table_resets}
            WHERE token = ?
            AND expires_at > NOW()
            AND used = 0
            LIMIT 1
        ");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row ? $row['user_id'] : false;
    }

    /************************************************************
     * STEP 3 — Reset Password
     ************************************************************/
    public function resetPassword($userId, $newPassword, $token)
    {
        // Hash new password
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password in usuarios table
        $stmt = $this->conn->prepare("UPDATE {$this->table_users} SET password = ?, fecha_actualizacion = NOW() WHERE id = ?");
        $stmt->bind_param("si", $hash, $userId);
        $stmt->execute();

        // Mark token as used
        $stmt = $this->conn->prepare("UPDATE {$this->table_resets} SET used = 1 WHERE user_id = ? AND token = ?");
        $stmt->bind_param("is", $userId, $token);
        $stmt->execute();

        return true;
    }
}