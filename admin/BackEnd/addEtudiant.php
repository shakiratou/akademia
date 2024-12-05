<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'bdd_akademia1.0';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe']; // Stockage du mot de passe en clair
    $niveau_etude = $_POST['niveau_etude'];
    $formation_id = $_POST['formation_id'];

    // Valider et déplacer les fichiers téléchargés
    $uploads_dir = __DIR__ . '/uploads';
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0777, true);
    }

    $files = ['acte_naissance', 'carte_identite', 'lettre_motivation', 'diplome_bac'];
    $filePaths = [];

    foreach ($files as $file) {
        if (!empty($_FILES[$file]['tmp_name']) && $_FILES[$file]['error'] === 0) {
            $fileName = basename($_FILES[$file]['name']);
            $filePath = $uploads_dir . '/' . uniqid() . '_' . $fileName;
            if (move_uploaded_file($_FILES[$file]['tmp_name'], $filePath)) {
                $filePaths[$file] = $filePath;
            } else {
                die("Erreur lors du téléchargement de " . htmlspecialchars($file));
            }
        }
    }

    try {
        // Insérer l'étudiant dans la table `etudiant`
        $sql = "INSERT INTO etudiant (nom, prenom, email, mot_de_passe, niveau_etude, formation_id, date_inscription) 
                VALUES (:nom, :prenom, :email, :mot_de_passe, :niveau_etude, :formation_id, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':mot_de_passe' => $mot_de_passe, // Pas de hashage
            ':niveau_etude' => $niveau_etude,
            ':formation_id' => $formation_id,
        ]);

        // Obtenir l'ID de l'étudiant nouvellement inséré
        $etudiant_id = $pdo->lastInsertId();

        // Insérer les documents dans la table `documents`
        $sql_document = "INSERT INTO documents (user_id, type_document, fichier_url, date_televersement) 
                         VALUES (:user_id, :type_document, :fichier_url, NOW())";
        $stmt_document = $pdo->prepare($sql_document);

        foreach ($filePaths as $type_document => $fichier_url) {
            $stmt_document->execute([
                ':user_id' => $etudiant_id,
                ':type_document' => $type_document,
                ':fichier_url' => $fichier_url,
            ]);
        }

        // Rediriger vers la liste des étudiants avec un message de succès
        header("Location: ../gereretudiant.php?success=1");
        exit;
    } catch (PDOException $e) {
        die("Erreur lors de l'insertion : " . $e->getMessage());
    }
}
