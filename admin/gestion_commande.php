<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

//CODE ...
// Restriction d'accès : si l'utilisateur n'est pas admin, on le redirige vers connexion.php
if (!user_is_admin()) {
    header('location: ../connexion.php');
    exit(); // permet de bloquer le code php de la page (dans le cas ou qq'un passerait des informations get dans l'url)
}

//for the orders

if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_commande'])) {

    $recup_commande = $pdo->prepare("SELECT * FROM commande WHERE id_commande =:id_commande");
    $recup_commande->bindParam(':id_commande', $_GET['id_commande'], PDO::PARAM_STR);
    $recup_commande->execute();

    $suppression = $pdo->prepare("DELETE FROM commande WHERE id_commande = :id_commande");
    $suppression->bindParam(':id_commande', $_GET['id_commande'], PDO::PARAM_STR);
    $suppression->execute();
    $msg .= '<div class="alert alert-success mb-3">La commande n°' . $_GET['id_commande'] . ' a bien été supprimé.</div>';
}

//$liste_commande = $pdo->query("SELECT id_commande, membre.id_membre, email, salle.titre, produit.id_produit,prix, commande.date_enregistrement FROM commande, membre, produit,salle WHERE commande.id_membre = membre.id_membre AND commande.id_produit = produit.id_produit");


$liste_commande = $pdo->query("SELECT commande.id_commande, commande.id_membre, commande.id_produit, prix, email, commande.date_enregistrement, salle.id_salle, salle.titre FROM commande, membre, produit ,salle WHERE commande.id_membre = membre.id_membre AND commande.id_produit = produit.id_produit AND produit.id_salle=salle.id_salle ORDER BY id_commande");

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
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($ligne = $liste_commande->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td>' . $ligne['id_commande'] . '</td>';
                    echo '<td>' . $ligne['id_membre'] . ' ' . '- ' . $ligne['email'] . '</td>';
                    echo '<td>' . $ligne['id_produit'] . ' ' . '- ' . $ligne['titre'] . '</td>';
                    echo '<td>' . $ligne['prix'] . ' &euro;' . '</td>';
                    echo '<td>' . date("d/m/Y H:i:s", strtotime($ligne['date_enregistrement'])) . '</td>';


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