<?php
// ------ CONNEXION BDD
$connect_db = new PDO('mysql:host=localhost;dbname=shop', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
]);

// echo '<pre>'; print_r($connect_db); echo '</pre>';


// ------ SESSION
session_start();


// ------ CHEMIN
// echo '<pre>'; print_r($SERVER); echo '</pre>';
define('RACINE_SITE', $_SERVER['DOCUMENT_ROOT'] . '/PHP gg/shop/');
// echo '<pre>'; print_r($RACINE_SITE); echo '</pre>';
// Cette constante retourne le chemin physique du dossier htdocs sur le serveur, de notre dossier 'shop' sur le serveur.
// Lors de l'enregistrement d'images/photos , nous aurons besoin du chemin complet dossier images pour enregistrer la photo
// echo RACINE_SITE . 'shop/assets/images/product.jpg';



define("URL", "http://localhost/PHP gg/shop/");
// <img src="URL
// Cette constante servira à enregistrer l'URL d'une photo/image dans la BDD, on ne pas conserver la photo physiquement dand la BDD, donc on définit une URL vers le bon dossier.


// ------------ VARIABLES
$content = '';


//------------ FAILLES XSS
foreach ($_POST as $key => $value) {
     $_POST[$key] = htmlentities(addslashes(trim($value)));
}

foreach ($_GET as $key => $value) {
    $_GET[$key] = htmlentities(addslashes(trim($value)));
}
// trim() : fonction prédéfinie qui supprime les espaces en début et fin de chaînes de caractères.

//------------ INCLUSIONS FONCTIONS
require_once("functions.php");

