<?php
require_once 'connexion.php'; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titreRole = htmlspecialchars($_POST['titreRole']);
    if (!empty($titreRole)) {
        try {
            $sql = "INSERT INTO roleadmin (titreRole) VALUES (:titreRole)";
            $stmt = $connexion->prepare($sql);
            $stmt->execute([
                ':titreRole' => $titreRole,
                
            ]);
            echo "un nouveau role ajouté avec succes!";
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        echo "Veuillez remplir le champ nom de utilisateur.";
    }
    if($query_run)
    {
        $_SESSION['message'] = "nouveau role enregistrée avec success!";
        header("Location: ../gererRole.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "role pas enregistrée!";
        header("Location: ../gererRole.php");
        exit(0);
    }
}
?>
