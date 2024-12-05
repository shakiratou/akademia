<?php
require_once 'connexion.php'; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $description = htmlspecialchars($_POST['description']);
    $duree = htmlspecialchars($_POST['duree']);
    if (!empty($nom)) {
        try {
            $sql = "INSERT INTO formations (nom,description,duree) VALUES (:nom, :description, :duree)";
            $stmt = $connexion->prepare($sql);
            $stmt->execute([
                ':nom' => $nom,
                ':description' => $description,
                ':duree' => $duree
            ]);
            echo "un formation a été ajoutée avec succès.";
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        echo "Veuillez remplir le champ de la formation.";
    }
    if($query_run)
    {
        $_SESSION['message'] = "formation enregistrée avec success!";
        header("Location: ../gererCours.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "formation pas enregistrée!";
        header("Location: ../gererCours.php");
        exit(0);
    }
}
?>
