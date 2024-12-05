<?php
require_once 'connexion.php'; // Assurez-vous que ce fichier contient la connexion PDO à votre base de données

if (isset($_GET['id'])) {
    // Récupérer et valider l'ID
    $idEtudiant = intval($_GET['id']);

    try {
        // Supprimer l'étudiant dans la table "etudiant"
        $sql = "DELETE FROM etudiant WHERE id = :idEtudiant";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([':idEtudiant' => $idEtudiant]);

        // Redirection vers la page de gestion des étudiants après la suppression
        header('Location: ../gereretudiant.php');
        exit;
    } catch (PDOException $e) {
        die("Erreur lors de la suppression : " . $e->getMessage());
    }
} else {
    echo "Aucun ID spécifié.";
}
?>
