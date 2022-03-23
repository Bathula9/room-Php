<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

//CODE ...
// Restriction d'accès : si l'utilisateur n'est pas admin, on le redirige vers connexion.php
if (!user_is_admin()) {
    header('location: ../connexion.php');
    exit(); // permet de bloquer le code php de la page (dans le cas ou qq'un passerait des informations get dans l'url)
}

// echo $_POST["note"];
// echo $_POST["commentaire"];

//for the avis
$liste_commande = $pdo->query("SELECT * FROM commande,membre,produit WHERE commande.id_membre = membre.id_membre AND produit.id_produit = salle.id_produit ORDER BY id_commande");

//Debut des affichages
include '../inc/header.inc.php';
include '../inc/nav.inc.php';

?>

<div class="bg-light p-5 rounded text-center">
    <h1 class="letter">Gestion des commandes <i class="fa-solid fa-book"></i></h1>
</div>

<div class="row mt-4">
    <div class="col-12">
        <table class="table table-bordered text-center">
            <thead class="bg-red text-white text-center">
                <tr>
                    <th>Id commande</th>
                    <th>Id membre</th>
                    <th>Id produit</th>
                    <th>Prix</th>
                    <th>Date enregistrement</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($ligne = $liste_commande->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td>' . $ligne['id_commande'] . '</td>';
                    echo '<td>' . $ligne['id_membre'] . '</td>';
                    echo '<td>' . $ligne['id_produit'] . '</td>';
                    echo '<td>' . $ligne['prix'] . '</td>';
                    echo '<td>' . $ligne['date_enregistrement'] . '</td>';

                    echo '<td><a href="?action=supprimer&id_commande=' . $ligne['id_commande'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fa-solid fa-trash-can"></i></a></td>';

                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include '../inc/footer.inc.php';

?>