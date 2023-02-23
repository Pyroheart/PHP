<?php

// Etablir une connexion à la bdd => PDO
$dsn = 'mysql:host=localhost;dbname=user_auth';
$user = 'root';
$password = '';
$db_name = "user_auth";  

$pdo = new PDO(
    $dsn,
    $user,
    $password,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);