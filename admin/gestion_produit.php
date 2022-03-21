<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

//CODE ...



$liste_salles = $pdo->query("SELECT * FROM salle ORDER BY id_salle");


//Debut des affichages
include '../inc/header.inc.php';
include '../inc/nav.inc.php';

?>




<div class="bg-light p-5 rounded text-center">
    <h1 class="letter">Gestion des produits <i class="fa-solid fa-book"></i></h1>
</div>

<div class="row mt-4">
    <div class="col-sm-12">
        <form action="gestion_produit.php" method="post" class="border mt-3 p-3 mb-4">

            <div class="mb-3">
                <label for="salle">Salle</label>
                <select name="salle" id="salle" class="form-select">
                    <?php
                    while ($ligne = $liste_salles->fetch(PDO::FETCH_ASSOC)) {

                        echo '<option value="' . $ligne['id_salle'] . '">' . $ligne['id_salle'] . ' - ' . 'Salle '  . $ligne['titre'] . ' - '  .   $ligne['adresse'] . ' ' . $ligne['cp'] . ' ' . $ligne['ville'] . ' - ' . $ligne['capacite'] . ' pers' . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="date_arrivee">Date d'arrivée</label>
                <input type="datetime-local" name="date_arrivee" class="form-control" id="date_arrivee" value="<?= $date_arrivee; ?>" autocomplete="off" />
            </div>
            <div class="mb-3">
                <label for="date_depart">Date d'départ</label>
                <input type="datetime-local" name="date_depart" class="form-control" id="date_depart" autocomplete="off" />
            </div>
            <div class="mb-3">
                <label for="prix">Prix</label>
                <input type="text" name="prix" class="form-control" id="prix" autocomplete="off" placeholder="prix en euros" />
            </div>
            <div class="mb-3 col-md-12 text-center">

                <button class="btn btn-danger" type="submit">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<?php
include '../inc/footer.inc.php';

?>