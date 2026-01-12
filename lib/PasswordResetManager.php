<?php
class PasswordResetManager
{
    private $conn;
    private $table_users = "users";
    private $table_resets = "password_resets";

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

        // Check for user
        $query = "SELECT id,email FROM {$this->table_users} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return false; // Silent for security
        }

        $userId = $user['id'];
        $email = $user['email'];

        // Delete previous tokens
        $del = $this->conn->prepare("DELETE FROM {$this->table_resets} WHERE user_id=:uid");
        $del->bindParam(":uid", $userId);
        $del->execute();

        // Create secure token
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash("sha256", $token);

        // Insert new token
        $insert = $this->conn->prepare("
            INSERT INTO {$this->table_resets} (user_id, token_hash, expires_at)
            VALUES (:uid, :hash, DATE_ADD(NOW(), INTERVAL 1 HOUR))
        ");
        $insert->bindParam(":uid", $userId);
        $insert->bindParam(":hash", $tokenHash);
        $insert->execute();
        $hostName = "http://localhost/interpal/apps";
        $resetLink = "http://localhost/reset-password.php?token=" . urlencode($token);
        if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'soc2.go2mti.com') {
            $hostName = "soc2.go2mti.com/";
        }
        $resetLink = sprintf("%s/reset-password.php?token=%s", $hostName, urlencode($token));

        // Send email
        $this->sendResetEmail($email, $resetLink);

        return true;
    }

    /************************************************************
     * Send Reset Email
     ************************************************************/

    function sendResetEmail($to, $link, $name = "")
    {
        $subject = "FROSH Systems - Password Request Received!";
        $template = file_get_contents("lib/templates/forgotPassword.htm");
        $year = date('Y');

        $message = str_replace(
            ['{{name}}', '{{link}}', '{{current_year}}'],
            [htmlspecialchars($name), htmlspecialchars($link), $year],
            $template
        );

        // Send email (HTML email)
        EnviarEmail($subject, $message, [$to, '']);
    }


    /************************************************************
     * STEP 2 — Validate Token
     ************************************************************/
    public function validateToken($token)
    {
        $tokenHash = hash("sha256", $token);

        $query = "
            SELECT user_id 
            FROM {$this->table_resets}
            WHERE token_hash = :hash
            AND expires_at > NOW()
            AND used = 0
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":hash", $tokenHash);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['user_id'] : false;
    }

    /************************************************************
     * STEP 3 — Reset Password
     ************************************************************/
    public function resetPassword($userId, $newPassword, $token)
    {
        // Hash new password
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $tokenHash = hash("sha256", $token);

        // Update password
        $update = $this->conn->prepare("
            UPDATE {$this->table_users}
            SET password_hash = :pw
            WHERE id = :uid
        ");
        $update->bindParam(":pw", $hash);
        $update->bindParam(":uid", $userId);
        $update->execute();

        // Mark token used
        $invalidate = $this->conn->prepare("
            UPDATE {$this->table_resets}
            SET used = 1
            WHERE user_id = :uid AND token_hash = :thash
        ");
        $invalidate->bindParam(":uid", $userId);
        $invalidate->bindParam(":thash", $tokenHash);
        $invalidate->execute();

        return true;
    }
}