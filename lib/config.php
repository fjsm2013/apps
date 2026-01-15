<?php
// Auto-setup: Check and create master database if needed
require_once __DIR__ . '/check-setup.php';

include 'handler.php';
include 'constants.php';
class Database
{
    private $host = 'localhost';
    private $db_name = 'frosh_lavacar';
    private $username = 'fmorgan';
    private $password = '4sf7xnah';
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
/* =====================================================
   DATABASE CONNECTION
===================================================== */
$DB_USER = 'fmorgan';
$DB_PASS = '4sf7xnah';
$DB_NAME = $DB_NAME ?? 'frosh_lavacar';
$DB_HOST = $DB_HOST ?? 'localhost';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
}



// FROSH Compliance Program Configuration
define('APP_NAME', 'FROSH Systems');
define('APP_VERSION', '1.0');
define('UPLOAD_PATH', __DIR__ . 'uploads/');
define('SECRET_KEY', "InternaNacionalIsacionista"); // 10MB

// Email notifications
define('NOTIFICATION_EMAILS', [
    'myinterpal@gmail.com',
    'amidestino@gmail.com'
]);
define('APP_ROOT', 'C:/wamp/www/interpal/apps');
define('LAVACAR_BASE_URL', '/interpal/apps/lavacar');
chdir(APP_ROOT);
//echo getcwd();