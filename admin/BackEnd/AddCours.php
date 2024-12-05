<?php
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
try {
    $sql = "SELECT id, nom FROM formations"; // Assurez-vous que 'formation' est le bon nom de votre table
    $stmt = $pdo->query($sql);
    $formations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des formations : " . $e->getMessage());
}

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titreFormation = $_POST['titreFormation'];
    $titreCours = $_POST['titreCours'];
    $descriptionCours = $_POST['descriptionCours'];
    $instructeur = $_POST['idAdmin'];
    $niveauCours = $_POST['niveauCours'];
    $contenuCours = null;

    if (isset($_FILES['contenuCours']) && $_FILES['contenuCours']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['contenuCours']['tmp_name'];
        $fileName = $_FILES['contenuCours']['name'];
        $fileSize = $_FILES['contenuCours']['size'];
        $fileType = $_FILES['contenuCours']['type'];
        $uploadFolder = 'uploads/';
        
        // Assurez-vous que le dossier d'upload existe
        if (!is_dir($uploadFolder)) {
            mkdir($uploadFolder, 0777, true);
        }
        
        // Vérification de l'extension et du type MIME
        $allowedExtensions = ['pdf', 'mp4', 'mpeg', 'avi'];
        $allowedFileTypes = ['application/pdf', 'video/mp4', 'video/mpeg', 'video/avi'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        if (in_array($fileExtension, $allowedExtensions) && in_array($fileType, $allowedFileTypes)) {
            // Limite de taille du fichier : 100 Mo
            $maxFileSize = 100 * 1024 * 1024;
            if ($fileSize > $maxFileSize) {
                echo "Le fichier est trop volumineux. Taille maximale autorisée : 100 Mo.";
                exit;
            }
            
            // Renommer le fichier pour éviter les conflits
            $fileName = time() . '_' . $fileName;
            $destPath = $uploadFolder . $fileName;
            
            // Déplacement du fichier
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $contenuCours = $destPath; // Chemin à stocker en base de données
            } else {
                echo "Erreur lors de l'enregistrement du fichier.";
                exit;
            }
        } else {
            echo "Type ou extension de fichier non autorisé. Veuillez uploader un fichier PDF ou une vidéo.";
            exit;
        }
    } else {
        echo "Aucun fichier n'a été téléchargé ou une erreur est survenue.";
        exit;
    }
    

    // Insertion des données dans la base de données
    try {
        $sql = "INSERT INTO cours (titreFormation, titreCours, descriptionCours, contenuCours, idAdmin, niveauCours) 
                VALUES (:titreFormation, :titreCours, :descriptionCours, :contenuCours, :instructeur, :niveauCours)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':titreFormation' => $titreFormation,
            ':titreCours' => $titreCours,
            ':descriptionCours' => $descriptionCours,
            ':contenuCours' => $contenuCours,
            ':instructeur' => $instructeur,
            ':niveauCours' => $niveauCours
        ]);
    
        echo "Cours enregistré avec succès !";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
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
