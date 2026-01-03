<?php
$host = "localhost";
$user = "root";
$pwd   = "Patrick110304";
$bd    = "banque";
try {
    $cnx = new PDO("mysql:host=$host;dbname=$bd;charset=utf8", $user, $pwd);
    $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($cnx) {
        echo "Connexion réussie à la base de données : <strong>$bd</strong>";
    }
} catch (Exception $e) {
    die("Échec de connexion : " . $e->getMessage());
}
