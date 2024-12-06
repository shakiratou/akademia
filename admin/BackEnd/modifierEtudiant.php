<?php
require_once 'connexion.php'; // Fichier de connexion à la base de données

// Vérifier si un ID est passé dans l'URL
if (isset($_GET['id'])) {
    $idEtudiant = intval($_GET['id']); // Récupérer l'ID de l'étudiant

    // Récupérer les données de l'étudiant à modifier
    $stmt = $connexion->prepare("SELECT * FROM etudiant WHERE id = :id");
    $stmt->execute([':id' => $idEtudiant]);
    $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'étudiant existe
    if (!$etudiant) {
        // Si l'étudiant n'existe pas, rediriger vers la page de gestion des étudiants
        header('Location: gererEtudiant.php');
        exit;
    }

    // Récupérer les formations pour la liste déroulante
    $stmtFormations = $connexion->query("SELECT id, nom FROM formations");
    $formations = $stmtFormations->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Si l'ID de l'étudiant n'est pas passé dans l'URL, rediriger
    header('Location: ../gererEtudiant.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Étudiant</title>
    
    <!-- Lien vers Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Styles personnalisés -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin-top: 50px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-weight: bold;
            color: #007bff;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        
        label {
            font-weight: bold;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.5);
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center mb-5">Modifier les informations de l'étudiant</h2>
    <form action="updateEtudiant.php" method="POST" enctype="multipart/form-data">
        <!-- ID caché -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($etudiant['id']) ?>">
        
        <!-- Formulaire -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" id="nom" value="<?= htmlspecialchars($etudiant['nom']) ?>" required>
            </div>
            <div class="col-md-6">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" name="prenom" class="form-control" id="prenom" value="<?= htmlspecialchars($etudiant['prenom']) ?>" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" value="<?= htmlspecialchars($etudiant['email']) ?>" required>
            </div>
            <div class="col-md-6">
                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                <input type="password" name="mot_de_passe" class="form-control" id="mot_de_passe" value="<?= htmlspecialchars($etudiant['mot_de_passe']) ?>" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="niveau_etude" class="form-label">Niveau d'étude</label>
                <select name="niveau_etude" class="form-control" id="niveau_etude" required>
                    <option value="bachelier" <?= $etudiant['niveau_etude'] === 'bachelier' ? 'selected' : '' ?>>Bachelier</option>
                    <option value="etudiant" <?= $etudiant['niveau_etude'] === 'etudiant' ? 'selected' : '' ?>>Étudiant ayant déjà débuté</option>
                    <option value="professionnel" <?= $etudiant['niveau_etude'] === 'professionnel' ? 'selected' : '' ?>>Professionnel</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="formation_id" class="form-label">Formation</label>
                <select name="formation_id" class="form-control" id="formation_id" required>
                    <option value="">-- Sélectionnez une formation --</option>
                    <?php foreach ($formations as $formation): ?>
                        <option value="<?= $formation['id'] ?>" <?= $etudiant['formation_id'] == $formation['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($formation['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Enregistrer les modifications</button>
    </form>
</div>

<!-- Lien vers Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
