<?php
$data = json_decode(file_get_contents('php://input'), true);

// Validar firma si TiloPay la envía
// Guardar resultado en base de datos

file_put_contents('tilopay_log.txt', print_r($data, true), FILE_APPEND);

// Responder 200 OK
http_response_code(200);
