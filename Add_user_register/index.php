<?php

require 'cnx.php';


// var_dump('sessions z', $_SESSION);

// initiate Session
// Check if session is yet init
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// var_dump('session a', $_SESSION);
var_dump('server', $_SERVER);
// Sortie
// string(10) "sessions z" NULL string(9) "session a" array(0) { }

// Notifs
$error = null;
$notif = null;

// Check Logout
if (
    $_SERVER['REQUEST_METHOD'] === 'GET'
    && array_key_exists('logout', $_GET)
) {
    unset($_SESSION['user']);
}

// test Post for insert or delete
if (isset($_POST) && !empty($_POST)) {
    // Check Empty fields
    foreach ($_POST as $attrName => $field) {
        if (empty($field)) {
            $error = 'Il y un champ vide';
        }
    }
    if (is_null($error)) {
        // Extraction du post
        extract($_POST); // $email, $password
        // Check email
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Check pwd
            if (strlen($password) >= 6) {
                // Connexion si besoin !!!!
                require 'cnx.php';
                // Check if user exist
                $q = $pdo->prepare('SELECT name, password FROM user WHERE email = :mail');
                $q->execute(['mail' => $email]);
                $user = $q->fetch();
                // Check
                if ($user) {
                    // Check password
                    if (password_verify($password, $user['password'])) {
                        // Go to session
                        $_SESSION['user']['name'] = $user['name'];
                        $_SESSION['user']['email'] = $email;
                        $notif = 'Connexion avec succÃ¨s';
                    } else $error = 'Mot de pass incorrect';
                } else $error = 'Aucun utilisateur n\'existe avec cet email';
            } else $error = 'Mot de pass trop court';
        } else $error = 'Email incorrect';
    }
}



















// Affichage du template
include 'index.phtml';