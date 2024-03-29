<?php

// Connexion BDD
$host = 'mysql:host=localhost;dbname=room';
$login = 'root';
$password = '';
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, //gestion des erreurs
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' //pour forcer l'utf-8
);

//we create our object
$pdo = new PDO($host, $login, $password, $options);


$msg = '';


// Création ou ouverture d'une session :
session_start();

// Déclaration de constantes :
// Constante URL contenant l'url absolue racine du projet
define('URL', 'http://localhost/PHP/room/');

// Constante ROOT_PATH (chemin racine du serveur, sur xampp : C:/xampp/htdocs (utilisée pour l'enregistrement photo sur gestion_salles.php))
// Cette information s'adapte naturellement au serveur, nous n'aurons jamais besoin de la changer
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

// Constante ROOT_SITE (chemin racine du projet depuis la racine serveur (utilisée pour l'enregistrement photo sur gestion_salles.php))
define('ROOT_SITE', '/PHP/room/');

// déclaration d'un indice dans la session afin de passer des messages utilisateur. Pour ne pas perdre les messages, on ne crée cet indice que s'il n'existe pas déjà.
if (!isset($_SESSION['message_utilisateur'])) {
    $_SESSION['message_utilisateur'] = '';
}
