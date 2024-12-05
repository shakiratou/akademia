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
// Requête pour compter les étudiants inscrits
$query = "SELECT COUNT(*) AS totalEtudiants FROM etudiant"; // Remplacez 'etudiants' par le nom de votre table d'étudiants
$stmt = $pdo->prepare($query);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer le nombre d'étudiants
$nombreEtudiants = $result['totalEtudiants'];

// Requête pour compter les formations
$query = "SELECT COUNT(*) AS totalFormations FROM formations"; // Remplacez 'formations' par le nom de votre table des formations
$stmt = $pdo->prepare($query);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer le nombre de formations
$nombreFormations = $result['totalFormations'];

// Requête pour compter les cours
$query = "SELECT COUNT(*) AS totalCours FROM cours"; // Remplacez 'cours' par le nom de votre table des cours
$stmt = $pdo->prepare($query);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer le nombre de cours
$nombreCours = $result['totalCours'];

// Requête pour compter les rôles dans la table roleadmin
$query = "SELECT COUNT(*) AS totalRoles FROM roleadmin";
$stmt = $pdo->prepare($query);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer le nombre total de rôles
$nombreRoles = $result['totalRoles'];

// Requête pour récupérer les étudiants inscrits
$query = "SELECT nom, prenom, email FROM etudiant"; // Remplacez 'etudiants' par le nom de votre table
$stmt = $pdo->prepare($query);
$stmt->execute();
$etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC); 

// Requête pour récupérer les formations
$query = "SELECT id, nom FROM formations"; // Remplacez 'formations' par le nom de votre table
$stmt = $pdo->prepare($query);
$stmt->execute();
$formations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Requête pour récupérer les formations et leurs cours
$query = "
    SELECT f.nom, c.titreCours 
    FROM formations f
    LEFT JOIN cours c ON f.id = c.titreFormation
    ORDER BY f.nom, c.titreCours
";
$stmt = $pdo->prepare($query);
$stmt->execute();
$formationsEtCours = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organiser les données dans un tableau associatif par formation
$formationsAvecCours = [];
foreach ($formationsEtCours as $row) {
    $formation = $row['nom']; // Nom de la formation
    $cours = $row['titreCours']; // Titre du cours

    if (!isset($formationsAvecCours[$formation])) {
        $formationsAvecCours[$formation] = [];
    }
    if ($cours) {
        $formationsAvecCours[$formation][] = $cours;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from learning-zone.github.io/website-templates/sb-admin/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Nov 2024 21:48:46 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard - SB Admin</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <!-- Page Specific CSS -->
    <link rel="stylesheet" href="../../../cdn.oesmith.co.uk/morris-0.4.3.min.css">
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
            <h1>Dashboard <small>Statistics Overview</small></h1>
            <ol class="breadcrumb">
              <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
            </ol>
            
          </div>
        </div><!-- /.row -->

        <div class="row">
          <div class="col-lg-3">
            <div class="panel panel-info">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-6">
                  <i class="fa fa-users fa-5x"></i> <!-- Changez l'icône si nécessaire -->
                </div>
                <div class="col-xs-6 text-right">
                  <p class="announcement-heading"><?php echo $nombreEtudiants; ?></p> <!-- Affiche le nombre d'étudiants -->
                  <p class="announcement-text">Étudiants inscrits</p>
                </div>
              </div>
            </div>

              
            </div>
          </div>
          <div class="col-lg-3">
            <div class="panel panel-warning">
            <div class="panel-heading">
              <div class="row">
                  <div class="col-xs-6">
                      <i class="fa fa-check fa-5x"></i> <!-- Icône de validation -->
                  </div>
                  <div class="col-xs-6 text-right">
                      <p class="announcement-heading"><?php echo $nombreFormations; ?></p> <!-- Nombre de formations -->
                      <p class="announcement-text">Formations disponibles</p>
                  </div>
                </div>
              </div>

              
            </div>
          </div>
          <div class="col-lg-3">
            <div class="panel panel-danger">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-6">
                  <i class="fa fa-tasks fa-5x"></i> <!-- Icône de tâches -->
                </div>
                <div class="col-xs-6 text-right">
                  <p class="announcement-heading"><?php echo $nombreCours; ?></p> <!-- Nombre de cours -->
                  <p class="announcement-text">Cours disponibles</p>
                </div>
              </div>
            </div>

              
            </div>
          </div>
          <div class="col-lg-3">
          <div class="panel panel-success">
            <div class="panel-heading">
                  <div class="row">
                    <div class="col-xs-6">
                      <i class="fa fa-comments fa-5x"></i> <!-- Icône de commentaires -->
                    </div>
                    <div class="col-xs-6 text-right">
                      <p class="announcement-heading"><?php echo $nombreRoles; ?></p> <!-- Nombre de rôles -->
                      <p class="announcement-text">Rôles Administrateurs</p>
                    </div>
                  </div>
            </div>
          </div>  

              
            </div>
          </div>
        </div><!-- /.row -->

        

        <div class="row">
        <div class="col-lg-4">
          <div class="panel panel-primary">
              <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Liste des étudiants inscrits</h3>
              </div>
              <div class="panel-body">
                  <ul>
                      <?php if (count($etudiants) > 0): ?>
                          <?php foreach ($etudiants as $etudiant): ?>
                              <li>
                                  <strong><?php echo htmlspecialchars($etudiant['nom']) . ' ' . htmlspecialchars($etudiant['prenom']); ?></strong> 
                                  - <?php echo htmlspecialchars($etudiant['email']); ?>
                              </li>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <li>Aucun étudiant inscrit.</li>
                      <?php endif; ?>
                  </ul>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-clock-o"></i> Liste des formations</h3>
                </div>
                <div class="panel-body">
                    <ul>
                        <?php if (count($formations) > 0): ?>
                            <?php foreach ($formations as $formation): ?>
                                <li>
                                    <strong><?php echo htmlspecialchars($formation['nom']); ?></strong>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>Aucune formation disponible.</li>
                        <?php endif; ?>
                    </ul>
                </div>
              </div>
          </div>
          <div class="col-lg-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-money"></i> Liste des cours selon la formation</h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <?php if (count($formationsAvecCours) > 0): ?>
                            <ul>
                                <?php foreach ($formationsAvecCours as $formation => $coursList): ?>
                                    <li>
                                        <strong><?php echo htmlspecialchars($formation); ?></strong>
                                        <?php if (count($coursList) > 0): ?>
                                            <ul>
                                                <?php foreach ($coursList as $cours): ?>
                                                    <li><?php echo htmlspecialchars($cours); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <p>(Aucun cours assigné)</p>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>Aucune formation avec des cours assignés.</p>
                        <?php endif; ?>
                    </div>
                  </div>
                </div>
            </div>

        </div><!-- /.row -->

      </div><!-- /#page-wrapper -->

    </div><!-- /#wrapper -->

    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>

    <!-- Page Specific Plugins -->
    <script src="../../../cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="../../../cdn.oesmith.co.uk/morris-0.4.3.min.js"></script>
    <script src="js/morris/chart-data-morris.js"></script>
    <script src="js/tablesorter/jquery.tablesorter.js"></script>
    <script src="js/tablesorter/tables.js"></script>

  </body>

<!-- Mirrored from learning-zone.github.io/website-templates/sb-admin/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Nov 2024 21:48:53 GMT -->
</html>
