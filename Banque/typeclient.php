<?php require('Connexion.php'); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Banque - Configuration Types</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" type="text/css" />
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-5">
                <div class="card border-danger shadow">
                    <div class="card-header bg-danger text-white text-center">
                        <h3>CONFIGURER TYPE CLIENT</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Code du Type (ex: PART, PRO)</label>
                                <input type="text" name="code" placeholder="Code unique" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Libellé (Description)</label>
                                <input type="text" name="libelle" placeholder="Nom complet" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <input type="submit" value="Enregistrer le Type" name="enregistrer" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                if (isset($_POST["enregistrer"])) {
                    $co  = strtoupper(trim(htmlspecialchars($_POST["code"]))); // On met en majuscules
                    $lib = trim(htmlspecialchars($_POST["libelle"]));
                    if ($co !== "" && $lib !== "") {
                        try {
                            $req = $cnx->prepare("INSERT INTO typeclient (codetype, libelletype) VALUES (:cod, :lib)");
                            $req->execute([
                                ':cod' => $co,
                                ':lib' => $lib
                            ]);
                            echo '<div class="alert alert-success mt-3 text-center"> Type ajouté avec succès !</div>';
                        } catch (PDOException $e) {
                            if ($e->getCode() == 23000) {
                                echo '<div class="alert alert-warning mt-3"> Ce code existe déjà.</div>';
                            } else {
                                echo '<div class="alert alert-danger mt-3"> Erreur : ' . $e->getMessage() . '</div>';
                            }
                        }
                    }
                }
                ?>
            </div>
            <div class="col-md-7">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white text-center">
                        <h3>TYPES EXISTANTS</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Libellé</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $liste = $cnx->query("SELECT * FROM typeclient");
                                while ($t = $liste->fetch()) {
                                    echo "<tr>
                                            <td><strong>" . $t['codetype'] . "</strong></td>
                                            <td>" . $t['libelletype'] . "</td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body><?php include('menu.php'); ?>

</html>