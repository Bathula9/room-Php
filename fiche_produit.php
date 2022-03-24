<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

if (empty($_GET['id_produit'])) {
    header('location: index.php');
}

// récupération des informations du produit en BDD
$infos_produit = $pdo->prepare("SELECT * FROM salle, produit WHERE produit.id_salle = salle.id_salle AND id_produit = :id_produit");
$infos_produit->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
$infos_produit->execute();

if ($infos_produit->rowCount() < 1) {
    header('location: index.php');
}

$produit = $infos_produit->fetch(PDO::FETCH_ASSOC);

//CODE ...


//$googlestr = $produit['adresse'] . ',' . '+' . $produit['ville'] . ',' . '+' . $produit['pays'];
//echo $googlestr;

//code for the form

if (isset($_POST['commentaire']) && isset($_POST['note'])) {


    $id_membre = $_SESSION['membre']['id_membre'];
    $id_salle = $produit['id_salle'];
    $commentaire = trim($_POST['commentaire']);
    $note = trim($_POST['note']);

    $erreur = false;

    if (!$erreur) {
        // Enregistrement des avis 
        $enregistrement = $pdo->prepare("INSERT INTO avis (id_avis,id_membre, id_salle, commentaire, note, date_enregistrement) VALUES (NULL, :id_membre, :id_salle, :commentaire, :note, NOW())");

        $enregistrement->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);
        $enregistrement->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);
        $enregistrement->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
        $enregistrement->bindParam(':note', $note, PDO::PARAM_STR);
        $enregistrement->execute();

        header('location: fiche_produit.php?id_produit' . $_GET['id_produit']);
        exit();
    }
}

//code for the reservation part

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// Commande produit
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] == 'reserver' && !empty($_GET['id_membre']) && !empty($_GET['id_produit'])) {

    $reservation = $pdo->prepare("SELECT * FROM commande WHERE id_commande = :id_commande");
    $reservation->bindParam(':id_commande', $_GET['id_commande'], PDO::PARAM_STR);
    $reservation->execute();
    $msg .= '<div class="alert alert-success mb-3">La salle n°' . $_GET['id_produit'] . ' a bien été commandé.</div>';
}


//Enregistrer commande

if (
    isset($_POST['id_membre']) && isset($_POST['id_produit'])
) {
    $id_membre = $_SESSION['membre']['id_membre'];
    $id_salle = $produit['id_salle'];

    $enregistrer = $pdo->prepare("INSERT INTO commande (id_avis,id_commande, id_salle, commentaire, note, date_enregistrement) VALUES (NULL, :id_membre, :id_salle, :commentaire, :note, NOW())");

    $enregistrer->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);
    $enregistrer->bindParam(':id_produit', $id_produit, PDO::PARAM_STR);

    $enregistrer->execute();
}












//Debut des affichages
include 'inc/header.inc.php';
include 'inc/nav.inc.php';

?>

<div class="bg-light p-5 rounded text-center">
    <h1 class="letter">Salle <?= ucfirst($produit['titre']); ?> <i class="fa-solid fa-book"></i></h1>
</div>
<div class="text-end mt-2">
    <a href="?action=reserver&id_commande="></a>
    <button class="btn btn-outline-danger">Reserver</button>
</div>

<div class="container mt-4">
    <div class="row align-content-center">
        <div class="col-lg-7 col-md-12 mb-lg-0 mb-5">
            <div class="card border-0">
                <img src="<?= URL; ?>assets/img_produit/<?= $produit['photo'] ?>" alt="salle" class="img-fluid" />
            </div>
        </div>
        <div class="col-lg-5 col-md-12 my-auto">
            <p>
                <?= ucfirst($produit['description']); ?> <br>
                <!-- <div class="col">
                <th width="446" class="entry" scope="col"><iframe width="441" height="243" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.co.za/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $googlestr; ?>&amp;aq=&amp;ie=UTF8&amp;output=embed"></iframe><br />
            </div> -->
            </p>
        </div>

    </div>
</div>
<div class="container my-5">
    <div class="row">
        <div class="col-4">
            <p><i class="fas fa-calendar"></i> Arrivee : <?= $produit['date_arrivee']; ?></p>
            <p><i class="fas fa-calendar-alt"></i> Depart : <?= $produit['date_depart']; ?></p>
        </div>
        <div class="col-4">
            <p><i class="fas fa-users"></i> Capacite : <?= $produit['capacite']; ?></p>
            <p><i class="fas fa-folder-open"></i> Categorie : <?= $produit['categorie']; ?></p>
        </div>
        <div class="col-4">
            <p><i class="fas fa-map-marker-alt"></i> Adresse : <?= $produit['adresse'] . ' ' . $produit['cp'] . ' ' . $produit['ville']; ?></p>
            <p><i class="fas fa-euro-sign"></i> Prix : <?= $produit['prix']; ?> &euro;</p>
        </div>
    </div>
</div>


<div class="container mt-3">
    <div class="row">
        <?php if (user_is_connected()) { ?>
            <div class="col d-flex justify-content-between">

                <a href="index.php">Retour vers le catalogue</a>


            </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-sm-6 mx-auto">
        <?= $msg; // affichage des messages utilisateur  
        ?>
        <form action="" method="post" class="border mt-3 p-3 mb-4">

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
                <button class="btn btn-outline-danger" type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>

<?php } else { ?>
    <a href="inscription.php">Connectez-vous</a>
<?php } ?>



<?php
include 'inc/footer.inc.php';

?>