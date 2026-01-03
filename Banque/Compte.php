<?php require('Connexion.php'); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion Bancaire</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body>
    <?php include('menu.php'); ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">OUVERTURE DE COMPTE</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($_POST["valider"])) {
                            $cni = $_POST['cni'];
                            $num = $_POST['numcpte'];
                            $date = $_POST['date_ouv'];
                            $type = $_POST['type_cpte'];
                            $soc = $_POST['societe'];
                            try {
                                $v = $cnx->prepare("SELECT num_cni FROM client WHERE num_cni = ?");
                                $v->execute([$cni]);
                                if ($v->rowCount() > 0) {
                                    $sql = "INSERT INTO compte (numcpte, dateouv, typecpte, num_cni, nom_societe, solde) 
                                            VALUES (?, ?, ?, ?, ?, 0)";
                                    $ins = $cnx->prepare($sql);
                                    $ins->execute([$num, $date, $type, $cni, $soc]);
                                    echo "<div class='alert alert-success'>Le compte a été créé.</div>";
                                } else {
                                    echo "<div class='alert alert-danger'>Client introuvable (CNI incorrecte).</div>";
                                }
                            } catch (Exception $e) {
                                echo "<div class='alert alert-danger'>Erreur : " . $e->getMessage() . "</div>";
                            }
                        }
                        ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label>Numéro CNI</label>
                                <input type="text" name="cni" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Numéro de Compte</label>
                                <input type="text" name="numcpte" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Date</label>
                                <input type="date" name="date_ouv" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="mb-3">
                                <label>Type</label>
                                <select name="type_cpte" class="form-select">
                                    <option value="Courant">Courant</option>
                                    <option value="Epargne">Epargne</option>
                                    <option value="Entreprise">Entreprise</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Société (facultatif)</label>
                                <input type="text" name="societe" class="form-control">
                            </div>
                            <button type="submit" name="valider" class="btn btn-primary w-100">Enregistrer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body><?php include('menu.php'); ?>

</html>