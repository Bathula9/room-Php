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
$email = '';
$civilite = '';
$statut = '';

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// Modification membre
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_membre'])) {
    $recup_membre = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
    $recup_membre->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);
    $recup_membre->execute();

    if ($recup_membre->rowCount() > 0) {
        $infos_membre = $recup_membre->fetch(PDO::FETCH_ASSOC);

        $id_membre = $infos_membre['id_membre']; // utilisée pour la modif
        $pseudo = $infos_membre['pseudo'];
        $nom = $infos_membre['nom'];
        $prenom = $infos_membre['prenom'];
        $email = $infos_membre['email'];
        $civilite = $infos_membre['civilite'];
        $statut = $infos_membre['statut'];
    }
}

if (isset($_POST['id_membre']) && isset($_POST['pseudo']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['civilite']) && isset($_POST['statut'])) {

    $id_membre = trim($_POST['id_membre']);
    $pseudo = trim($_POST['pseudo']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $civilite = trim($_POST['civilite']);
    $statut = trim($_POST['statut']);


    if (!empty($id_membre)) {

        $enregistrement = $pdo->prepare("UPDATE membre SET pseudo = :pseudo, nom = :nom, prenom = :prenom, email = :email, civilite = :civilite, statut = :statut WHERE id_membre = :id_membre");
        $enregistrement->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);
        $enregistrement->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $enregistrement->bindParam(':nom', $nom, PDO::PARAM_STR);
        $enregistrement->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $enregistrement->bindParam(':email', $email, PDO::PARAM_STR);
        $enregistrement->bindParam(':civilite', $civilite, PDO::PARAM_STR);
        $enregistrement->bindParam(':statut', $statut, PDO::PARAM_STR);
        $enregistrement->execute();


        $_SESSION['message_utilisateur'] .= '<div class="alert alert-success mb-3">Le membre n°' . $id_membre . ' a bien été modifié.</div>';

        //header('location: gestion_membre.php');
        //exit();
    }
}

// Message si modification
if (!empty($_SESSION['message_utilisateur'])) {
    $msg .= $_SESSION['message_utilisateur']; // on affiche le message
    $_SESSION['message_utilisateur'] = ''; // on vide le message
}

//CODE ...
//list of members
$liste_membre =
    $pdo->query("SELECT * FROM membre");

//Debut des affichages
include '../inc/header.inc.php';
include '../inc/nav.inc.php';
//echo $civilite;

if (!empty($_SESSION['message_utilisateur'])) {
    $msg .= $_SESSION['message_utilisateur']; // on affiche le message
    $_SESSION['message_utilisateur'] = ''; // on vide le message
}
?>

<div class="bg-light p-5 rounded text-center">
    <h1 class="letter">Gestion membre <i class="fa-solid fa-book"></i></h1>
</div>

<div class="row mt-4">
    <div class="col-sm-12 w-50 mx-auto">
        <?= $msg; // affichage des messages utilisateur  
        ?>
        <form action="gestion_membre.php" method="post" class="border mt-3 p-3 mb-4">
            <!-- champ caché id_produit pour la modification -->
            <div class="mb-3">
                <label for="id_membre">Id Membre</label>
                <input type="text" name="id_membre" id="id_membre" class="form-control" <?php if (!empty($id_membre)) {
                                                                                            echo 'readonly';
                                                                                        } ?> value="<?= $id_membre; ?>">
            </div>

            <div class="mb-3">
                <label for="pseudo">Pseudo</label>
                <input type="text" name="pseudo" id="pseudo" class="form-control" value="<?= $pseudo; ?>">

            </div>

            <div class="mb-3">
                <label for="nom">Nom</label>
                <input type="text" name="nom" id="nom" class="form-control" value="<?= $nom; ?>">
            </div>
            <div class="mb-3">
                <label for="prenom">Prenom</label>
                <input type="text" name="prenom" id="prenom" class="form-control" value="<?= $prenom; ?>">
            </div>

            <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= $email; ?>">
            </div>

            <div class="mb-3">
                <label for="civilite">Civilite</label>
                <select name="civilite" id="civilite" class="form-select">
                    <option value="m">Homme</option>

                    <option value="f" <?php if ($civilite == 'f') {
                                            echo ' selected ';
                                        } ?>>Femme</option>

                </select>
            </div>

            <div class="mb-3">
                <label for="statut">Statut</label>
                <select name="statut" id="statut" class="form-select">
                    <option value="2">Administrateur</option>

                    <option value="1" <?php if ($statut == '1') {
                                            echo ' selected ';
                                        } ?>>Membre</option>

                </select>
            </div>
            <div class="mb-3 col-md-12 text-center">
                <button class="btn btn-danger" type="submit">Ajouter</button>
            </div>

        </form>
    </div>
</div>


<div class="row mt-4">
    <div class="col-sm-12">
        
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
            echo '<td>' . date("d/m/Y H:i", strtotime($ligne['date_enregistrement'])) . '</td>';


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