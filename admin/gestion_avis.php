<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

//CODE ...
// Restriction d'accès : si l'utilisateur n'est pas admin, on le redirige vers connexion.php
if (!user_is_admin()) {
    header('location: ../connexion.php');
    exit(); // permet de bloquer le code php de la page (dans le cas ou qq'un passerait des informations get dans l'url)
}

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// Suppression produit
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_avis'])) {

    $suppression = $pdo->prepare("DELETE FROM avis WHERE id_avis = :id_avis");
    $suppression->bindParam(':id_avis', $_GET['id_avis'], PDO::PARAM_STR);
    $suppression->execute();

    $msg .= '<div class="alert alert-success mb-3">L\'avis n°' . $_GET['id_avis'] . ' a bien été supprimé.</div>';
}


//for the avis
$liste_avis = $pdo->query("SELECT * FROM avis,membre,salle WHERE avis.id_membre = membre.id_membre AND avis.id_salle = salle.id_salle ORDER BY id_avis");

//Debut des affichages
include '../inc/header.inc.php';
include '../inc/nav.inc.php';

?>

<div class="bg-light p-5 rounded text-center">
    <h1 class="letter">Gestion des avis <i class="fa-solid fa-book"></i></h1>
</div>

<div class="row mt-4">
    <div class="col-12">
        <table class="table table-bordered text-center">
            <thead class="bg-red text-white text-center">
                <tr>
                    <th>Id avis</th>
                    <th>Id membre</th>
                    <th>Id Salle</th>
                    <th>Commentaire</th>
                    <th>Note</th>
                    <th>Date enregistrement</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($ligne = $liste_avis->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td>' . $ligne['id_avis'] . '</td>';
                    echo '<td>' . $ligne['id_membre'] . ' - ' . $ligne['email'] . '</td>';
                    echo '<td>' . $ligne['id_salle'] . ' ' . 'Salle ' . $ligne['titre'] . '</td>';
                    echo '<td>' . $ligne['commentaire'] . '</td>';
                    echo '<td>' . $ligne['note'] . '</td>';
                    echo '<td>' . $ligne['date_enregistrement'] . '</td>';

                    echo '<td><a href="?action=supprimer&id_avis=' . $ligne['id_avis'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fa-solid fa-trash-can"></i></a></td>';

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