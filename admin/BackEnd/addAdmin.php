<?php
require_once 'connexion.php'; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titreRole = htmlspecialchars($_POST['titreRole']); 
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $motDePasse = htmlspecialchars($_POST['motDePasse']);
    if (!empty($nom)) {
        try {
            $sql = "INSERT INTO administrateur (titreRole, nomAdmin, prenomAdmin, emailAdmin, motDePasseAdmin) VALUES (:titreRole, :nom, :prenom, :email, :motDePasse)";
            $stmt = $connexion->prepare($sql);
            $stmt->execute([
                ':titreRole' => $titreRole,
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':motDePasse' => $motDePasse
            ]);
            echo "un nouvel admin ajoutée avec succes!";
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        echo "Veuillez remplir le champ nom de utilisateur.";
    }
    if($query_run)
    {
        $_SESSION['message'] = "nouvel admin enregistrée avec success!";
        header("Location: ../ajoutAdmin.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "admin pas enregistrée!";
        header("Location: ../ajoutAdmin.php");
        exit(0);
    }
}
?>
