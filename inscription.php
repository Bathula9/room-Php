<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';


//if the user is connected go to his profile - location - Use header
if (user_is_connected()) {
    header('location: profil.php');
}


$pseudo = '';
$nom = '';
$prenom = '';
$email = '';
$sexe = '';

if (isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['confirm_mdp']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['sexe'])) {

    //echo 'Ok'; // To verify if it works

    // echo '<pre>';
    // echo print_r($_POST);
    // echo '</pre>';

    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);
    $confirm_mdp = trim($_POST['confirm_mdp']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $sexe = trim($_POST['sexe']);

    $erreur = 'non';

    // vérifier la taille du pseudo
    if (iconv_strlen($pseudo) < 4 || iconv_strlen($pseudo) > 14) {
        $msg .= '<div class="alert alert-danger mb-3">Attention,<br>le pseudo doit avoir entre 4 et 14 caractères inclus.</div>';
        // cas d'erreur
        $erreur = 'oui';
    }

    //verify pseudo characters

    $verify_character = preg_match('#^[a-zA-Z0-9._-]+$#', $pseudo);

    if (!$verify_character) {
        $msg .= '<div class="alert alert-danger mb-3">Attention,<br>caractères autorisés pour le pseudo : a-z 0-9 _ . -</div>';
        // cas d'erreur
        $erreur = 'oui';
    }


    $verif_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $verif_pseudo->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $verif_pseudo->execute();
    if ($verif_pseudo->rowCount() > 0) {
        $msg .= '<div class="alert alert-danger mb-3">Attention,<br>Pseudo indisponible.</div>';
        // cas d'erreur
        $erreur = 'oui';
    }

    //? vérifier que le mdp n'est pas vide    
    if (empty($mdp)) {
        $msg .= '<div class="alert alert-danger mb-3">Attention,<br>le mot de passe est obligatoire.</div>';
        // cas d'erreur
        $erreur = 'oui';
    } else {
        // vérifier que le mdp et le confirm_mdp sont similaire si le mdp n'est pas vide
        if ($mdp != $confirm_mdp) {
            $msg .= '<div class="alert alert-danger mb-3">Attention,<br>le mot de passe et la confirmation du mot de passe doivent être identiques.</div>';
            // cas d'erreur
            $erreur = 'oui';
        }
    }

    //? Vérification du format mail
    if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $msg .= '<div class="alert alert-danger mb-3">Attention,<br>le format du mail n\'est pas correct.</div>';
        // cas d'erreur
        $erreur = 'oui';
    }

    //!enregistrement en database

    if ($erreur == 'non') {

        $mdp = password_hash($mdp, PASSWORD_DEFAULT);

        $enregistrement = $pdo->prepare("INSERT INTO membre (id_membre, pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (NULL, :pseudo,:mdp, :nom, :prenom,  :email, :civilite, 1, NOW())");
        $enregistrement->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $enregistrement->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $enregistrement->bindParam(':nom', $nom, PDO::PARAM_STR);
        $enregistrement->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $enregistrement->bindParam(':email', $email, PDO::PARAM_STR);
        $enregistrement->bindParam(':civilite', $sexe, PDO::PARAM_STR);
        $enregistrement->execute();
    }
}


//Debut des affichages
include 'inc/header.inc.php';
include 'inc/nav.inc.php';

?>




<div class="bg-light p-5 rounded text-center">
    <h1 class="letter">Inscription <i class="fa-solid fa-book"></i></h1>
</div>

<section class="container-fluid pt-3">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="head text-center py-3">
                                <h3>Inscrivez-vous</h3>
                            </div>
                        </div>
                    </div>
                    <?= $msg; // affichage des user messages
                    ?>

                    <form method="POST" class="border p-4">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="pseudo">Pseudo</label>
                                <input type="text" autocomplete="off" name="pseudo" id="pseudo" class="form-control" value="">
                            </div>
                            <div class="mb-3">
                                <label for="mdp">Mot de passe</label>
                                <input type="password" name="mdp" id="mdp" class="form-control" value="" autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <label for="confirm_mdp">Confirmation du mot de passe</label>
                                <input type="password" autocomplete="off" name="confirm_mdp" id="confirm_mdp" class="form-control" value="">
                            </div>
                            <div class="mb-3">
                                <label for="nom">Nom</label>
                                <input type="text" autocomplete="off" name="nom" id="nom" class="form-control" value="">
                            </div>
                            <div class="mb-3">
                                <label for="prenom">Prénom</label>
                                <input type="text" autocomplete="off" name="prenom" id="prenom" class="form-control" value="">
                            </div>
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="text" autocomplete="off" name="email" id="email" class="form-control" value="">
                            </div>
                            <div class="mb-3">
                                <label for="sexe">Sexe</label>
                                <select name="sexe" id="sexe" class="form-select">
                                    <option value="m">Homme</option>
                                    <option value="f">Femme</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <button type="submit" id="inscription" class="w-100 btn btn-outline-danger">Register </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<?php
include 'inc/footer.inc.php';

?>