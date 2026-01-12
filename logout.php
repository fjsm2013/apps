<?php
session_start();

require_once 'lib/config.php';
require_once 'lib/Auth.php';

logout();

header("Location: login.php");
exit;