
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
    $query = $pdo->query("SELECT titreRole FROM RoleAdmin");
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
    // Récupérer le nombre total d'administrateurs
    $countQuery = "SELECT COUNT(*) AS total FROM Administrateur";
    $stmt = $pdo->prepare($countQuery);
    $stmt->execute();
    $totalAdmins = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalAdmins / $limit);

    // Requête pour récupérer les administrateurs avec pagination
    $sql = "SELECT a.idAdmin, a.nomAdmin, a.prenomAdmin, a.emailAdmin, r.titreRole 
            FROM Administrateur a 
            JOIN RoleAdmin r ON a.titreRole = r.titreRole 
            LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $administrateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from learning-zone.github.io/website-templates/sb-admin/blank-page.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Nov 2024 21:48:58 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Creer admin - Akademia</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
  </head>

  <body>

    <div id="wrapper">

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
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="gererCours.php"><i class="fa fa-edit"></i> Gestion des cours</a></li>
            <li><a href="tables.html"><i class="fa fa-table"></i> Gestion des évaluations</a></li>
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
            <h1>Creation <small>d'un Admin</small></h1>
            <ol class="breadcrumb">
              <li><a href="index-2.html"><i class="icon-dashboard"></i> Dashboard</a></li>
              <li class="active"><i class="icon-file-alt"></i> Ajouter un Admin</li>
            </ol>
          </div>

          <form action="BackEnd/addAdmin.php" method="POST" style="margin-left: 50px">
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" id="nom" style="width: 100%;">
              </div>
              <div class="col-md-6">
                <label for="prenom" class="form-label">Prenom</label>
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
              </div> <br>
              <div class="col-md-6">
                <button type="submit" class="btn btn-primary" style="width: 500px;">Ajouter</button>
              </div>
              </div>
            </div>
          </form>


            <!-- tableau pour recuperer les administrateur -->

      <div class="container my-5">
        <h1 class="text-center mb-4">Liste des Administrateurs</h1>
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
                <?php if (!empty($administrateurs)): ?>
                    <?php foreach ($administrateurs as $admin): ?>
                        <tr>
                            <td><?= htmlspecialchars($admin['idAdmin']) ?></td>
                            <td><?= htmlspecialchars($admin['nomAdmin']) ?></td>
                            <td><?= htmlspecialchars($admin['prenomAdmin']) ?></td>
                            <td><?= htmlspecialchars($admin['emailAdmin']) ?></td>
                            <td><?= htmlspecialchars($admin['titreRole']) ?></td>
                            <td>
                                <a href="BackEnd/modifier_admin.php?id=<?= $admin['idAdmin'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                                <a href="BackEnd/supprime_admin.php?id=<?= $admin['idAdmin'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Aucun administrateur trouvé.</td>
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

    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>

  </body>

<!-- Mirrored from learning-zone.github.io/website-templates/sb-admin/blank-page.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Nov 2024 21:48:58 GMT -->
</html>