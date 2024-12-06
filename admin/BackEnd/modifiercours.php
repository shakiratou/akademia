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