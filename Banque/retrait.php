<?php require('Connexion.php'); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Banque - Effectuer un Retrait</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-5 offset-md-4">
                <div class="card border-danger shadow-sm">
                    <div class="card-header bg-danger text-white text-center">
                        <h3>RETRAIT BANCAIRE</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($_POST["Valider"])) {
                            $num_trans = htmlspecialchars($_POST["num_retrait"]);
                            $montant   = floatval($_POST["montant"]);
                            $num_cpte  = htmlspecialchars($_POST["num_compte"]);
                            try {
                                $stmt = $cnx->prepare("SELECT solde FROM compte WHERE numcpte = ?");
                                $stmt->execute([$num_cpte]);
                                $resultat = $stmt->fetch();
                                if ($resultat) {
                                    $solde_actuel = $resultat['solde'];
                                    if ($solde_actuel >= $montant) {
                                        $cnx->beginTransaction();
                                        $nouveau_solde = $solde_actuel - $montant;
                                        $upd = $cnx->prepare("UPDATE compte SET solde = ? WHERE numcpte = ?");
                                        $upd->execute([$nouveau_solde, $num_cpte]);
                                        $insRetrait = $cnx->prepare("INSERT INTO retraits (num_retrait, montant_retrait, numcpte) VALUES (?, ?, ?)");
                                        $insRetrait->execute([$num_trans, $montant, $num_cpte]);
                                        $insMvt = $cnx->prepare("INSERT INTO mouvement (typemvt, montmvt, numcpte) VALUES ('RETRAIT', ?, ?)");
                                        $insMvt->execute([$montant, $num_cpte]);
                                        $cnx->commit();
                                        echo '<div class="alert alert-success"> Retrait effectué !<br>Nouveau solde : <strong>' . number_format($nouveau_solde, 0, ',', ' ') . ' FCFA</strong></div>';
                                    } else {
                                        echo '<div class="alert alert-warning"> Solde insuffisant (Disponible : ' . $solde_actuel . ' FCFA)</div>';
                                    }
                                } else {
                                    echo '<div class="alert alert-danger"> Le numéro de compte ' . $num_cpte . ' n\'existe pas.</div>';
                                }
                            } catch (Exception $e) {
                                $cnx->rollBack();
                                echo '<div class="alert alert-danger">Erreur critique : ' . $e->getMessage() . '</div>';
                            }
                        }
                        ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Numéro de Transaction</label>
                                <input type="text" name="num_retrait" placeholder="Ex: RET-0098" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Numéro de Compte</label>
                                <input type="text" name="num_compte" placeholder="Numéro du compte à débiter" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Montant à retirer (FCFA)</label>
                                <input type="number" name="montant" class="form-control" min="500" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" name="Valider" class="btn btn-danger">Confirmer le Retrait</button>
                                <button type="reset" class="btn btn-secondary">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body><?php include('menu.php'); ?>

</html>