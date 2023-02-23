<?php

// Connexion si besoin !!!!

require 'cnx.php';

// Notifs
$error = null;
$notif = null;

// test Post for insert or delete
if (isset($_POST) && !empty($_POST)) {
    // Check Empty fields
    foreach ($_POST as $attrName => $field) {
        if (empty($field) && $attrName !== 'birthdate') {
            $error = 'Il y un champ vide';
        }
    }
    if (is_null($error)) {
        // Extraction du post
        extract($_POST);
        // var_dump($_POST);
        // die;
        // Check Unity field
        // Check name
        if (strlen($name) <= 30) {
            // Check Email
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Check pwd
                if (strlen($password) >= 6) {
                    // Check if user exist
                    $q = $pdo->prepare('SELECT id FROM user WHERE email = :mail');
                    $q->execute(['mail' => $email]);
                    $user = $q->fetch();
                    // Check
                    if ($user) {
                        $error = 'Un utilisateur existe déjà avec cet email';
                    } else {
                        // Hashage pwd
                        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

                        // Process Insert
                        // INSERT INTO `user`(`id`, `name`, `email`, `password`, `birthdate`, `registration_date`, `role_id`)
                        // VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]')

                        // Test si dirthdate field exist
                        if (empty($birthdate)) {
                            $q = $pdo->prepare(
                                'INSERT INTO user(name, email, password, registration_date)
                                VALUES (:name, :email, :password, NOW())'
                            );
                            $q->execute([
                                'name' => $name,
                                'email' => $email,
                                'password' => $passwordHashed
                            ]);
                        } else {
                            $q = $pdo->prepare(
                                'INSERT INTO user(name, email, password, birthdate, registration_date)
                                VALUES (:name, :email, :password, :birthdate, NOW())'
                            );
                            $q->execute([
                                'name' => $name,
                                'email' => $email,
                                'password' => $passwordHashed,
                                'birthdate' => date($birthdate)
                            ]);
                        }
                        // Notif
                        $notif = 'L\'utilisateur a été enregistré avec succès';
                    }
                } else $error = 'Le champ password est trop court';
            } else $error = 'Le champ email n\'est pas valide';
        } else $error = 'Le champ name est trop long';
    }
}

// Check if users exists
$users = null;
$q = $pdo->query('SELECT name FROM user');
$users = $q->fetchAll();

// Affichage du template
include 'index.phtml';