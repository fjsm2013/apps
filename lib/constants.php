<?php
/**
 * FROSH SYSTEM CONSTANTS
 * Global constants for the Frosh car wash management system
 */

// Currency Configuration
define('CURRENCY_SYMBOL', '₡');
define('CURRENCY_CODE', 'CRC');
define('CURRENCY_NAME', 'Colones');
define('CURRENCY_LOCALE', 'es-CR');

// Number formatting
define('CURRENCY_DECIMALS', 0);
define('THOUSAND_SEPARATOR', ',');
define('DECIMAL_SEPARATOR', '.');

// Date and time formats
define('DATE_FORMAT', 'd/m/Y');
define('DATETIME_FORMAT', 'd/m/Y H:i');
define('TIME_FORMAT', 'H:i');

// System defaults
define('DEFAULT_TIMEZONE', 'America/Costa_Rica');
define('DEFAULT_LANGUAGE', 'es');

// Business rules
define('IVA_RATE', 0.13); // 13% IVA for Costa Rica
define('DEFAULT_TRIAL_DAYS', 30);

// File upload limits
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Pagination
define('DEFAULT_PAGE_SIZE', 10);
define('MAX_PAGE_SIZE', 100);

// Session configuration
define('SESSION_TIMEOUT', 3600); // 1 hour
define('REMEMBER_ME_DURATION', 30 * 24 * 3600); // 30 days

/**
 * Get currency configuration as array
 */
function getCurrencyConfig() {
    return [
        'symbol' => CURRENCY_SYMBOL,
        'code' => CURRENCY_CODE,
        'name' => CURRENCY_NAME,
        'locale' => CURRENCY_LOCALE,
        'decimals' => CURRENCY_DECIMALS,
        'thousand_separator' => THOUSAND_SEPARATOR,
        'decimal_separator' => DECIMAL_SEPARATOR
    ];
}