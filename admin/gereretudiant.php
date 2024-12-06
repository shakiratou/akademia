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
    // Récupérer les formations pour l'inscription
    $query = $pdo->query("SELECT id, nom FROM formations");
    $formations = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des formations : " . $e->getMessage());
}

try {
    // Récupérer les étudiants avec pagination
    $limit = 10; 
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Récupérer le nombre total d'étudiants
    $countQuery = "SELECT COUNT(*) AS total FROM etudiant";
    $stmt = $pdo->prepare($countQuery);
    $stmt->execute();
    $totalEtudiants = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalEtudiants / $limit);

    // Requête pour récupérer les étudiants avec pagination
    $sql = "SELECT e.id, e.nom, e.prenom, e.email, e.niveau_etude, f.nom AS formation
            FROM etudiant e 
            JOIN formations f ON e.formation_id = f.id
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
    <!-- Sidebar -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index-2.html">SB Admin</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav side-nav">
            <li><a href="accueil.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="gererCours.php"><i class="fa fa-edit"></i> Gestion des cours</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-square-o-down"></i> Listes<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="gereretudiant.php">Gestion des etudiants</a></li>
                <li><a href="ajoutAdmin.php">Gestion des admin</a></li>
                <li><a href="gererRole.php">Gestion des roles</a></li>

              </ul>
            </li>
          </ul>

          <ul class="nav navbar-nav navbar-right navbar-user">
            </li>
            <li class="dropdown user-dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> John Smith <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#"><i class="fa fa-user"></i> Profile</a></li>
                <li class="divider"></li>
                <li><a href="#"><i class="fa fa-power-off"></i> Log Out</a></li>
              </ul>
            </li>
          </ul>
        </div><!-- /.navbar-collapse -->
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

            <form action="BackEnd/addEtudiant.php" method="POST" enctype="multipart/form-data" style="margin-left: 50px">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control" id="nom" required>
                        </div>
                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" name="prenom" class="form-control" id="prenom" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="mot_de_asse" class="form-label">Mot de passe</label>
                            <input type="password" name="mot_de_passe" class="form-control" id="mot_de_passe" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="niveau_etude">Niveau d'étude :</label>
                            <select name="niveau_etude" class="form-control" id="niveau_etude" required>
                                <option value="bachelier">Bachelier</option>
                                <option value="etudiant">Etudiant ayant déja débuté dans une faculté</option>
                                <option value="professionnel">Professionnel en reconversion</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="formation_id">Formation :</label>
                            <select name="formation_id" class="form-control" id="formation_id" required>
                                <option value="">-- Sélectionnez une formation --</option>
                                <?php foreach ($formations as $formation): ?>
                                    <option value="<?= htmlspecialchars($formation['id']) ?>">
                                        <?= htmlspecialchars($formation['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="document1">Acte de naissance</label>
                            <input type="file" name="acte_naissance" accept=".pdf, .jpg, .png"  class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="document2">Carte d'identité</label>
                            <input type="file" name="carte_identite" accept=".pdf, .jpg, .png" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="document3">Lettre de motivation</label>
                            <input type="file" name="lettre_motivation" accept=".pdf" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="document4">Diplome du Bac</label>
                            <input type="file" name="diplome_bac" accept=".pdf" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12" style=" margin: bottom 10px;">
                            <button type="submit" class="btn btn-primary" style="width: 500px;">Ajouter Etudiant</button>
                        </div>
                    </div>
                </form>

            <!-- Tableau des étudiants -->
            <div class="container my-5">
                    <h2 class="text-center mb-4">Liste des étudiants</h2>
                    <table class="table table-striped table-bordered" style="margin-left: 50px; width: 1000px">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Niveau d'étude</th>
                                <th>Formation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($etudiants)): ?>
                                <?php foreach ($etudiants as $etudiant): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($etudiant['id']) ?></td>
                                        <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                                        <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
                                        <td><?= htmlspecialchars($etudiant['email']) ?></td>
                                        <td><?= htmlspecialchars($etudiant['niveau_etude']) ?></td>
                                        <td><?= htmlspecialchars($etudiant['formation']) ?></td>
                                        <td>
                                            
                                            <a href="BackEnd/modifierEtudiant.php?id=<?= $etudiant['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                                            <a href="BackEnd/supEtudiant.php?id=<?= $etudiant['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">Supprimer</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Aucun étudiant trouvé.</td>
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
            </div>
        </div>
    </div>

    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
</body>

</html>
