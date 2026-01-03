<?php require('Connexion.php'); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" type="text/css">
    <title>Banque - Versement</title>
</head>

<body class="bg-light">
    <div class="row mt-5">
        <div class="col-4"></div>
        <div class="col-4">
            <div class="card border-primary shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h3>VERSEMENT (DÉPÔT)</h3>
                </div>
                <div class="card-body">
                    <?php
                    if (isset($_POST["enregistrer"])) {
                        $num_saisi = trim(htmlspecialchars($_POST["num_compte"]));
                        $montant = floatval($_POST["montant"]);
                        if ($num_saisi !== "" && $montant > 0) {
                            try {
                                $check = $cnx->prepare("SELECT * FROM compte WHERE numcpte = :num");
                                $check->execute([':num' => $num_saisi]);
                                $compte = $check->fetch();
                                if (!$compte) {
                                    echo '<div class="alert alert-danger"> Erreur : Ce numéro de compte n\'existe pas !</div>';
                                } else {
                                    $cnx->beginTransaction();
                                    $update = $cnx->prepare("UPDATE compte SET solde = solde + :montant WHERE numcpte = :num");
                                    $update->execute([':montant' => $montant, ':num' => $num_saisi]);
                                    $mvt = $cnx->prepare("INSERT INTO mouvement (typemvt, montmvt, numcpte) VALUES ('VERSEMENT', :montant, :num)");
                                    $mvt->execute([':montant' => $montant, ':num' => $num_saisi]);
                                    $cnx->commit();
                                    echo '<div class="alert alert-success"> Versement de ' . $montant . ' FCFA réussi !</div>';
                                }
                            } catch (PDOException $e) {
                                $cnx->rollBack();
                                echo '<div class="alert alert-danger">Erreur : ' . $e->getMessage() . '</div>';
                            }
                        } else {
                            echo '<div class="alert alert-warning">Veuillez saisir un montant valide !</div>';
                        }
                    }
                    ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Numéro de compte</label>
                            <input type="text" name="num_compte" placeholder="Ex: CPTE-101" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Montant du versement</label>
                            <input type="number" name="montant" placeholder="Montant en FCFA" class="form-control" required>
                        </div>
                        <div class="d-grid gap-2">
                            <input type="submit" value="Valider le versement" name="enregistrer" class="btn btn-primary">
                            <input type="reset" value="Annuler" class="btn btn-secondary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-4"></div>
    </div>
</body><?php include('menu.php'); ?>

</html>