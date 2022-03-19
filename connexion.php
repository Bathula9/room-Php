<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';


//CODE ...

//déconnexion utilisateur
if (isset($_GET['action']) && $_GET['action'] == 'deconnexion') {
    session_destroy();
}

// Restriction d'accès : si l'utilisateur est connecté, on le redirige sur profil.php
if (user_is_connected()) {
    header('location: profil.php');
}

if (isset($_POST['pseudo']) && isset($_POST['mdp'])) {
    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);


    echo '<pre>';
    print_r($_POST);
    echo '</pre>';

    $connexion = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $connexion->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $connexion->execute();

    if ($connexion->rowCount() < 1) {
        // si on a récupéré 0 ligne : le pseudo n'existe pas en BDD
        $msg .= '<div class="alert alert-danger mb-3">Attention,<br>Erreur sur le pseudo et/ou le mot de passe.</div>';
    } else {
        // pseudo ok
        // on compare le mdp
        // pour comparer le mdp traité avec password_hash() : password_verify()

        $infos = $connexion->fetch(PDO::FETCH_ASSOC);

        if (password_verify($mdp, $infos['mdp'])) {

            // on conserve les données utilisateur (sauf le mdp) dans la session dans un sous tableau "membre"
            $_SESSION['membre'] = array();
            $_SESSION['membre']['id_membre'] = $infos['id_membre'];
            $_SESSION['membre']['pseudo'] = $infos['pseudo'];
            $_SESSION['membre']['nom'] = $infos['nom'];
            $_SESSION['membre']['prenom'] = $infos['prenom'];
            $_SESSION['membre']['email'] = $infos['email'];
            $_SESSION['membre']['sexe'] = $infos['sexe'];
            $_SESSION['membre']['statut'] = $infos['statut'];

            // on redirige sur profil.php
            header('location: profil.php');
        } else {
            // mdp incorrect
            $msg .= '<div class="alert alert-danger mb-3">Attention,<br>Erreur sur le pseudo et/ou le mot de passe.</div>';
        }
    }
}



//Debut des affichages
include 'inc/header.inc.php';
include 'inc/nav.inc.php';
/*
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
*/



?>




<div class="bg-light p-5 rounded text-center">
    <h1 class="letter">Connexion</h1>
    <!-- <p class="lead">Bienvenue au Room</p> -->
</div>

<div class="row mt-4">
    <div class="col-sm-12">
        <div class="col-sm-4 mx-auto">
            <?= $msg; // affichage des messages utilisateur  
            ?>
            <form method="post" action="" class="p-3 border mt-4 mb-4">
                <div class="mb-3">
                    <label for="pseudo"><i class="fa-solid fa-user"></i> Pseudo</label>
                    <input type="text" name="pseudo" id="pseudo" class="form-control" value="" autocomplete="off">
                </div>
                <div class="mb-3">
                    <label for="mdp"><i class="fa-solid fa-lock"></i> Mot de passe</label>
                    <input type="text" name="mdp" id="mdp" class="form-control" value="" autocomplete="off">
                </div>
                <div class="mb-3">
                    <button type="submit" id="connexion" class="w-100 btn btn-outline-danger">Connexion</button>
                </div>
            </form>

        </div>
    </div>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

<?php
include 'inc/footer.inc.php';

?>