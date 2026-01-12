<?php
include '../../lib/config.php'; // Use the main config instead of local one
include 'c:/comun/formHandler.php';

// The main config already sets up the database connection
// No need to redefine the Database class here

// SOC2 Compliance Program Configuration
define('APP_NAME', 'FROSH Lavacar Management System');
define('APP_VERSION', '1.0');
define('UPLOAD_PATH', __DIR__ . '/uploads/');
define('SECRET_KEY', "InternaNacionalIsacionista");
define('MAX_FILE_SIZE', 10485760); // 10MB

// Email notifications
define('NOTIFICATION_EMAILS', [
    'compliance@company.com',
    'security@company.com'
]);

// Audit settings
define('AUDIT_RETENTION_DAYS', 365 * 7); // 7 years
define('PASSWORD_RESET_EXPIRY', 24); // hours

// Risk assessment thresholds
define('RISK_HIGH_THRESHOLD', 15);
define('RISK_MEDIUM_THRESHOLD', 8);
