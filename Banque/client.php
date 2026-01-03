<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrement client - Banque</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" type="text/css">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <div class="card border-danger shadow">
                    <div class="card-header bg-danger text-white text-center">
                        <h3>Enregistrement Client</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Numéro CNI</label>
                                <input type="text" name="numero" placeholder="Ex: CNI010203" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" name="nom" placeholder="Nom du client" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prénoms</label>
                                <input type="text" name="prenoms" placeholder="Prénoms du client" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contact</label>
                                <input type="text" name="contact" placeholder="Téléphone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Profession</label>
                                <input type="text" name="profession" placeholder="Métier" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Type Client</label>
                                <select class="form-select" name="type" required>
                                    <option value="">-- Sélectionner --</option>
                                    <?php
                                    include('Connexion.php');
                                    $req = $cnx->query("SELECT * FROM typeclient");
                                    while ($result = $req->fetch(PDO::FETCH_ASSOC)) {
                                        $code = htmlspecialchars($result['codetype']);
                                        $libelle = htmlspecialchars($result['libelletype']);
                                        echo "<option value=\"$code\">$libelle</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="text-center d-grid gap-2">
                                <button type="submit" name="Enregistrer" class="btn btn-primary">Enregistrer le Client</button>
                                <button type="reset" class="btn btn-secondary">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                if (isset($_POST["Enregistrer"])) {
                    $num = trim(htmlspecialchars($_POST["numero"]));
                    $nom = trim(htmlspecialchars($_POST["nom"]));
                    $pre = trim(htmlspecialchars($_POST["prenoms"]));
                    $con = trim(htmlspecialchars($_POST["contact"]));
                    $pro = trim(htmlspecialchars($_POST["profession"]));
                    $typ = trim(htmlspecialchars($_POST["type"]));
                    if (!empty($num) && !empty($nom) && !empty($pre)) {
                        try {
                            $sql = "INSERT INTO client (num_cni, nomcli, prencli, contcli, profcli, codetype) 
                                    VALUES (:num, :nom, :pren, :cont, :prof, :typ)";
                            $insertion = $cnx->prepare($sql);
                            $insertion->execute([
                                ':num'  => $num,
                                ':nom'  => $nom,
                                ':pren' => $pre,
                                ':cont' => $con,
                                ':prof' => $pro,
                                ':typ'  => $typ
                            ]);
                            echo '<div class="alert alert-success mt-3 text-center">✅ Client enregistré avec succès !</div>';
                        } catch (PDOException $e) {
                            if ($e->getCode() == 23000) {
                                echo '<div class="alert alert-warning mt-3"> Ce numéro de CNI est déjà utilisé.</div>';
                            } else {
                                echo '<div class="alert alert-danger mt-3"> Erreur : ' . $e->getMessage() . '</div>';
                            }
                        }
                    } else {
                        echo '<div class="alert alert-warning mt-3">Veuillez remplir les champs obligatoires !</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body><?php include('menu.php'); ?>

</html>