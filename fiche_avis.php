<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

//CODE ...





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
        <form action="fiche_avis.php" method="post" class="border mt-3 p-3 mb-4">
            <!-- champ caché id_produit pour la modification -->
            <!-- <input type="hidden" name="id_produit" id="id_produit" value="<?= $id_produit; ?>"> -->

            <div class="mb-3">
                <label for="avis">Laissez vos commentaires</label>
                <input type="text" name="avis" class="form-control" id="avis" autocomplete="off" placeholder="" />
            </div>

            <div class="mb-3 col-md-12 text-center">
                <button class="btn btn-danger" type="submit">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<?php
include 'inc/footer.inc.php';

?>