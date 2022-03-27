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
    <a href="#first" class="list-group-item list-group-item-action" aria-current="true">
        Top 5 des salles les mieux notées </a>
    <a href="#second" class="list-group-item list-group-item-action">Top 5 des salles les plus commandées</a>
    <a href="#third" class="list-group-item list-group-item-action">Top 5 des membres qui achètent le plus</a>
    <a href="#fourth" class="list-group-item list-group-item-action">Top 5 des membres qui achètent le plus cher</a>
</div>

<!-- Top 5 des salles les mieux notées -->
<div class="container mt-5">
    <div class="row">
        <div class="col-sm-6" id="first">
            <?php
            // $resultat = $pdo->query("SELECT salle.titre, note,salle.id_salle FROM avis,salle WHERE avis.id_salle = salle.id_salle ORDER BY note DESC LIMIT 5");
            $resultat_notes = $pdo->query("SELECT salle.id_salle, titre, avg(note) AS note_moyenne FROM salle, avis WHERE salle.id_salle = avis.id_salle GROUP BY salle.id_salle ORDER BY note_moyenne DESC LIMIT 5");

            //echo "nombre de lignes: " . $resultat_notes->rowCount() . ' lignes <hr>';

            echo '<table class="table table-bordered container text-center">';
            echo '<tr>';
            echo '<th>Id Salle</th><th>Nom de la salle</th><th>Note</th>';
            echo '</tr>';

            while ($ligne = $resultat_notes->fetch(PDO::FETCH_ASSOC)) {
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


<!-- Top 5 des salles les plus commandées -->
<div class="container mt-5">
    <div class="row">
        <div class="col-sm-6" id="second">
            <?php
            $resultat_commandes = $pdo->query("SELECT salle.id_salle, titre, COUNT(id_commande) AS nombre_commande FROM commande, produit, salle WHERE produit.id_produit = commande.id_produit AND salle.id_salle = produit.id_salle GROUP BY salle.id_salle ORDER BY nombre_commande DESC LIMIT 5");
            echo "nombre de lignes: " . $resultat_commandes->rowCount() . ' lignes <hr>';

            echo '<table class="table table-bordered container text-center">';
            echo '<tr>';
            echo '<th>Id Salle</th><th>Nom de la Salle</th><th>Id commande</th>';
            echo '</tr>';

            while ($ligne = $resultat_commandes->fetch(PDO::FETCH_ASSOC)) {
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


<!-- Top 5 des membres qui achètent le plus (en termes de quantité) -->
<div class="container mt-5">
    <div class="row">
        <div class="col-sm-6" id="third">
            <?php
            $resultat_membres_qty = $pdo->query("SELECT membre.id_membre, email, count(id_commande) AS nombre_commande FROM membre, commande WHERE membre.id_membre = commande.id_membre GROUP BY membre.id_membre ORDER BY nombre_commande DESC LIMIT 5");
            echo "nombre de lignes: " . $resultat_membres_qty->rowCount() . ' lignes <hr>';

            echo '<table class="table table-bordered container text-center">';
            echo '<tr>';
            echo '<th>Id membre</th><th>Email</th><th>Nombre des commandes</th>';
            echo '</tr>';

            while ($ligne = $resultat_membres_qty->fetch(PDO::FETCH_ASSOC)) {
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

<!-- Top 5 des membres qui achètent le plus cher (en termes de prix) -->
<div class="container mt-5">
    <div class="row">
        <div class="col-sm-6" id="fourth">
            <?php

            $resultat_membres_prix = $pdo->query("SELECT m.id_membre,pseudo, email, SUM(prix) AS prix_commande FROM membre m, commande c, produit p WHERE m.id_membre = c.id_membre AND c.id_produit = p.id_produit GROUP BY m.id_membre ORDER BY prix_commande DESC LIMIT 5 ");
            echo "nombre de lignes: " . $resultat_membres_prix->rowCount() . ' lignes <hr>';

            echo '<table class="table table-bordered container text-center">';
            echo '<tr>';
            echo '<th>Id membre</th><th>Pseudo</th><th>Email</th><th>Prix</th>';
            echo '</tr>';

            while ($ligne = $resultat_membres_prix->fetch(PDO::FETCH_ASSOC)) {
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