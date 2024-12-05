<?php
$host = 'localhost';
$dbname = 'bdd_akademia1.0';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si un ID de cours a été passé en paramètre
if (isset($_GET['cours_id']) && !empty($_GET['cours_id'])) {
    $cours_id = $_GET['cours_id'];

    // Préparer la requête de suppression
    $stmt = $pdo->prepare("DELETE FROM cours WHERE idCours = ?");
    $stmt->execute([$cours_id]);

    // Rediriger vers la page de gestion des cours après suppression
    header("Location: gererCours.php");
    exit();
} else {
    echo "ID du cours manquant.";
}
?>
