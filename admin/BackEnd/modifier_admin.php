
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
                    header('Location: ../ajoutAdmin.php'); // Rediriger après modification
                    exit;
                } catch (PDOException $e) {
                    die("Erreur : " . $e->getMessage());
                }
            }
        ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Administrateur - Akademia</title>
    <!-- Ajouter Bootstrap pour les styles -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            color: #ffffff;
        }

        .sidebar {
            background-color: #343a40;
            color: #ffffff;
        }

        .sidebar a {
            color: #ffffff;
            font-size: 16px;
        }

        .sidebar a:hover {
            background-color: #007bff;
            color: #ffffff;
        }

        .content-container {
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 30px;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .breadcrumb {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }

        .breadcrumb a {
            color: #007bff;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 800px;
            margin-top: 30px;
        }

        .header-title {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .nav-item {
            margin-right: 20px;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../accueil.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Modifier un Administrateur</li>
        </ol>

        <!-- Header -->
        <h1 class="header-title">Modifier un Administrateur</h1>

        <!-- Formulaire -->
        <form method="POST">
            <div class="content-container">
                <div class="form-group">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="<?= htmlspecialchars($admin['nomAdmin']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" name="prenom" id="prenom" class="form-control" value="<?= htmlspecialchars($admin['prenomAdmin']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($admin['emailAdmin']) ?>" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Enregistrer les modifications</button>
            </div>
        </form>
    </div>

    <!-- JavaScript et jQuery pour Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
