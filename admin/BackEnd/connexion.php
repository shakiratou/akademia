<?php 
// Informations de connexion
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$base_de_donnees = "bdd_akademia1.0";

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$base_de_donnees;charset=utf8", $utilisateur, $mot_de_passe);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optionnellement, retirez ce message si vous préférez afficher autre chose dans le frontend
    echo "Connexion réussie à la base de données !";
} catch (PDOException $e) {
    echo "Échec de la connexion à la base de données : " . $e->getMessage();
}
?>
