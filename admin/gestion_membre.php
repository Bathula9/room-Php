<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

// Restriction d'accès : si l'utilisateur n'est pas admin, on le redirige vers connexion.php
if (!user_is_admin()) {
    header('location: ../connexion.php');
    exit(); // permet de bloquer le code php de la page (dans le cas ou qq'un passerait des informations get dans l'url)
}

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// Suppression membre
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_membre'])) {


    $suppression = $pdo->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
    $suppression->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);
    $suppression->execute();
    $msg .= '<div class="alert alert-success mb-3">Le membre n°' . $_GET['id_membre'] . ' a bien été supprimé.</div>';
}

$id_membre = ''; // utilisé pour la modif


$pseudo = '';
$nom = '';
$prenom = '';
$pays = '';
$email = '';
$civilite = '';
$statut = '';

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
        <form action="gestion_membre.php" method="post" class="border mt-3 p-3 mb-4">
            <!-- champ caché id_produit pour la modification -->
            <input type="hidden" name="id_membre" id="id_membre" value="<?= $id_membre; ?>">
            <div class="col-sm-6">

                <div class="mb-3">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" name="pseudo" id="pseudo" class="form-control">

                </div>

                <div class="mb-3">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="prenom">Prenom</label>
                    <input type="text" name="prenom" id="prenom" class="form-control">
                </div>

            </div>
            <div class="col-sm-6">
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="civilite">Civilite</label>
                    <select name="civilite" id="civilite" class="form-select">
                        <option value="m">Homme</option>

                        <option <?php if ($civilite == 'Femme') {
                                    echo ' selected ';
                                } ?>>Femme</option>

                    </select>
                </div>

                <div class="mb-3">
                    <label for="statut">Statut</label>
                    <select name="statut" id="statut" class="form-select">
                        <option value="2">Administrateur</option>

                        <option <?php if ($statut == '1') {
                                    echo ' selected ';
                                } ?>>Membre</option>

                    </select>
                </div>


                <div class="mb-3 col-md-12 text-center">
                    <button class="btn btn-danger" type="submit">Ajouter</button>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="row mt-4">
    <div class="col-sm-12">
        <?= $msg; // affichage des messages utilisateur  
        ?>
        <?php

        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered container text-center">';
        echo '<tr class="bg-red text-white">';
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

            echo '<td><a href="?action=modifier&id_membre=' . $ligne['id_membre'] . '" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a></td>';

            echo '<td><a href="?action=supprimer&id_membre=' . $ligne['id_membre'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fa-solid fa-trash-can"></i></a></td>';


            echo '</tr>';
        }

        echo '</table>';
        echo '</div>';


        ?>
    </div>

</div>


<?php
include '../inc/footer.inc.php';

?>