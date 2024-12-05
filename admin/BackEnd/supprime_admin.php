<?php
require_once 'connexion.php';

if (isset($_GET['id'])) {
    $idAdmin = intval($_GET['id']);
    try {
        $sql = "DELETE FROM Administrateur WHERE idAdmin = :idAdmin";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([':idAdmin' => $idAdmin]);
        header('Location: ../ajoutAdmin.php');
        exit;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    echo "Aucun ID spécifié.";
}
?>
