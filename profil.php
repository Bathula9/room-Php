<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';


//CODE ...
//restriction d'acces : si ''utilisateur n'est pas connecte,on le renvoie sur connexion.php

if (!user_is_connected()) {
    header('location: connexion.php');
}


//CODE ...
if ($_SESSION['membre']['sexe'] == 'm') {
    $sexe = 'homme';
} else {
    $sexe = 'femme';
}

if ($_SESSION['membre']['statut'] == 1) {
    $statut = 'membre';
} else {
    $statut = 'administrateur';
}




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
                        <img src="assets/img/fille.jpg" alt="girl" class="img-fluid" />
                    </div>
                </div>
                <div class="col-lg-5 col-md-12 my-auto">
                    <ul class="list-group">
                        <li class="list-group-item">Profil: <?= $_SESSION['membre']['pseudo']; ?></li>
                        <li class="list-group-item">Client: <?= $_SESSION['membre']['id_membre']; ?></li>
                        <li class="list-group-item">Nom: <?= $_SESSION['membre']['nom']; ?></li>
                        <li class="list-group-item">Prenom: <?= $_SESSION['membre']['prenom']; ?></li>
                        <li class="list-group-item">Email: <?= $_SESSION['membre']['email']; ?></li>
                        <li class="list-group-item">Sexe: <?= $sexe; ?></li>
                        <li class="list-group-item">Statut: <?= $statut; ?></li>

                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
include 'inc/footer.inc.php';

?>