<?php

declare(strict_types=1);

$notif = null;
$error = null;

//1ere tentative approche
// // Traitement si post (existant et non-vide)
// if (isset($_POST) && !empty($_POST)) {
//     //uploader un fichier tutorial republic attention HTML
        
//         var_dump($_POST);
//         // Extraction du post
//         extract($_POST); // $email, $name
//         // Connexion si besoin !!!!
//         require 'cnx.php';
//         // Check if user exist
//         $productName = $_POST['name']; 
//         $queryName = "INSERT INTO product(name)VALUES('$productName')";
//         $productPrice = $_POST['pha']; 
//         $queryPrice = "INSERT INTO product(price)VALUES('$productPrice')"; 

//         $q = $pdo->prepare('SELECT name, price, picture FROM product VALUES($productName, $productPrice)');
//         $q->execute([
//             'name' => $productName,
//             'price' => $productPrice
//         ]);
//         $user = $q->fetch();


//         // Check if the form was submitted
//         if($_SERVER["REQUEST_METHOD"] == "POST"){
//         // Check if file was uploaded without errors
//         if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0){
//             $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
//             $filename = $_FILES["photo"]["name"];
//             $filetype = $_FILES["photo"]["type"];
//             $filesize = $_FILES["photo"]["size"];
        
//             // Verify file extension
//             $ext = pathinfo($filename, PATHINFO_EXTENSION);
//             if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
        
//             // Verify file size - 5MB maximum
//             $maxsize = 5 * 1024 * 1024;
//             if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
        
//             // Verify MYME type of the file
//             if(in_array($filetype, $allowed)){
//                 // Check whether file exists before uploading it
//                 if(file_exists("image/" . $filename)){
//                     echo $filename . " is already exists.";
//                 } else{
//                     //decider le repertoire d'arrivée
//                     move_uploaded_file($_FILES["photo"]["tmp_name"], "image/" . $filename);
//                     echo "Your file was uploaded successfully.";
//                 } 
//             } else{
//                 echo "Error: There was a problem uploading your file. Please try again."; 
//             }
//         } else{
//             echo "Error: " . $_FILES["photo"]["error"];
//         }
//     }
// }




//autre approche

// Var tmp, qui sert à désigner l'attribut du fichier uploader, dans $_FILES
$picture = null;

// Traitement si post (existant et non-vide)
if (isset($_POST) && !empty($_POST)) {
    // Check Empty fields
    foreach ($_POST as $attrName => $field) {
        if (empty($field)) {
            $error = 'Il y un champ vide';
        }
    }
    if (is_null($error)) {
        // Extraction du post
        extract($_POST); // $name, $pha from $_POST
        // Check name
        if (strlen($name) <= 120) {
            // Appel de la connexion à la bdd
            require 'cnx.php';
            require 'ProductModel.php';
            $productM = new ProductModel($pdo);
            // Check if product exist
            $product = $productM->findByName($name);
            if (!$product) {
                // Check if attr 'name=photo' exist et qu'il n'ya a pas eu d'erreur (error=0)
                // Création de l'attr pour l'envoi dans $_Files
                array_key_exists('photo', $_FILES) ? $picture = 'photo' : null;

                // Appel de dépendance
                require 'FileFormValidator.php';
                // Instanciation
                $fileValidator = new FileFormValidator(
                    $picture,
                    'products/',
                    [
                        "jpg" => "image/jpg",
                        "jpeg" => "image/jpeg"
                    ]
                );
                if (!$fileValidator->isError()) {
                    // Verify file extension
                    if ($fileValidator->isExtensionAllowed()) {
                        // Check whether file exists before uploading it
                        if (!file_exists("products/" . $fileValidator->getFilename())) {
                            // Move in project
                            $fileValidator->moveFileTo();
                            // Insert into bdd
                            $productM->insert([
                                'name' => $name,
                                'pha' => $pha,
                                'photo' => $fileValidator->getFilename()
                            ]);
                            // Notif
                            $notif = 'Tudo Bem';
                        } else $error = 'File already exists';
                    } else $error = 'Invalid file format';
                } else $error = "Error: " . $_FILES["photo"]["error"];
            } else $error = 'Le nom du produit est déjà présent en base';
        } else $error = 'Le nom du produit est trop long';
    }
}

// Affichage du template
include 'index.phtml';