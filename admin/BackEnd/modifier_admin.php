
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
                    <li><a href="tables.html"><i class="fa fa-table"></i> Tables</a></li>
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
            if (isset($_GET['id'])) {
                $idAdmin = intval($_GET['id']);
                try {
                    $sql = "SELECT * FROM Administrateur WHERE idAdmin = :idAdmin";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':idAdmin' => $idAdmin]);
                    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    die("Erreur : " . $e->getMessage());
                }
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nom = htmlspecialchars($_POST['nom']);
                $prenom = htmlspecialchars($_POST['prenom']);
                $email = htmlspecialchars($_POST['email']);

                try {
                    $sql = "UPDATE Administrateur 
                            SET nomAdmin = :nom, prenomAdmin = :prenom, emailAdmin = :email 
                            WHERE idAdmin = :idAdmin";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':nom' => $nom,
                        ':prenom' => $prenom,
                        ':email' => $email,
                        ':idAdmin' => $idAdmin,
                    ]);
                    header('Location: ajoutAdmin.php'); // Rediriger après modification
                    exit;
                } catch (PDOException $e) {
                    die("Erreur : " . $e->getMessage());
                }
            }
        ?>


            <div class="container my-5">
                <h1>Modifier un Administrateur</h1>
                <form method="POST">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" name="nom" id="nom" class="form-control" value="<?= htmlspecialchars($admin['nomAdmin']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" name="prenom" id="prenom" class="form-control" value="<?= htmlspecialchars($admin['prenomAdmin']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($admin['emailAdmin']) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </form>
            </div>


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