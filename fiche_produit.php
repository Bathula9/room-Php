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


$googlestr = $produit['adresse'] . ',' . '+' . $produit['ville'] . ',' . '+' . $produit['pays'];
//echo $googlestr;




//Debut des affichages
include 'inc/header.inc.php';
include 'inc/nav.inc.php';

?>




<div class="bg-light p-5 rounded text-center">
    <h1 class="letter">Produit : <?= ucfirst($produit['titre']); ?> <i class="fa-solid fa-book"></i></h1>
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
            <div class="col">
                <th width="446" class="entry" scope="col"><iframe width="441" height="243" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.co.za/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $googlestr; ?>&amp;aq=&amp;ie=UTF8&amp;output=embed"></iframe><br />
            </div>
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
        <div class="col d-flex justify-content-between">
            <a href="fiche_avis.php">Deposer un commentaire et une note</a>
            <a href="index.php">Retour vers le catalogue</a>
        </div>
    </div>
</div>
<?php
include 'inc/footer.inc.php';

?>