<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

//CODE ...

if (isset($_POST['id_membre']) && isset($_POST['id_salle']) && isset($_POST['commentaire']) && isset($_POST['note'])) {


    $commentaire = trim($_POST['commentaire']);
    $radio = trim($_POST['note']);
    $commentaire = trim($_POST['id_membre']);
    $commentaire = trim($_POST['id_salle']);

    $erreur = false;

    if (!$erreur) {
        // Enregistrement des avis 
        $enregistrement = $pdo->prepare("INSERT INTO avis (id_avis,id_membre, id_salle, commentaire, note, date_enregistrement) VALUES (NULL, id_membre, :id_salle, :commentaire, :note, NOW()");

        $enregistrement->bindParam('id_avis', $titre, PDO::PARAM_STR);
        $enregistrement->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);
        $enregistrement->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);
        $enregistrement->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
        $enregistrement->bindParam(':note', $note, PDO::PARAM_STR);
        $enregistrement->execute();

        header('location: fiche_avis.php');
        exit();
    }
}



//Debut des affichages
include 'inc/header.inc.php';
include 'inc/nav.inc.php';

?>

<div class="bg-light p-5 rounded text-center">
    <h1 class="letter">Laisse votre avis <i class="fa-solid fa-book"></i></h1>
</div>

<div class="row mt-4">
    <div class="col-sm-6 mx-auto">
        <?= $msg; // affichage des messages utilisateur  
        ?>
        <form action="admin/gestion_avis.php" method="post" class="border mt-3 p-3 mb-4">

            <div class="form-check">
                <input class="form-check-input" type="radio" name="note" id="radio" value="5" checked>
                <label class="form-check-label" for="radio">
                    &starf;&starf;&starf;&starf;&starf;
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="note" value="4" id="radio">
                <label class="form-check-label" for="radio">
                    &starf;&starf;&starf;&starf;
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="note" value="3" id="radio">
                <label class="form-check-label" for="radio">
                    &starf;&starf;&starf;
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="note" value="2" id="radio">
                <label class="form-check-label" for="radio">
                    &starf;&starf;
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="note" value="1" id="radio">
                <label class="form-check-label" for="radio">
                    &starf;
                </label>
            </div>

            <div class="my-3">
                <label for="avis">Laissez vos commentaires</label>
                <input type="text" name="commentaire" class="form-control" id="avis" autocomplete="off" placeholder="Leave your comments" />
            </div>

            <div class="mb-3 col-md-12 text-center">
                <button class="btn btn-danger" value="commentaire" type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>

<?php
include 'inc/footer.inc.php';

?>