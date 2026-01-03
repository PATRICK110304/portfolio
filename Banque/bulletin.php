<?php require('Connexion.php'); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Bulletin Bancaire</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body class="p-5">
    <div class="container">
        <h2 class="text-center mb-4">Relevé de Compte Dynamique</h2>
        <form method="GET" class="row g-3 mb-5">
            <div class="col-auto">
                <input type="text" name="cpte" class="form-control" placeholder="N° de compte" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-dark">Afficher le bulletin</button>
            </div>
        </form>
        <?php
        if (isset($_GET['cpte'])) {
            $cpte = htmlspecialchars($_GET['cpte']);
            $info = $cnx->prepare("SELECT c.*, cli.nomcli, cli.prencli FROM compte c  JOIN client cli ON c.num_cni = cli.num_cni WHERE c.numcpte = ?");
            $info->execute([$cpte]);
            $client = $info->fetch();
            if ($client) {
                echo "<h4>Titulaire : " . $client['nomcli'] . " " . $client['prencli'] . "</h4>";
                echo "<h5>Solde actuel : " . number_format($client['solde'], 0, ',', ' ') . " FCFA</h5>";
                $mvts = $cnx->prepare("SELECT * FROM mouvement WHERE numcpte = ? ORDER BY datemvt DESC");
                $mvts->execute([$cpte]);
                echo '<table class="table table-bordered mt-3">
                        <thead class="table-dark">
                            <tr><th>Date</th><th>Type</th><th>Montant</th></tr>
                        </thead><tbody>';
                while ($m = $mvts->fetch()) {
                    $couleur = ($m['typemvt'] == 'RETRAIT') ? 'text-danger' : 'text-success';
                    echo "<tr>
                            <td>{$m['datemvt']}</td>
                            <td class='fw-bold'>{$m['typemvt']}</td>
                            <td class='{$couleur}'>" . number_format($m['montmvt'], 0, ',', ' ') . " FCFA</td>
                        </tr>";
                }
                echo '</tbody></table>';
            } else {
                echo "<p class='text-danger'>Compte non trouvé.</p>";
            }
        }
        ?>
    </div>
</body><?php include('menu.php'); ?>

</html>