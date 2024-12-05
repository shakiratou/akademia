<?php
require_once 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = intval($_POST['id']);  // Convertir en entier
        // Traitez ensuite les autres données...
    } 
} else {
    die("Requête invalide.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $niveau_etude = $_POST['niveau_etude'];
    $formation_id = $_POST['formation_id'];

    try {
        $sql = "UPDATE etudiant SET 
                    nom = :nom, 
                    prenom = :prenom, 
                    email = :email, 
                    mot_de_passe = :mot_de_passe, 
                    niveau_etude = :niveau_etude, 
                    formation_id = :formation_id
                WHERE id = :id";

        $stmt = $connexion->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':mot_de_passe' => $mot_de_passe,
            ':niveau_etude' => $niveau_etude,
            ':formation_id' => $formation_id,
            ':id' => $id,
        ]);

        // Si mise à jour réussie, affichez un message
        $message = "Les informations ont été mises à jour avec succès.";
    } catch (PDOException $e) {
        $message = "Erreur lors de la mise à jour : " . $e->getMessage();
    }
}

// Incluez la page modifierEtudiant.php pour afficher le formulaire
include 'modifierEtudiant.php';
?>
