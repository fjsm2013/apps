<?php
function EjecutarSQL($link, $query, $aParms = array())
{
    $statement = $link->prepare($query);
    if (!$statement) {
        FHTrace("Could not create statement " . $query);
        die("Could not create statement " . $query);
    }
    if (count($aParms)) {
        $aReference = array(MakeBindMap($aParms));
        for ($i = 0; $i < count($aParms); $i++)
            $aReference[] = &$aParms[$i];
        call_user_func_array(array(&$statement, 'bind_param'), $aReference);
    }
    if ($statement->execute()) {
        if (substr(strtolower($query), 0, 6) == "insert")
            return $link->insert_id;
        else
            return true;
    } else {
        die('Error : (' . $link->errno . ') ' . $link->error);
    }
}

function ObtenerPrimerRegistro($link, $query, $aParms = array())
{
    $statement = $link->prepare($query);
    if (!$statement) {
        FHTrace("Could not create statement " . $query);
        die("Could not create statement " . $query);
    }
    if (count($aParms)) {
        $aReference = array(MakeBindMap($aParms));
        for ($i = 0; $i < count($aParms); $i++)
            $aReference[] = &$aParms[$i];
        //$statement->bind_param('i', $userID);
        call_user_func_array(array(&$statement, 'bind_param'), $aReference);
    }
    $row = false;
    if ($statement->execute()) {
        $res = $statement->get_result();
        if (!$res) {
            FHTrace('Error : (' . $link->errno . ') ' . $link->error . $query . print_r($aParms, true));
            die('Error : (' . $link->errno . ') ' . $link->error);
        } else {
            $row = $res->fetch_assoc();
        }
    } else {
        FHTrace('Error : (' . $link->errno . ') ' . $link->error . $query);
        die('Error : (' . $link->errno . ') ' . $link->error);
    }
    return $row;
}

function CrearConsulta($link, $query, $aParms = array())
{
    $statement = $link->prepare($query);
    if (!$statement) {
        FHTrace("Could not create statement " . $query);
        die("Could not create statement " . $query);
    }
    if (count($aParms)) {
        $aReference = array(MakeBindMap($aParms));
        for ($i = 0; $i < count($aParms); $i++)
            $aReference[] = &$aParms[$i];
        //$statement->bind_param('i', $userID);
        call_user_func_array(array(&$statement, 'bind_param'), $aReference);
    }
    $row = false;
    if ($statement->execute()) {
        return $statement->get_result();
    } else {
        FHTrace('Error : (' . $link->errno . ') ' . $link->error . $query);
        die('Error : (' . $link->errno . ') ' . $link->error);
    }
    return false;
}

function ObtenerValor($link, $query, $aParms = array())
{
    $statement = $link->prepare($query);
    if (!$statement) {
        FHTrace("Could not create statement " . $query);
        die("Could not create statement " . $query);
    }
    if (count($aParms)) {
        $aReference = array(MakeBindMap($aParms));
        for ($i = 0; $i < count($aParms); $i++)
            $aReference[] = &$aParms[$i];
        //$statement->bind_param('i', $userID);
        call_user_func_array(array(&$statement, 'bind_param'), $aReference);
    }
    $row = false;
    if ($statement->execute()) {
        $res = $statement->get_result();
        $row = $res->fetch_array();
        if ($row)
            return $row[0];
    } else {
        FHTrace('Error : (' . $link->errno . ') ' . $link->error . $query);
        die('Error : (' . $link->errno . ') ' . $link->error);
    }
    return false;
}
function MakeBindMap($aParms)
{
    $map = "";
    foreach ($aParms as $value) {
        switch (gettype($value)) {
            case "integer":
            case "boolean":
                $map .= "i";
                break;

            case "double":
                $map .= "d";
                break;

            case "string":
                $map .= "s";
                break;

            default:
                $map .= "s";
        }
    }
    return $map;
}

function EnviarEmail($subject, $message, $row, $emailAttachment = '')
{
    global $smtp_ses_host, $smtp_access_key, $smtp_secret_key, $smtp_se_email;
    require_once 'c:/comun/phpmailer/vendor/autoload.php';
    require_once("C:/comun/smtp.php");

    $mail = new \PHPMailer\PHPMailer\PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
    $mail->IsSMTP(); // telling the class to use SMTP
    try {
        $mail->SMTPDebug = 0; // enables SMTP debug information (for testing)
        $mail->SMTPAuth = true; // enable SMTP authentication
        $mail->SMTPSecure = "tls";
        $mail->SMTPAutoTLS = true;
        $mail->Port = 587; // set the SMTP port for the GMAIL server
        $mail->SMTPOptions = array('ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ));
        $mail->Host = $smtp_ses_host; // sets the SMTP server
        $mail->Username = $smtp_access_key; // SMTP username
        $mail->Password = $smtp_secret_key; // SMTP password
        $mail->SetFrom($smtp_se_email, 'Taller'); //Email from

        $mail->AddAddress($row[0], $row[1]);
        $mail->Subject = $subject;
        $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
        if ($emailAttachment != '') {
            if (file_exists($emailAttachment)) {
                $mail->AddAttachment($emailAttachment);
            }
        }
        $mail->MsgHTML($message);
        $mail->Send();
        //echo "Message Sent OK {$row[0]}\r\n";
    } catch (phpmailerException $e) {
        //echo $e->errorMessage(); //Pretty error messages from PHPMailer
        return false;
    } catch (exception $e) {
        //echo $e->getMessage(); //Boring error messages from anything else!
        return false;
    }
    return true;
}

function encryptarOrDecryptar($action, $text)
{
    // Encryption/Decryption settings
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'edesarzobispodeconstantinopolitarizador';
    $secret_iv = 'edesarzobispodeconstantinopolitarizar';

    // Generate a 256-bit key using SHA-256 from the secret key
    $key = hash('sha256', $secret_key);

    // Generate an initialization vector (IV) of 16 bytes
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'encrypt') {
        // Encrypt the text using OpenSSL with AES-256-CBC
        $encrypted_text = openssl_encrypt($text, $encrypt_method, $key, 0, $iv);

        // Base64 encode the encrypted text for safe storage
        $output = base64_encode($encrypted_text);
    } else if ($action == 'decrypt') {
        // URL Decode the input text (if needed)
        $urlDecodedText = urldecode($text);

        // Decode the Base64 encoded input text
        $decoded_text = base64_decode($urlDecodedText);

        // Decrypt the text using OpenSSL with AES-256-CBC
        $output = openssl_decrypt($decoded_text, $encrypt_method, $key, 0, $iv);
    } else {
        // Invalid action specified
        return false;
    }

    return urlencode($output);
}

function controlAcceso($login = true)
{
    global $link, $usuarioID;
    $usuarioID = 0;
    if (!$login) {
        setcookie("Usuario", '', time() - 360000, "/");
        unset($_COOKIE["Usuario"]);
        header('location:index.php');
    }

    //var_dump($_COOKIE);
    if ($_COOKIE['UsuarioID'] ?? false) {
        $usuarioID = ObtenerValor($link, "SELECT UsuarioID FROM `usuarios` u WHERE UsuarioID = ?", [encryptarOrDecryptar('decrypt', $_COOKIE['UsuarioID'])]);
        if ($usuarioID) {
            echo $usuarioID;
            echo "Cookie existe!";
            header('location:panel.php');
            exit();
            //return $usuarioID;
        }
    }

    if ($login) {
        //validar usuario
        if (!isset($_POST["Email"]) || !isset($_POST["Clave"])) {
            //echo $usuarioID;
            return 0;
        } else {

            $query = "SELECT UsuarioID, Contrasena FROM tallersa.usuarios WHERE Email = ?";
            $result = CrearConsulta($link, $query, array($_POST["Email"]));

            if ($row = mysqli_fetch_assoc($result)) {
                $storedPassword = $row['Contrasena'];
                $enteredPassword = $_POST["Clave"];
            }

            if (password_verify($enteredPassword, $storedPassword)) {
                $query = "SELECT UsuarioID,u.`NombreCompleto` FROM tallersa.`usuarios` u WHERE Email = ?";
                $result = CrearConsulta($link, $query, array($_POST["Email"]));
            } else {
                $query = "SELECT u.UsuarioID,u.`NombreCompleto`FROM tallersa.`usuarios` u WHERE Email = ? AND Contrasena = ?";
                $result = CrearConsulta($link, $query, array($_POST["Email"], $_POST["Clave"]));
            }
            if ($row = $result->fetch_array()) {
                $usuarioID = encryptarOrDecryptar('encrypt', $row['UsuarioID']);
                setcookie("UsuarioID", $usuarioID, time() + (10000000), "/");
                header('location:main.php');
                return $row['UsuarioID'];
            } else {
                echo  "Algo Salio Mal!";
            }
        }
    }
}

function Encrypter($text, $action = true)
{
    // Encryption/Decryption settings
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'edesarzobispodeConstantinoteyrsfdor';
    $secret_iv = 'edesarzobispodeConstantinopolitarizador';

    // Generate a 256-bit key using SHA-256 from the secret key
    $key = hash('sha256', $secret_key);

    // Generate an initialization vector (IV) of 16 bytes
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action) {
        // Encrypt the text using OpenSSL with AES-256-CBC
        $encrypted_text = openssl_encrypt($text, $encrypt_method, $key, 0, $iv);

        // Base64 encode the encrypted text for safe storage
        $output = base64_encode($encrypted_text);
        return urlencode($output);
    } else {
        // URL Decode the input text (if needed)
        $urlDecodedText = urldecode($text);

        // Decode the Base64 encoded input text
        $decoded_text = base64_decode($urlDecodedText);

        // Decrypt the text using OpenSSL with AES-256-CBC
        $output = openssl_decrypt($decoded_text, $encrypt_method, $key, 0, $iv);
        return urldecode($output);
    }
}
function generateRandomPassword($length = 12)
{
    $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    $numbers = '0123456789';
    $specialChars = '!@#$&*-+.';

    $allChars = $uppercase . $lowercase . $numbers . $specialChars;
    $password = '';

    // Ensure at least one character from each character set
    $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
    $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
    $password .= $numbers[rand(0, strlen($numbers) - 1)];
    $password .= $specialChars[rand(0, strlen($specialChars) - 1)];

    // Fill the rest of the password with random characters
    for ($i = strlen($password); $i < $length; $i++) {
        $password .= $allChars[rand(0, strlen($allChars) - 1)];
    }

    // Shuffle the password to mix characters
    $password = str_shuffle($password);

    return $password;
}

function safe_htmlspecialchars($string, $default = ''): string
{
    return htmlspecialchars($string ?? $default, ENT_QUOTES, 'UTF-8');
}

function safe_number_format($number, $decimals = 2, $default = 0): string
{
    return number_format($number ?? $default, $decimals);
}

function EmailSenderDFT($subject, $message, $row, $emailAttachment = '')
{
    global $smtp_ses_host, $smtp_access_key, $smtp_secret_key, $smtp_se_email;
    require_once("C:/comun/phpmailer/vendor/autoload.php");

    $mail = new \PHPMailer\PHPMailer\PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
    $mail->IsSMTP(); // telling the class to use SMTP
    try {
        $mail->SMTPDebug = 0; // enables SMTP debug information (for testing)
        $mail->SMTPAuth = true; // enable SMTP authentication
        $mail->SMTPSecure = "tls";
        $mail->SMTPAutoTLS = true;
        $mail->Port = 587; // set the SMTP port for the GMAIL server
        $mail->SMTPOptions = array('ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ));
        $mail->Host = $smtp_ses_host; // sets the SMTP server
        $mail->Username = $smtp_access_key; // SMTP username
        $mail->Password = $smtp_secret_key; // SMTP password
        $mail->SetFrom($smtp_se_email, 'FROSH'); //Email from

        $mail->AddAddress($row[0], $row[1]);
        //$mail->AddAddress('myinterpal@gmail.com', "Javier Saavedra");
        $mail->Subject = $subject;
        $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
        if ($emailAttachment != '') {
            if (file_exists($emailAttachment)) {
                $mail->AddAttachment($emailAttachment);
            }
        }
        $mail->MsgHTML($message);
        $mail->Send();
        //echo "Message Sent OK {$row[0]}\r\n";
    } catch (phpmailerException $e) {
        //echo $e->errorMessage(); //Pretty error messages from PHPMailer
        return false;
    } catch (exception $e) {
        //echo $e->getMessage(); //Boring error messages from anything else!
        return false;
    }
    return true;
}

/*function SendReportEmail($subject, $message, $email, $name)
{
    $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

    $mail->IsSMTP(); // telling the class to use SMTP

    try {
        $mail->SMTPDebug = 0; // enables SMTP debug information (for testing)
        $mail->SMTPAuth = true; // enable SMTP authentication
        $mail->SMTPSecure = "STARTTLS";
        $mail->Host = "smtp.titan.email"; // sets the SMTP server
        $mail->Port = 587; // set the SMTP port for the GMAIL server
        $mail->Username = "booking@taxiairportliberia.com"; // SMTP username
        $mail->Password = "k!Net0wR+sWl5EyutTF"; // SMTP password
        $mail->SetFrom("booking@taxiairportliberia.com", 'Taxi Airport Liberia');
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        //echo "\t{$row['EMail']}\r\n";
        $mail->AddAddress($email, $name);
        //$mail->AddAddress('myinterpal@gmail.com', "Javier Saavedra");
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
        $mail->Send();
        echo "";
    } catch (phpmailerException $e) {
        //echo $e->errorMessage(); //Pretty error messages from PHPMailer
        return false;
    } catch (exception $e) {
        // echo $e->getMessage(); //Boring error messages from anything else!
        return false;
    }
    return true;
}*/
// Function to generate the email content dynamically by replacing placeholders
function generateEmailContent($fileName, $params)
{
    // Read the content of the template file
    if (!file_exists($fileName)) {
        return "Template file not found!";
    }

    $template = file_get_contents($fileName);

    // Replace placeholders with values from the $params array
    foreach ($params as $placeholder => $value) {
        $template = str_replace("[$placeholder]", $value, $template);
    }

    // Return the modified email content
    return $template;
}


function logMessage($message, $params = [], $logFile = 'C:/wamp/logs/frosh.log')
{
    $timestamp = date('Y-m-d H:i:s');
    //$logEntry = sprintf("[%s] [Line:%d] [File:%s]", $timestamp, __LINE__, __FILE__);
    $logEntry = sprintf("[%s] ", $timestamp);
    if (!empty($params)) {
        $logEntry .= " | " . json_encode($params);
    }

    $logEntry .= PHP_EOL;

    // Create directory if it doesn't exist
    $logDir = dirname($logFile);
    if (!is_dir($logDir) && $logDir !== '.') {
        mkdir($logDir, 0755, true);
    }

    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

//Function to send the Update work order tickets
function sendOrderEmail($orderID, $customerData, $recipients, $estado = "Pendiente")
{
    /**
     * Sends an order confirmation email using a template
     * 
     * @param string $orderID The order identification number
     * @param array $customerData Associative array with customer information
     * @param array $recipients Array of recipient arrays [email, name]
     * @return bool True if emails were sent successfully, false otherwise
     */

    $subject = "FROSH - Orden de Servicio {$orderID}";

    // Load the template
    $templateFile = '../../layouts/emailTemplates/orden.htm';

    if (!file_exists($templateFile)) {
        error_log("Email template not found: " . $templateFile);
        return false;
    }

    $templateContent = file_get_contents($templateFile);
    $year = date('Y');

    // Prepare the email content
    $emailContent = '<p><b>Placa: </b> ' . htmlspecialchars($customerData['placa']) . '<br/>';
    $emailContent .= '<b>Nombre: </b> ' . htmlspecialchars($customerData['nombre']) . '<br/>';
    $emailContent .= '<b>Fecha y hora: </b> ' . htmlspecialchars($customerData['fecha_ingreso']) . '<br/>';
    $emailContent .= '<b>Estado: </b> ' . htmlspecialchars($estado) . '<br/></p>';

    $infoBox = '<p><b>Placa: </b> ' . htmlspecialchars($customerData['placa']) . '<br/>';
    $infoBox .= '<b>Nombre: </b> ' . htmlspecialchars($customerData['nombre']) . '<br/>';
    $infoBox .= '<b>Fecha y hora: </b> ' . htmlspecialchars($customerData['fecha_ingreso']) . '<br/></p>';

    // Define the parameters for template replacement
    $params = [
        'COMPANY' => 'FROSH',
        'ORDER_NUMBER' => 'Orden: ' . $orderID,
        'EMAIL_CONTENT' => $emailContent,
        'INFO_BOX' => $infoBox,
        'BUTTON_TEXT' => 'Ver Orden',
        'CURRENT_YEAR' => $year,
    ];

    // Replace placeholders with actual values
    foreach ($params as $key => $value) {
        $templateContent = str_replace("[" . $key . "]", $value, $templateContent);
    }

    // Send email to each recipient
    $success = true;
    foreach ($recipients as $recipient) {
        if (count($recipient) >= 2) {
            $email = $recipient[0];
            $name = $recipient[1];

            // Assuming EmailSenderDFT function exists and returns boolean
            $result = EmailSenderDFT($subject, $templateContent, [$email, $name]);

            if (!$result) {
                error_log("Failed to send email to: " . $email);
                $success = false;
            }
        }
    }

    return $success;
}

// ===== FUNCIONES REUTILIZABLES PARA UI =====

/**
 * Genera estadísticas rápidas para dashboards
 */
function generateStatsCards($stats, $cardTypes = ['primary', 'success', 'info', 'warning']) {
    $html = '<div class="row g-3 mb-4">';
    $index = 0;
    
    foreach ($stats as $key => $stat) {
        $cardType = $cardTypes[$index % count($cardTypes)];
        $html .= '<div class="col-md-3">';
        $html .= '<div class="stats-card ' . $cardType . '">';
        $html .= '<div class="stats-icon">';
        $html .= '<i class="' . ($stat['icon'] ?? 'fa-solid fa-chart-bar') . '"></i>';
        $html .= '</div>';
        $html .= '<div class="stats-content">';
        $html .= '<h3>' . ($stat['value'] ?? 0) . '</h3>';
        $html .= '<p>' . ($stat['label'] ?? ucfirst(str_replace('_', ' ', $key))) . '</p>';
        if (isset($stat['subtitle'])) {
            $html .= '<small>' . $stat['subtitle'] . '</small>';
        }
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $index++;
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Genera breadcrumbs dinámicamente
 */
function generateBreadcrumbs($breadcrumbs, $baseUrl = '') {
    if (empty($breadcrumbs)) return '';
    
    $html = '<nav aria-label="breadcrumb" class="mb-3">';
    $html .= '<div class="container">';
    $html .= '<ol class="breadcrumb bg-transparent p-0 m-0">';
    $html .= '<li class="breadcrumb-item">';
    $html .= '<a href="' . $baseUrl . '/dashboard.php" class="breadcrumb-link">';
    $html .= '<i class="fa-solid fa-home me-1"></i>Inicio</a></li>';
    
    foreach ($breadcrumbs as $index => $crumb) {
        if ($index === count($breadcrumbs) - 1) {
            $html .= '<li class="breadcrumb-item active" aria-current="page">';
            $html .= htmlspecialchars($crumb['title']) . '</li>';
        } else {
            $html .= '<li class="breadcrumb-item">';
            $html .= '<a href="' . htmlspecialchars($crumb['url']) . '" class="breadcrumb-link">';
            $html .= htmlspecialchars($crumb['title']) . '</a></li>';
        }
    }
    
    $html .= '</ol></div></nav>';
    return $html;
}

/**
 * Genera filtros de búsqueda reutilizables
 */
function generateSearchFilters($config) {
    $html = '<div class="filter-card"><form method="GET" class="row g-3 align-items-end">';
    
    foreach ($config['fields'] as $field) {
        $html .= '<div class="col-md-' . ($field['width'] ?? 4) . '">';
        $html .= '<label class="form-label">' . $field['label'] . '</label>';
        
        switch ($field['type']) {
            case 'search':
                $html .= '<div class="input-group">';
                $html .= '<span class="input-group-text"><i class="fa-solid fa-search"></i></span>';
                $html .= '<input type="text" class="form-control" name="' . $field['name'] . '" ';
                $html .= 'value="' . htmlspecialchars($_GET[$field['name']] ?? '') . '" ';
                $html .= 'placeholder="' . ($field['placeholder'] ?? '') . '">';
                $html .= '</div>';
                break;
                
            case 'select':
                $html .= '<select class="form-select" name="' . $field['name'] . '">';
                foreach ($field['options'] as $value => $label) {
                    $selected = ($_GET[$field['name']] ?? '') === $value ? 'selected' : '';
                    $html .= '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
                }
                $html .= '</select>';
                break;
                
            case 'date':
                $html .= '<input type="date" class="form-control" name="' . $field['name'] . '" ';
                $html .= 'value="' . htmlspecialchars($_GET[$field['name']] ?? '') . '">';
                break;
        }
        
        $html .= '</div>';
    }
    
    // Botones de acción
    $html .= '<div class="col-md-3">';
    $html .= '<button type="submit" class="btn btn-frosh-dark me-2">';
    $html .= '<i class="fa-solid fa-search me-1"></i>' . ($config['search_text'] ?? 'Buscar') . '</button>';
    $html .= '<a href="' . ($config['reset_url'] ?? $_SERVER['PHP_SELF']) . '" class="btn btn-outline-frosh-gray">';
    $html .= '<i class="fa-solid fa-refresh me-1"></i>' . ($config['reset_text'] ?? 'Limpiar') . '</a>';
    $html .= '</div>';
    
    $html .= '</form></div>';
    return $html;
}

/**
 * Formatea fechas de manera consistente
 */
function formatDate($date, $format = 'd/m/Y') {
    if (!$date) return 'N/A';
    return date($format, strtotime($date));
}

/**
 * Formatea moneda
 */
function formatCurrency($amount, $currency = '₡', $decimals = 0) {
    if ($amount === null || $amount === '') return 'N/A';
    return $currency . ' ' . number_format($amount, $decimals);
}

/**
 * Genera badges de estado
 */
function generateStatusBadge($status, $config = []) {
    $defaultConfig = [
        'active' => ['class' => 'bg-success', 'text' => 'Activo'],
        'inactive' => ['class' => 'bg-secondary', 'text' => 'Inactivo'],
        'pending' => ['class' => 'bg-warning', 'text' => 'Pendiente'],
        'completed' => ['class' => 'bg-success', 'text' => 'Completado'],
        'cancelled' => ['class' => 'bg-danger', 'text' => 'Cancelado']
    ];
    
    $config = array_merge($defaultConfig, $config);
    $statusConfig = $config[$status] ?? ['class' => 'bg-secondary', 'text' => ucfirst($status)];
    
    return '<span class="badge ' . $statusConfig['class'] . '">' . $statusConfig['text'] . '</span>';
}

/**
 * Genera respuesta JSON estándar para AJAX
 */
function jsonResponse($success, $data = null, $message = '', $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    
    $response = [
        'success' => $success,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit;
}

/**
 * Valida y sanitiza entrada de datos
 */
function sanitizeInput($data, $type = 'string') {
    if ($data === null || $data === '') return null;
    
    switch ($type) {
        case 'int':
            return (int) filter_var($data, FILTER_SANITIZE_NUMBER_INT);
        case 'float':
            return (float) filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        case 'email':
            return filter_var($data, FILTER_SANITIZE_EMAIL);
        case 'url':
            return filter_var($data, FILTER_SANITIZE_URL);
        case 'string':
        default:
            return trim(htmlspecialchars($data, ENT_QUOTES, 'UTF-8'));
    }
}

/**
 * Genera paginación
 */
function generatePagination($currentPage, $totalPages, $baseUrl, $params = []) {
    if ($totalPages <= 1) return '';
    
    $html = '<nav aria-label="Paginación"><ul class="pagination justify-content-center">';
    
    // Botón anterior
    if ($currentPage > 1) {
        $prevParams = array_merge($params, ['page' => $currentPage - 1]);
        $html .= '<li class="page-item">';
        $html .= '<a class="page-link" href="' . $baseUrl . '?' . http_build_query($prevParams) . '">';
        $html .= '<i class="fa-solid fa-chevron-left"></i></a></li>';
    }
    
    // Números de página
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);
    
    for ($i = $start; $i <= $end; $i++) {
        $pageParams = array_merge($params, ['page' => $i]);
        $active = $i === $currentPage ? 'active' : '';
        $html .= '<li class="page-item ' . $active . '">';
        $html .= '<a class="page-link" href="' . $baseUrl . '?' . http_build_query($pageParams) . '">' . $i . '</a>';
        $html .= '</li>';
    }
    
    // Botón siguiente
    if ($currentPage < $totalPages) {
        $nextParams = array_merge($params, ['page' => $currentPage + 1]);
        $html .= '<li class="page-item">';
        $html .= '<a class="page-link" href="' . $baseUrl . '?' . http_build_query($nextParams) . '">';
        $html .= '<i class="fa-solid fa-chevron-right"></i></a></li>';
    }
    
    $html .= '</ul></nav>';
    return $html;
}
