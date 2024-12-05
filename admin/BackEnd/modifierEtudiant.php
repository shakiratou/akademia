<?php
require_once 'connexion.php'; // Fichier de connexion à la base de données


// Vérifier si un ID est passé dans l'URL
if (isset($_GET['id'])) {
    $idEtudiant = intval($_GET['id']);
    
    // Récupérer les données de l'étudiant à modifier
    $stmt = $connexion->prepare("SELECT * FROM etudiant WHERE id = :id");
    $stmt->execute([':id' => $idEtudiant]);
    $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$etudiant) {
        die("Étudiant non trouvé.");
    }

    // Récupérer les formations pour la liste déroulante
    $stmtFormations = $connexion->query("SELECT id, nom FROM formations");
    $formations = $stmtFormations->fetchAll(PDO::FETCH_ASSOC);
} else {
    die("Aucun ID d'étudiant spécifié.");
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Étudiant</title>
    <link rel="stylesheet" href="votre_chemin_vers_bootstrap.css"> <!-- Ajoutez vos styles -->
</head>
<body>
<div class="container">
    <h2 class="text-center my-5">Modifier les informations de l'étudiant</h2>
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
                <label for="niveau_etude">Niveau d'étude :</label>
                <select name="niveau_etude" class="form-control" id="niveau_etude" required>
                    <option value="bachelier" <?= $etudiant['niveau_etude'] === 'bachelier' ? 'selected' : '' ?>>Bachelier</option>
                    <option value="etudiant" <?= $etudiant['niveau_etude'] === 'etudiant' ? 'selected' : '' ?>>Étudiant ayant déjà débuté</option>
                    <option value="professionnel" <?= $etudiant['niveau_etude'] === 'professionnel' ? 'selected' : '' ?>>Professionnel</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="formation_id">Formation :</label>
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
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>
</div>
<script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
</body>
</html>
