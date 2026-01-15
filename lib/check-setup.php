<?php
/**
 * FROSH Setup Checker
 * Include this file at the beginning of config.php to auto-setup the system
 */

// Only run setup check if not already done
$setupCheckFile = __DIR__ . '/.setup_complete';

if (!file_exists($setupCheckFile)) {
    try {
        // Define constant to allow auto-setup to run
        define('FROSH_SETUP_RUNNING', true);
        
        // Include auto-setup class
        require_once __DIR__ . '/auto-setup.php';
        
        // Get database credentials from environment or defaults
        $dbHost = getenv('DB_HOST') ?: 'localhost';
        $dbUser = getenv('DB_USER') ?: 'root';
        $dbPass = getenv('DB_PASS') ?: '';
        
        // Create auto-setup instance
        $autoSetup = new AutoSetup($dbHost, $dbUser, $dbPass);
        
        // Check if master database exists
        if (!$autoSetup->masterDatabaseExists()) {
            // Create master database and tables
            $autoSetup->createMasterDatabase();
            
            // Log success
            error_log("FROSH Auto-Setup: Master database created successfully");
        } else {
            // Database exists, just mark setup as complete
            $autoSetup->markSetupComplete();
            error_log("FROSH Auto-Setup: Master database already exists, marking setup as complete");
        }
        
    } catch (Exception $e) {
        // Log error but don't stop execution
        error_log("FROSH Auto-Setup Error: " . $e->getMessage());
        
        // Create a flag file to prevent repeated attempts
        file_put_contents(__DIR__ . '/.setup_error', date('Y-m-d H:i:s') . ': ' . $e->getMessage());
    }
}
