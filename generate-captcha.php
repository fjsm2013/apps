<?php
/**
 * FROSH Simple Math Captcha Generator
 * Generates a simple math question to prevent bots
 */

session_start();

// Generate two random numbers
$num1 = rand(1, 10);
$num2 = rand(1, 10);

// Store the answer in session
$_SESSION['captcha_answer'] = $num1 + $num2;

// Return the question
header('Content-Type: application/json');
echo json_encode([
    'question' => "¿Cuánto es {$num1} + {$num2}?",
    'num1' => $num1,
    'num2' => $num2
]);
