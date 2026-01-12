<?php
session_start();
require_once '../../lib/config.php';
require_once 'lib/Auth.php';
require_once 'lavacar/backend/ServiciosManager.php';
$debug = false;
if ($debug) {
    $data = [
        'id' => 0,
        'descripcion' => 'nbvbnvbnvnvbn',
        'precios' => [
            [
                'categoria_id' => 1,
                'precio' => 2000
            ],
            [
                'categoria_id' => 2,
                'precio' => 20000
            ]
        ]
    ];

    $dbName = "froshlav_3";
} else {
    $data = json_decode(file_get_contents('php://input'), true);
    $user = userInfo();
    log_debug('USER INFO', $user);
    $dbName = $user['company']['db'];
}
log_debug('RAW INPUT', $data);
if (!$data || empty($data['descripcion']) || empty($data['precios'])) {
    echo json_encode(['ok' => false, 'error' => 'Datos incompletos']);
    exit;
}


log_debug('DB NAME', $dbName ?? '');
$manager = new ServiciosManager($conn, $dbName);

/* =========================
   CREATE / UPDATE SERVICE
========================= */
if (empty($data['id']) || $data['id'] == 0) {
    $serviceID = $manager->create($data['descripcion']);
    log_debug('CREATING SERVICE', $data['descripcion'] . "|" . $serviceID);
} else {
    $serviceID = (int)$data['id'];
    log_debug('UPDATING SERVICE', $serviceID);
    $manager->update($serviceID, $data['descripcion']);
}

/* =========================
   SAVE PRICES
========================= */
foreach ($data['precios'] as $p) {
    log_debug(
        'SERVICE ID',
        "INSERT INTO {$dbName}.precios
            (ServicioID, TipoCategoriaID, Precio)
         VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE
            Precio = VALUES(Precio)",
        [
            $serviceID,
            (int)$p['categoria_id'],
            (float)$p['precio']
        ]
    );
    EjecutarSQL(
        $conn,
        "INSERT INTO {$dbName}.precios
            (ServicioID, TipoCategoriaID, Precio)
         VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE
            Precio = VALUES(Precio)",
        [
            $serviceID,
            (int)$p['categoria_id'],
            (float)$p['precio']
        ]
    );
}

echo json_encode(['ok' => true]);

function log_debug($message, $context = null)
{
    $logFile = __DIR__ . '/guardar_servicio.log';

    $entry = '[' . date('Y-m-d H:i:s') . '] ' . $message;

    if ($context !== null) {
        $entry .= ' | ' . print_r($context, true);
    }

    $entry .= PHP_EOL;

    file_put_contents($logFile, $entry, FILE_APPEND);
}