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
  $stmt = $pdo->query("SELECT id, nom FROM formations");
  $formations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Erreur lors de la récupération des formations : " . $e->getMessage());
}

// Récupérer les cours existants pour le formulaire de modification
if (isset($_GET['idCours'])) {
  $cours_id = $_GET['idCours'];
  $stmt = $pdo->prepare("SELECT * FROM cours WHERE idCours = :idCours");
  $stmt->execute(['idCours' => $idCours]);
  $cours = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>



<?php

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
try {
    $query = $pdo->query("SELECT idAdmin FROM administrateur");
    $administrateurs = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des rôles : " . $e->getMessage());
}
?>




<!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from learning-zone.github.io/website-templates/sb-admin/forms.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Nov 2024 21:48:58 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cours - Akedemia</title>

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
          <a class="navbar-brand" href="index-2.html">Admin</a>
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
                <li><a href="#">Gestion des etudiants</a></li>
                <li><a href="ajoutAdmin.php">Gestion des admin</a></li>
                <li><a href="gererRole.php">Gestion des roles</a></li>

              </ul>
            </li>
          </ul>

          <ul class="nav navbar-nav navbar-right navbar-user">
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
            <h1>Enregistrez les cours</h1>
            <ol class="breadcrumb">
              <li><a href="index-2.html"><i class="fa fa-dashboard"></i> Dashboard</a></li>
              <li class="active"><i class="fa fa-edit"></i> Gestion des cours</li>
            </ol>
          </div>
        </div><!-- /.row -->

        <div class="row" style="display: block;">
          <div class="col-lg-6">
            <!-- debut du formulaire d'ajouter une categorie de cours -->
            <form role="form" action="BackEnd/addCategorie.php" method="POST" style="display: inline;">
            <h2>Ajoutez une formation</h2>
              <div class="form-group">
                <label for="nom">Nom de la formation</label>
                <input class="form-control" name="nom" style="width: 50%">
              </div>
              <div class="form-group">
                <label for="nom">Descrition de la formation</label>
                <input class="form-control" name="description" style="width: 50%">
              </div>
              <div class="form-group">
                <label for="nom">Durée de la formation</label>
                <input class="form-control" name="duree" style="width: 50%">
              </div>
              <button type="submit" class="btn btn-default">Ajouter</button>
            </form>
            <!-- debut du formulaire d'ajout des cours -->
            <form action="BackEnd/addCours.php" method="POST" enctype="multipart/form-data">
              <div id="emailHelp" class="form-text"><h2>Créer un cours</h2></div>
              
              <div class="row mb-3">

              <div class="col-md-6" style="margin-top: 20px">
                <label for="titreFormation">Libellé de la formation</label>
                <select id="titreFormation" name="titreFormation" class="form-control" style="width: 100%;" required>
                    <option value="">-- Sélectionnez une formation --</option>
                    <?php foreach ($formations as $formation): ?>
                        <option value="<?= htmlspecialchars($formation['id']) ?>">
                            <?= htmlspecialchars($formation['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
               </div>
                <br>

                <div class="col-md-6">
                  <label for="titreCours" class="form-label">Titre du cours</label>
                  <input type="text" name="titreCours" class="form-control" id="titreCours" aria-describedby="emailHelp">
                </div>
              </div>
              
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="descriptionCours" class="form-label">Description du cours</label>
                  <input type="text" name="descriptionCours" class="form-control" id="descriptionCours" aria-describedby="emailHelp">
                </div>

                <div class="col-md-6">
                    <label for="contenuCours" class="form-label">Contenu du cours (vidéo ou PDF)</label>
                    <input type="file" name="contenuCours" class="form-control" id="contenuCours" accept="application/pdf, video/*" required>
                </div>
              </div>
              
              <div class="row mb-3">
                <div class="col-md-6" style="margin-top: 20px">
                <label for="idAdmin">Identifiant de l'instructeurs</label>
                <select id="idAdmin" name="idAdmin" class="form-control" style="width: 100%;" required>
                  <option value="">-- Sélectionnez un instructeur --</option>
                  <?php foreach ($administrateurs as $administrateur): ?>
                    <option value="<?= htmlspecialchars($administrateur['idAdmin']) ?>">
                      <?= htmlspecialchars($administrateur['idAdmin']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
                <div class="col-md-6">
                  <label for="niveauCours" class="form-label">Niveau du cours</label>
                  <input type="text" name="niveauCours" class="form-control" id="niveauCours">
                </div>
              </div><br>
              <button type="submit" class="btn btn-primary">Soumettre</button>
            </form>

                        

            <!-- fin du formulaire d'ajout des cours -->
          </div>
        
        </div><!-- /.row -->

      </div><!-- /#page-wrapper -->

    </div><!-- /#wrapper -->

    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>

  </body>

<!-- Mirrored from learning-zone.github.io/website-templates/sb-admin/forms.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Nov 2024 21:48:58 GMT -->
</html>