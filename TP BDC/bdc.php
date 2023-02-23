<?php

// Paramètre reçu depuis la page précédente
//var_dump($_GET);

// Evaluation du param
if (!array_key_exists('num_order', $_GET) || array_key_exists('num_order', $_GET) && empty($_GET['num_order'])) {
    // Redirection
    header('Location: index.php');
    exit;
}

// Connexion à la bdd
/*
    dsn =>
        bdd => mysql :
        host => localhost (sur le serveur local) ;
        dbname = le nopm de votre bdd
    ,
    user => identifiant
    ,
    password => votre_mdp
    ,
    option => array() (obligatoirement sous la forme d'un array)
*/
$pdo = new PDO('mysql:host=localhost;dbname=classicmodels;charset=utf8',
    'root',
    'root',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

// 3 requêtes (1 - Get Customer / 2 - Get product's order / 3 - Compute total amount)
// Customer
$sql =
    'Select customerName, contactLastName, contactFirstName, addressLine1, postalCode, city, orderNumber, status, orderDate
    from customers, orders
    where orders.customerNumber = customers.customerNumber
    and orderNumber = :num';

// En mode Inner Join
/*$sqlJ = 'Select customerName, contactLastName, contactFirstName, AddressLine1, postalCode, city
    from customers
    inner join orders on orders.customerNumber = customers.customerNumber
    where orderNumber = :num';
*/

$q = $pdo->prepare($sql);
$q->execute([':num' => $_GET['num_order'] ]);
// Récupération du résultat
$customer = $q->fetch();

// Products
$sql =
    'Select productName, priceEach, quantityOrdered, priceEach * quantityOrdered as total_line
    from orderdetails
    inner join products on products.productCode = orderdetails.productCode
    where orderNumber = :num';
$q = $pdo->prepare($sql);
$q->execute([':num' => $_GET['num_order'] ]);
// Récupération du résultat
$products = $q->fetchAll();


// Amount total
$sql =
    'Select SUM(priceEach * quantityOrdered) as total_line
    from orderdetails
    where orderNumber = :num';
$q = $pdo->prepare($sql);
$q->execute([':num' => $_GET['num_order'] ]);
// Récupération du résultat
$total = $q->fetch();

// Compute total
$tat = floatval($total['total_line']);

$totalAmountWithTva = $tat * 0.20;
$totalAmountTtc = $tat + $totalAmountWithTva;

include 'bdc.phtml';
