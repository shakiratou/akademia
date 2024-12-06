<?php
session_start();

// Vérifiez si la session contient les informations nécessaires
if (
    !isset($_SESSION['idAdmin']) ||
    !isset($_SESSION['emailAdmin']) ||
    !isset($_SESSION['nomAdmin']) ||
    !isset($_SESSION['prenomAdmin'])
) {
    echo "Erreur : Vous devez être connecté pour accéder à cette page.";
    exit;
}

// Si l'utilisateur est connecté, récupérez ses informations
$idAdmin = $_SESSION['idAdmin'];
$emailAdmin = $_SESSION['emailAdmin'];
$nomAdmin = $_SESSION['nomAdmin'];
$prenomAdmin = $_SESSION['prenomAdmin'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Administrateur</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            padding: 20px;
            text-align: center;
        }
        h1 {
            font-size: 1.8em;
            margin-bottom: 10px;
            color: #4CAF50;
        }
        p {
            margin: 8px 0;
            font-size: 1em;
            color: #555;
        }
        .info {
            background: #f9f9f9;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        a {
            display: inline-block;
            text-decoration: none;
            color: #fff;
            background-color: #4CAF50;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Profil Administrateur</h1>
        <div class="info">
            <p><strong>Prénom :</strong> <?= htmlspecialchars($prenomAdmin); ?></p>
            <p><strong>Nom :</strong> <?= htmlspecialchars($nomAdmin); ?></p>
            <p><strong>Messagerie électronique :</strong> <?= htmlspecialchars($emailAdmin); ?></p>
        </div>
        <a href="deconnexion.php">Se déconnecter</a>
    </div>
</body>
</html>
