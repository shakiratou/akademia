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
    $query = $pdo->query("SELECT titreRole FROM RoleEtudiant"); // Récupérer les rôles des étudiants
    $roles = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des rôles : " . $e->getMessage());
}
?>

<?php
// Variables de pagination
$limit = 10; // Nombre de lignes par page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

try {
    // Récupérer le nombre total d'étudiants
    $countQuery = "SELECT COUNT(*) AS total FROM Etudiant";
    $stmt = $pdo->prepare($countQuery);
    $stmt->execute();
    $totalStudents = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalStudents / $limit);

    // Requête pour récupérer les étudiants avec pagination
    $sql = "SELECT e.idEtudiant, e.nomEtudiant, e.prenomEtudiant, e.emailEtudiant, r.titreRole 
            FROM Etudiant e 
            JOIN RoleEtudiant r ON e.titreRole = r.titreRole 
            LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Étudiant - Akademia</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
</head>
<body>

<div id="wrapper">

    <!-- Sidebar -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Sidebar content goes here -->
    </nav>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1>Création <small>d'un Étudiant</small></h1>
                <ol class="breadcrumb">
                    <li><a href="index-2.html"><i class="icon-dashboard"></i> Dashboard</a></li>
                    <li class="active"><i class="icon-file-alt"></i> Ajouter un Étudiant</li>
                </ol>
            </div>

            <form action="BackEnd/addEtudiant.php" method="POST" style="margin-left: 50px">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" id="nom" style="width: 100%;">
                    </div>
                    <div class="col-md-6">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" name="prenom" class="form-control" id="prenom" style="width: 100%;">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="email" style="width: 100%;">
                    </div>
                    <div class="col-md-6">
                        <label for="motDePasse" class="form-label">Mot de passe</label>
                        <input type="password" name="motDePasse" class="form-control" id="motDePasse" style="width: 100%;">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="idRole">Rôle :</label>
                        <select id="titreRole" name="titreRole" class="form-control" style="width: 100%;" required>
                            <option value="">-- Sélectionnez un rôle --</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= htmlspecialchars($role['titreRole']) ?>">
                                    <?= htmlspecialchars($role['titreRole']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary" style="width: 500px;">Ajouter</button>
                    </div>
                </div>
            </form>

            <!-- Tableau pour récupérer les étudiants -->
            <div class="container my-5">
                <h1 class="text-center mb-4">Liste des Étudiants</h1>
                <table class="table table-striped table-bordered" style="margin-left: 50px; width: 1000px">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($etudiants)): ?>
                            <?php foreach ($etudiants as $etudiant): ?>
                                <tr>
                                    <td><?= htmlspecialchars($etudiant['idEtudiant']) ?></td>
                                    <td><?= htmlspecialchars($etudiant['nomEtudiant']) ?></td>
                                    <td><?= htmlspecialchars($etudiant['prenomEtudiant']) ?></td>
                                    <td><?= htmlspecialchars($etudiant['emailEtudiant']) ?></td>
                                    <td><?= htmlspecialchars($etudiant['titreRole']) ?></td>
                                    <td>
                                        <a href="BackEnd/modifier_etudiant.php?id=<?= $etudiant['idEtudiant'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                                        <a href="BackEnd/supprime_etudiant.php?id=<?= $etudiant['idEtudiant'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Aucun étudiant trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <nav aria-label="Pagination" style="margin-left: 50px;">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        </div><!-- /.row -->

    </div><!-- /#page-wrapper -->

</div><!-- /#wrapper -->

<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.js"></script>

</body>
</html>
