<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';


//CODE ...

if (!user_is_connected()) {
    header('location: connexion.php');
}


//to display homme and femme
if ($_SESSION['membre']['civilite'] == 'm') {
    $sexe = 'homme';
} else {
    $sexe = 'femme';
}

if ($_SESSION['membre']['statut'] == 1) {
    $statut = 'membre';
} else {
    $statut = 'administrateur';
}

//display historique des commandes
$liste_commande = $pdo->prepare("SELECT id_commande, id_membre, produit.id_produit, salle.id_salle, salle.titre,date_enregistrement, prix, date_arrivee FROM commande, produit,salle WHERE id_membre = :id_membre AND commande.id_produit = produit.id_produit AND produit.id_salle = salle.id_salle");
$liste_commande->bindParam(':id_membre', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
$liste_commande->execute();


//Debut des affichages
include 'inc/header.inc.php';
include 'inc/nav.inc.php';
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';

?>

<div class="bg-light p-5 rounded text-center">
    <h1 class="letter">Profil <i class="fa-solid fa-book"></i></h1>
</div>

<div class="row mt-4">
    <div class="pt-3">
        <div class="container">
            <div class="row align-content-center">
                <div class="col-lg-7 col-md-12 mb-lg-0 mb-3">
                    <div class="card border-0">


                        <?php if ($_SESSION['membre']['id_membre'] == 2) {  ?>
                            <img src="assets/img/henry.jpg" alt="henry" class="img-fluid" />
                        <?php } elseif ($_SESSION['membre']['id_membre'] == 3) { ?>
                            <img src="assets/img/jane.jpg" alt="jane" class="img-fluid" />
                        <?php } elseif ($_SESSION['membre']['id_membre'] == 4) { ?>
                            <img src="assets/img/william.jpg" alt="william" class="img-fluid" />
                        <?php } elseif ($_SESSION['membre']['id_membre'] == 5) { ?>
                            <img src="assets/img/nancy.jpg" alt="nancy" class="img-fluid" />
                        <?php } elseif ($_SESSION['membre']['id_membre'] == 6) { ?>
                            <img src="assets/img/steve.jpg" alt="steve" class="img-fluid" />
                        <?php } elseif ($_SESSION['membre']['id_membre'] == 7) { ?>
                            <img src="assets/img/lily.jpg" alt="lily" class="img-fluid" />
                        <?php } elseif ($_SESSION['membre']['id_membre'] == 8) { ?>
                            <img src="assets/img/gucci.jpg" alt="gucci" class="img-fluid" />
                        <?php } else { ?>

                            <img src="assets/img/fille.jpg" alt="woman" class="img-fluid" />
                        <?php } ?>

                    </div>
                </div>
                <div class="col-lg-5 col-md-12 my-auto">
                    <ul class="list-group">
                        <li class="list-group-item"><span class="fw-bold">Profil: </span> <?= $_SESSION['membre']['pseudo']; ?></li>
                        <li class="list-group-item"><span class="fw-bold">Client: </span> <?= $_SESSION['membre']['id_membre']; ?></li>
                        <li class="list-group-item"><span class="fw-bold">Nom: </span> <?= $_SESSION['membre']['nom']; ?></li>
                        <li class="list-group-item"><span class="fw-bold">Prenom: </span> <?= $_SESSION['membre']['prenom']; ?></li>
                        <li class="list-group-item"><span class="fw-bold">Email: </span> <?= $_SESSION['membre']['email']; ?></li>
                        <li class="list-group-item"><span class="fw-bold">Sexe: </span> <?= $sexe; ?></li>
                        <li class="list-group-item"><span class="fw-bold">Statut: </span> <?= $statut; ?></li>

                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>


<!-- Historique des commandes -->

<div class="container mt-5">
    <div class="row align-content-center">
        <div class="col-lg-12 col-md-12 mb-lg-0 mb-3">
            <table class="table text-center">
                <thead class="bg-red text-white">
                    <tr>
                        <th>Numero Commande</th>
                        <th>Numero Client</th>
                        <th>Produit Commande</th>
                        <th>Prix</th>
                        <th>Date d'arrivee</th>
                        <th>Date de la commande</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($liste_commande->rowCount() > 0) {
                        while ($commande = $liste_commande->fetch(PDO::FETCH_ASSOC)) {

                            echo '<tr>';
                            echo '<td>' . $commande['id_commande'] . '</td>';
                            echo '<td>' . $commande['id_membre'] . '</td>';
                            echo '<td>' . $commande['id_produit'] . '</td>';
                            echo '<td>' . $commande['prix'] . ' ' . '&euro;' . '</td>';
                            echo '<td>'
                                . date("d/m/Y H:i", strtotime($commande['date_arrivee'])) . '</td>';

                            echo '<td>'
                                . date("d/m/Y H:i", strtotime($commande['date_enregistrement'])) . '</td>';


                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>


            </table>
        </div>
    </div>


    <?php
    include 'inc/footer.inc.php';

    ?>