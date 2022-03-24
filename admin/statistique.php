<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

//CODE ...
// Restriction d'accès : si l'utilisateur n'est pas admin, on le redirige vers connexion.php
if (!user_is_admin()) {
    header('location: ../connexion.php');
    exit(); // permet de bloquer le code php de la page (dans le cas ou qq'un passerait des informations get dans l'url)
}

//Debut des affichages
include '../inc/header.inc.php';
include '../inc/nav.inc.php';

?>

<div class="bg-light p-5 rounded text-center">
    <h1 class="letter">Statistiques <i class="fa-solid fa-book"></i></h1>
</div>

<!-- beginning of list -->

<div class="list-group w-50 mx-auto mt-3">
    <a href="#first" class="list-group-item list-group-item-action active" aria-current="true">
        Top 5 des salles mieux notees
    </a>
    <a href="#second" class="list-group-item list-group-item-action">Top 5 des salles plus commandees</a>
    <a href="#" class="list-group-item list-group-item-action">Top 5 des membres qui achetent le plus</a>
    <a href="#" class="list-group-item list-group-item-action">Top 5 des membres qui achetent</a>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-sm-6" id="first">
            <?php
            $resultat = $pdo->query("SELECT salle.titre, note,salle.id_salle FROM avis,salle WHERE avis.id_salle = salle.id_salle ORDER BY note DESC LIMIT 5");
            //echo "nombre de lignes: " . $resultat->rowCount() . ' lignes <hr>';

            echo '<table class="table table-bordered container">';
            echo '<tr>';
            echo '<th>Nom de la Salle</th><th>Notes</th><th>Id salle</th>';
            echo '</tr>';

            while ($ligne = $resultat->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                foreach ($ligne as $valeur) {
                    echo '<td>' . $valeur . '</td>';
                }

                echo '</tr>';
            }

            echo '</table>';


            ?>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-sm-6" id="second">
            <?php
            $resultat = $pdo->query("SELECT salle.titre, note,salle.id_salle FROM avis,salle WHERE avis.id_salle = salle.id_salle ORDER BY note DESC LIMIT 5");
            //echo "nombre de lignes: " . $resultat->rowCount() . ' lignes <hr>';

            echo '<table class="table table-bordered container">';
            echo '<tr>';
            echo '<th>Nom de la Salle</th><th>Notes</th><th>Id salle</th>';
            echo '</tr>';

            while ($ligne = $resultat->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                foreach ($ligne as $valeur) {
                    echo '<td>' . $valeur . '</td>';
                }

                echo '</tr>';
            }

            echo '</table>';


            ?>
        </div>
    </div>
</div>






<?php
include '../inc/footer.inc.php';

?>