<?php
session_start(); // Démarrer la session
require_once 'connexion.php'; // Inclure le fichier de connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données soumises via le formulaire
    $emailAdmin = trim($_POST['emailAdmin'] ?? '');
    $motDePasseAdmin = trim($_POST['motDePasseAdmin'] ?? '');

    // Vérifier que les champs ne sont pas vides
    if (empty($emailAdmin) || empty($motDePasseAdmin)) {
        echo "Veuillez remplir tous les champs."; // Message d'erreur
        exit;
    }

    try {
        // Requête pour récupérer les informations de l'administrateur correspondant à l'email
        $stmt = $connexion->prepare("SELECT * FROM administrateur WHERE emailAdmin = :emailAdmin");
        $stmt->execute([':emailAdmin' => $emailAdmin]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            // Vérifier le mot de passe
            if ($motDePasseAdmin === $admin['motDePasseAdmin']) {
                // Vérifier si le rôle est correct
                if ($admin['titreRole'] === 'admin') {
                    // Stocker les informations dans la session
                    $_SESSION['idAdmin'] = $admin['idAdmin'];
                    $_SESSION['emailAdmin'] = $admin['emailAdmin'];
                    $_SESSION['nomAdmin'] = $admin['nomAdmin'];
                    $_SESSION['prenomAdmin'] = $admin['prenomAdmin'];
                    $_SESSION['role'] = $admin['titreRole'];

                    // Rediriger vers le tableau de bord de l'administrateur
                    header('Location: ../accueil.php'); // Vérifiez le chemin
                    exit;
                    var_dump($_SESSION); // Affiche la session pour vérifier son contenu
                    exit; // Arrête l'exécution pour voir le résultat

                } else {
                    echo "Accès réservé aux administrateurs uniquement."; // Rôle incorrect
                }
            } else {
                echo "Mot de passe incorrect."; // Mot de passe incorrect
            }
        } else {
            echo "Aucun administrateur trouvé avec cet email."; // Aucune correspondance dans la base
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la connexion à la base de données : " . $e->getMessage(); // Erreur lors de la requête SQL
    }
} else {
    // Rediriger vers le formulaire si la méthode de requête n'est pas POST
    header('Location: ../login/index.php');
    exit;
}
?>
