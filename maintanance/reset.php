<?php
require_once '../lib/config.php';

$queries = [
    "TRUNCATE frosh_lavacar.suscripciones",
    "TRUNCATE frosh_lavacar.empresas",
    "TRUNCATE frosh_lavacar.password_resets",
    "TRUNCATE frosh_lavacar.password_reset_tokens",
    "TRUNCATE frosh_lavacar.access_logs",
    "TRUNCATE frosh_lavacar.usuarios"
];

foreach ($queries as $query) {
    echo "Processing: ".$query."\n";
    EjecutarSQL($conn, $query);
}
