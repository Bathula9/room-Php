<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

//CODE ...
// Restriction d'accès : si l'utilisateur n'est pas admin, on le redirige vers connexion.php
if (!user_is_admin()) {
    header('location: ../connexion.php');
    exit(); // permet de bloquer le code php de la page (dans le cas ou qq'un passerait des informations get dans l'url)
}

$titre = '';
$description = '';
$pays = '';
$ville = '';
$adresse = '';
$cp = '';
$capacite = '';
$categorie = '';
$photo = '';


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// Enregistrement produit
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

if (isset($_POST['titre']) && isset($_POST['description']) && isset($_POST['pays']) && isset($_POST['ville']) && isset($_POST['adresse']) && isset($_POST['cp']) && isset($_POST['capacite']) && isset($_POST['categorie'])) {


    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $pays = trim($_POST['pays']);
    $ville = trim($_POST['ville']);
    $adresse = trim($_POST['adresse']);
    $cp = trim($_POST['cp']);
    $capacite = trim($_POST['capacite']);
    // pas pour $photo
    $categorie = trim($_POST['categorie']);


    echo '<pre>';
    print_r($_POST);
    echo '</pre>';



    //$erreur = false;

    // $enregistrement->bindParam(':titre', $titre, PDO::PARAM_STR);
    // $enregistrement->bindParam(':categorie', $categorie, PDO::PARAM_STR);
    // $enregistrement->bindParam(':description', $description, PDO::PARAM_STR);
    // $enregistrement->bindParam(':id_couleur', $id_couleur, PDO::PARAM_STR);
    // $enregistrement->bindParam(':taille', $taille, PDO::PARAM_STR);
    // $enregistrement->bindParam(':sexe', $sexe, PDO::PARAM_STR);
    // $enregistrement->bindParam(':photo', $photo, PDO::PARAM_STR);
    // $enregistrement->bindParam(':prix', $prix, PDO::PARAM_STR);
    // $enregistrement->bindParam(':stock', $stock, PDO::PARAM_STR);
    // $enregistrement->execute();

    // header('location: gestion_produit.php');
    // exit();
}



//Debut des affichages
include '../inc/header.inc.php';
include '../inc/nav.inc.php';
?>




<div class="bg-light p-5 rounded text-center">
    <h1 class="letter">Gestion produit <i class="fa-solid fa-book"></i></h1>
</div>

<div class="row mt-4">
    <div class="col-sm-12">
        <?= $msg; // affichage des messages utilisateur  
        ?>
        <form method="post" action="gestion_produit.php" class="border p-3 row" enctype="multipart/form-data">
            <!-- champ caché id_produit pour la modification -->
            <input type="hidden" name="id_produit" id="id_produit" value="">
            <!-- champ caché id_produit pour la modification -->
            <div class="col-sm-6">

                <div class="mb-3">
                    <label for="titre">Titre</label>
                    <input type="text" name="titre" id="titre" class="form-control" value="">
                </div>
                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4"></textarea>
                </div>
                <div class="mb-3">
                    <label for="photo">Photo</label>
                    <input type="file" name="photo" id="photo" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="pays">Pays</label>
                    <select name="pays" id="pays" class="form-select">
                        <option>France</option>

                    </select>
                </div>
                <div class="mb-3">
                    <label for="ville">Ville</label>
                    <select name="ville" id="ville" class="form-select">
                        <option value="p">Paris</option>
                        <option value="l">Lyon</option>
                        <option value="m">Marseille</option>
                    </select>
                </div>



            </div>
            <div class="col-sm-6">
                <div class="mb-3">
                    <label for="adresse">Adresse</label>
                    <textarea name="adresse" id="adresse" class="form-control" rows="4"></textarea>
                </div>
                <div class="mb-4">
                    <label for="cp">Code Postal</label>
                    <input type="text" name="cp" id="cp" class="form-control" value="">
                </div>
                <div class="mb-3">
                    <label for="capacite">Capacité</label>
                    <input type="capacite" name="capacite" id="capacite" class="form-control" value="">
                    </select>
                </div>

                <div class="mb-3">
                    <label for="categorie">Catégorie</label>
                    <select name="categorie" id="categorie" class="form-select">
                        <option value="r">Réunion</option>
                        <option value="b">Bureau</option>
                        <option value="f">Formation</option>
                    </select>
                </div>
                <div class="mt-4">
                    <button type="submit" id="enregistrement_produit" class="w-100 btn btn-outline-danger"> Enregistrer </button>
                </div>
            </div>
        </form>

    </div>
</div>

<?php
include '../inc/footer.inc.php';

?>