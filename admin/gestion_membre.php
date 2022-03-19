<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';


//CODE ...
//list of members
$liste_membre = $pdo->query("SELECT id_membre,pseudo,nom,prenom,email,civilite,statut,date_enregistrement FROM membre");






//Debut des affichages
include '../inc/header.inc.php';
include '../inc/nav.inc.php';

?>




<div class="bg-light p-5 rounded text-center">
    <h1 class="letter">Gestion membre <i class="fa-solid fa-book"></i></h1>
</div>

<div class="row mt-4">
    <div class="col-sm-12">
        <?= $msg; // affichage des messages utilisateur  
        ?>
        <?php

        echo '<table class="table table-bordered container text-center">';
        echo '<tr class="bg-red">';
        echo '<th>id_membre</th><th>Pseudo</th><th>Nom</th><th>Prenom</th><th>Email</th><th>Civilite</th><th>Statut</th><th>Date_enregistrement</th><th>Modifier</th><th>Supprimer</th>';
        echo '</tr>';

        while ($ligne = $liste_membre->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $ligne['id_membre'] . '</td>';
            echo '<td>' . $ligne['pseudo'] . '</td>';
            echo '<td>' . $ligne['nom'] . '</td>';
            echo '<td>' . $ligne['prenom'] . '</td>';
            echo '<td>' . $ligne['email'] . '</td>';
            echo '<td>' . $ligne['civilite'] . '</td>';
            echo '<td>' . $ligne['statut'] . '</td>';
            echo '<td>' . $ligne['date_enregistrement'] . '</td>';
            echo '<td>' . '<i class="fa-solid fa-pen-to-square"></i>' . '</td>';
            echo '<td>' . '<i class="fa-solid fa-trash"></i>' . '</td>';


            echo '</tr>';
        }

        echo '</table>';


        ?>
    </div>

</div>


<?php
include '../inc/footer.inc.php';

?>