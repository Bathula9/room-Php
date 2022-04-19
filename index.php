<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';


//CODE ...

// Récupération de la liste des catégories présentes en BDD sans doublons
$liste_categories = $pdo->query("SELECT DISTINCT categorie FROM salle ORDER BY categorie");
// Récupération de la liste des villes présentes en BDD sans doublons
$liste_villes = $pdo->query("SELECT DISTINCT ville FROM salle");
// Récupération de la liste des capacites présentes en BDD sans doublons
$liste_capacite = $pdo->query("SELECT DISTINCT capacite FROM salle");


// Récupération de tous les produits en BDD
if (isset($_GET['categorie'])) {

    $liste_produits = $pdo->prepare("SELECT * FROM produit,salle WHERE produit.id_salle = salle.id_salle AND categorie = :categorie ORDER BY categorie");
    $liste_produits->bindParam(':categorie', $_GET['categorie'], PDO::PARAM_STR);
    $liste_produits->execute();
} elseif (isset($_GET['ville'])) {

    $liste_produits = $pdo->prepare("SELECT * FROM produit,salle WHERE produit.id_salle = salle.id_salle AND ville = :ville ORDER BY ville");
    $liste_produits->bindParam(':ville', $_GET['ville'], PDO::PARAM_STR);
    $liste_produits->execute();
} elseif (isset($_GET['capacite'])) {

    $liste_produits = $pdo->prepare("SELECT * FROM produit,salle WHERE produit.id_salle = salle.id_salle AND capacite = :capacite ORDER BY capacite");
    $liste_produits->bindParam(':capacite', $_GET['capacite'], PDO::PARAM_STR);
    $liste_produits->execute();
} elseif (isset($_GET['date_arrivee'])) {

    $liste_produits = $pdo->prepare("SELECT * FROM produit, salle WHERE produit.id_salle = salle.id_salle AND date_arrivee = :date_arrivee AND etat = 'libre' ORDER BY categorie, titre");
    $liste_produits->bindParam(':date_arrivee', $_GET['date_arrivee'], PDO::PARAM_STR);
    $liste_produits->execute();
} elseif (isset($_GET['date_depart'])) {

    $liste_produits = $pdo->prepare("SELECT * FROM produit, salle WHERE produit.id_salle = salle.id_salle AND date_depart = :date_depart AND etat = 'libre' ORDER BY categorie, titre");
    $liste_produits->bindParam(':date_depart', $_GET['date_depart'], PDO::PARAM_STR);
    $liste_produits->execute();
} elseif (isset($_GET['rechercher'])) {

    $liste_produits = $pdo->prepare("SELECT * FROM produit, salle WHERE produit.id_salle = salle.id_salle  AND(titre LIKE :rechercher OR description LIKE :rechercher) ORDER BY categorie, titre");
    // on prépare l'argument car il faut les % pour le LIKE
    $rechercher = '%' . trim($_GET['rechercher']) . '%';

    $liste_produits->bindParam(':rechercher', $rechercher, PDO::PARAM_STR);
    $liste_produits->execute();
} else {
    $liste_produits = $pdo->query("SELECT * FROM produit, salle WHERE produit.id_salle = salle.id_salle AND etat = 'libre' ORDER BY categorie, titre");
}


//Debut des affichages
include 'inc/header.inc.php';
include 'inc/nav.inc.php';

?>

<div class="bg-light p-5 rounded text-center">
    <h1 class="letter">Room <i class="fa-solid fa-book"></i></h1>
</div>

<div class="row mt-4">
    <div class="col-sm-12">
        <?= $msg; // affichage des messages utilisateur  
        ?>
        <div class="row">
            <div class="col-sm-3 filtres">

                <?php
                // si $_GET n'est pas vide : un filtre est appliqué, on propose un lien pour annuler les filtres.
                if (!empty($_GET)) {
                    echo '<a href="index.php" class="btn btn-outline-danger w-100">Annuler les filtres</a><hr>';
                }
                ?>

                <h3 class="pb-3 border-bottom">Catégories</h3>
                <ul class="list-group">
                    <?php
                    while ($categorie = $liste_categories->fetch(PDO::FETCH_ASSOC)) {
                        echo '<li class="list-group-item"><a href="?categorie=' . $categorie['categorie'] . '" class="stretched-link">' . $categorie['categorie'] . '</a></li>';
                    }

                    ?>
                </ul>
                <h3 class="pb-3 border-bottom mt-3">Villes</h3>
                <ul class="list-group">
                    <?php
                    while ($ville = $liste_villes->fetch(PDO::FETCH_ASSOC)) {
                        echo '<li class="list-group-item"><a href="?ville=' . $ville['ville'] . '" class="stretched-link">' . $ville['ville'] . '</a></li>';
                    }

                    ?>
                </ul>
                <h3 class="pb-3 border-bottom mt-3">Capacite</h3>
                <ul class="list-group">
                    <?php
                    while ($capacite = $liste_capacite->fetch(PDO::FETCH_ASSOC)) {
                        echo '<li class="list-group-item"><a href="?capacite=' . $capacite['capacite'] . '" class="stretched-link">' . $capacite['capacite'] . '</a></li>';
                    }

                    ?>
                </ul>

                <h3 class="pb-3 border-bottom mt-3">Period</h3>
                <form class="p-1">
                    <div class="mb-3">
                        <label for="date_arrivee">Date d'arrivee</label>
                        <input type="datetime-local" name="date_arrivee" id="date_arrivee">
                        <button class="btn btn-sm mt-2 btn-danger">Ok</button>
                    </div>
                </form>
                <form class="p-1">

                    <div class="mb-3">
                        <label for="date_depart">Date de depart</label>
                        <input type="datetime-local" name="date_depart" id="date_depart">
                        <button class="btn btn-sm mt-2 btn-danger">Ok</button>

                    </div>
                </form>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <?php

                    if ($liste_produits->rowCount() > 0) {
                        // affichage des produits
                        while ($produit = $liste_produits->fetch(PDO::FETCH_ASSOC)) {
                            echo '<div class="col-lg-4 col-md-3 col-sm-6 mb-3">';
                            echo '<div class="card shadow">
                            <img src="' . URL . 'assets/img_produit/' . $produit['photo'] . '" class="card-img-top" alt="Image produit : ' . $produit['titre'] . '">
                            <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between">' . $produit['titre'] . '<span>' . $produit['prix'] . ' €' . '</span>' . '</h5>
                                <p class="card-text">Catégorie : ' . $produit['categorie'] . '</p>
                                <p class="card-text">' . '<i class="fa-solid fa-calendar"></i> ' . $produit['date_arrivee'] . ' au ' . $produit['date_depart'] . ' </p>
                                <a href="fiche_produit.php?id_produit=' . $produit['id_produit'] . '" class="btn-outline-danger w-100 stretched-link">Voir</a>
                            </div>
                        </div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="col-12"><h3 class="text-center text-royalblue pb-3 border-bottom">Aucun résultat ne correspond à votre recherche !</h3></div>';
                    }


                    ?>
                </div>
            </div>
        </div>


    </div>
</div>

<!-- *** -->


<?php
include 'inc/footer.inc.php';

?>