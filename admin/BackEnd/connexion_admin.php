<?php
session_start(); // Démarrer la session
require_once 'connexion.php'; // Inclure le fichier de connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données soumises via le formulaire
    $emailAdmin = $_POST['emailAdmin'] ?? '';
    $motDePasseAdmin = $_POST['motDePasseAdmin'] ?? '';

    // Vérifier que les champs ne sont pas vides
    if (empty($emailAdmin) || empty($motDePasseAdmin)) {
        echo "Veuillez remplir tous les champs.";
        exit;
    }

    try {
        // Requête pour récupérer les informations de l'administrateur à partir de l'email
        $stmt = $connexion->prepare("SELECT * FROM administrateur WHERE emailAdmin = :emailAdmin");
        $stmt->execute([':emailAdmin' => $emailAdmin]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            // Vérifier le mot de passe
            if (password_verify($motDePasseAdmin, $admin['motDePasseAdmin'])) {
                // Vérifier le rôle (titreRole)
                if ($admin['titreRole'] === 'admin') {
                    // Stocker les informations dans la session
                    $_SESSION['idAdmin'] = $admin['idAdmin'];
                    $_SESSION['emailAdmin'] = $admin['emailAdmin'];
                    $_SESSION['role'] = $admin['titreRole'];

                    // Rediriger vers le tableau de bord
                    header('Location: http://localhost/akademia/admin/index.php');
                    exit;
                } else {
                    echo "Accès réservé aux administrateurs uniquement.";
                }
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Aucun administrateur trouvé avec cet email.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la connexion : " . $e->getMessage();
    }
} else {
    // Si l'accès au fichier se fait sans soumission, rediriger vers la page de connexion
    header('Location: http://localhost/akademia/admin/index.php');
    exit;
}
