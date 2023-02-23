<?php
// Connexion à la bdd

$pdo = new PDO('mysql:host=localhost;dbname=classicmodels;charset=utf8',
    'root',
    'root',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

// Test à effectuer pour s'assurer de la bonne connectivité avec la bdd
//var_dump($pdo);

// Tuer le script pour ne pas tenter de faire autre chose en même temps
//die('Another day');

// Méthode 1 - Requête simple (query est une méthode de l'objet PDO)
$q = $pdo->query('Select orderNumber, orderDate, shippedDate, status from orders');
//$customerFirst = $q->fetch(); // fetch => récupère le 1er résultat correspondant
$orders = $q->fetchAll(); // fetchAll => récupère l'ensemble des résultats correspondant


include 'index.phtml';