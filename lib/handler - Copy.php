<?php
function EjecutarSQL(PDO $conn, string $query, array $params = [])
{
    try {
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("No se pudo preparar la consulta");
        }

        $stmt->execute($params);

        // If INSERT, return last insert ID
        if (stripos(trim($query), 'insert') === 0) {
            return $conn->lastInsertId();
        }

        return true;
    } catch (Exception $e) {
        error_log("DB Error: " . $e->getMessage() . " | Query: {$query}");
        return false;
    }
}


function ObtenerPrimerRegistro(PDO $conn, string $query, array $params = [])
{
    try {
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("No se pudo preparar la consulta");
        }

        // Execute with parameters
        $stmt->execute($params);

        // Fetch first row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: false;
    } catch (Exception $e) {
        error_log("DB Error: " . $e->getMessage());
        return false;
    }
}

/*
$result = CrearConsulta($conn, $query, $params);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    // use $row
}
 */
function CrearConsulta(PDO $conn, string $query, array $params = [])
{
    try {
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("No se pudo preparar la consulta");
        }

        $stmt->execute($params);

        // Return the statement so caller can fetch rows
        return $stmt;
    } catch (Exception $e) {
        FHTrace("DB Error: " . $e->getMessage() . " | Query: {$query}");
        return false;
    }
}
function ObtenerValor(PDO $conn, string $query, array $params = [])
{
    try {
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("No se pudo preparar la consulta");
        }

        $stmt->execute($params);

        // Fetch first column of first row
        $value = $stmt->fetchColumn();

        return $value !== false ? $value : false;
    } catch (Exception $e) {
        FHTrace("DB Error: " . $e->getMessage() . " | Query: {$query}");
        return false;
    }
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
    require_once("smtp.php");

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