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
// Enregistrement salle
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

    $erreur = false;

    //vérification disponibilité référence :
    $verif_titre = $pdo->prepare("SELECT * FROM salle WHERE titre = :titre");
    $verif_titre->bindParam(':titre', $titre, PDO::PARAM_STR);
    $verif_titre->execute();
    //on vérifie si on a récupéré une ligne que si l'id_produit est vide (si l'id_produit n'est pas vide, c'est une modif et la référence existe déjà)
    if ($verif_titre->rowCount() > 0 && empty($id_salle)) {
        $msg .= '<div class="alert alert-danger mb-3">Attention,<br>titre indisponible.</div>';
        //cas d'erreur 
        $erreur = true;
    }

    // Contrôles sur la photo
    // Superglobale pour les pièces jointes d'un formulaire : $_FILES (obligatoire de mettre l'attribut enctype="" sur le form)
    if (!empty($_FILES['photo']['name']) && !$erreur) {
        // on déclare un tableau avec les formats acceptés 
        $tab_formats = array('png', 'jpg', 'jpeg', 'gif', 'webp');

        // on récupère le format du fichier chargé : on découpe depuis la fin et on remonte au point : strrchr()
        $extension = strrchr($_FILES['photo']['name'], '.'); // exemple : pour le fichier photo.png on récupère .png

        // On enlève le . de la chaine et on passe la chaine en minuscule :
        $extension = strtolower(substr($extension, 1)); // exemple : .png on obtient png

        // on vérifie si l'extension correspond à une des valeurs placées dans le tableau array :
        // in_array('valeur', 'tableau');
        if (in_array($extension, $tab_formats)) {

            // le nom de la photo peut correspondre à une autre photo déjà enregistrée. Pour éviter de l'écraser, on place la référence (qui est unique) devant le nom de la photo
            $photo = $titre . '-' . $_FILES['photo']['name'];

            // on enlève les caractères spéciaux
            $photo = preg_replace('/[^a-zA-Z0-9._-]/', '', $photo);

            // copy(emplacement, dossier cible)
            $dossier_cible = ROOT_PATH . ROOT_SITE . 'assets/img_produit/' . $photo;
            copy($_FILES['photo']['tmp_name'], $dossier_cible);
        } else {
            $msg .= '<div class="alert alert-danger mb-3">Attention,<br>la photo n\'a pas un format valide pour le web.</div>';
            // cas d'erreur 
            $erreur = true;
        }


        // Enregistrement de la salle 
        $enregistrement = $pdo->prepare("INSERT INTO salle (id_salle, titre, description, photo, pays, ville, adresse, cp, capacite,categorie) VALUES (NULL, :titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");

        $enregistrement->bindParam(':titre', $titre, PDO::PARAM_STR);
        $enregistrement->bindParam(':description', $description, PDO::PARAM_STR);
        $enregistrement->bindParam(':photo', $photo, PDO::PARAM_STR);
        $enregistrement->bindParam(':pays', $pays, PDO::PARAM_STR);
        $enregistrement->bindParam(':ville', $ville, PDO::PARAM_STR);
        $enregistrement->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $enregistrement->bindParam(':cp', $cp, PDO::PARAM_STR);
        $enregistrement->bindParam(':capacite', $capacite, PDO::PARAM_STR);
        $enregistrement->bindParam(':categorie', $categorie, PDO::PARAM_STR);
        $enregistrement->execute();

        header('location: gestion_salles.php');
        exit();
    }
}



//Debut des affichages
include '../inc/header.inc.php';
include '../inc/nav.inc.php';
?>

<?php
// Récupération des produits en BDD
$liste_salle = $pdo->query("SELECT * FROM salle");

?>


<div class="bg-light p-5 rounded text-center">
    <h1 class="letter">Gestion Salles <i class="fa-solid fa-book"></i></h1>
</div>

<div class="row mt-4">
    <div class="col-sm-12">
        <?= $msg; // affichage des messages utilisateur  
        ?>
        <form method="post" action="gestion_salles.php" class="border p-3 row" enctype="multipart/form-data">
            <!-- champ caché id_produit pour la modification -->
            <!-- <input type="hidden" name="id_produit" id="id_produit" value=""> -->
            <!-- champ caché id_produit pour la modification -->
            <div class="col-sm-6">

                <div class="mb-3">
                    <label for="titre">Titre</label>
                    <input type="text" name="titre" id="titre" class="form-control" value="<?= $titre; ?>">
                </div>
                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4"><?= $description; ?></textarea>
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
                        <option value="Paris">Paris</option>
                        <option value="Lyon">Lyon</option>
                        <option value="Marseille">Marseille</option>
                    </select>
                </div>



            </div>
            <div class="col-sm-6">
                <div class="mb-3">
                    <label for="adresse">Adresse</label>
                    <textarea name="adresse" id="adresse" class="form-control" rows="4"><?= $adresse; ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="cp">Code Postal</label>
                    <input type="text" name="cp" id="cp" class="form-control" value="<?= $cp; ?>">
                </div>
                <div class="mb-3">
                    <label for="capacite">Capacité</label>
                    <input type="capacite" name="capacite" id="capacite" class="form-control" value="<?= $capacite; ?>">
                    </select>
                </div>

                <div class="mb-3">
                    <label for="categorie">Catégorie</label>
                    <select name="categorie" id="categorie" class="form-select">
                        <option value="reunion">Réunion</option>
                        <option value="bureau">Bureau</option>
                        <option value="formation">Formation</option>
                    </select>
                </div>
                <div class="mt-4">
                    <button type="submit" id="enregistrement_salle" class="w-100 btn btn-outline-danger"> Enregistrer </button>
                </div>
            </div>
        </form>

    </div>
</div>




<div class="row mt-4">
    <div class="col-12">
        <table class="table table-bordered text-center">
            <thead class="bg-red text-white">
                <tr>
                    <th>Id salle</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Photo</th>
                    <th>Pays</th>
                    <th>Ville</th>
                    <th>Adresse</th>
                    <th>Code postal</th>
                    <th>Capacite</th>
                    <th>Catégorie</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($ligne = $liste_salle->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td>' . $ligne['id_salle'] . '</td>';
                    echo '<td>' . $ligne['titre'] . '</td>';
                    echo '<td>' . substr($ligne['description'], 0, 5) . ' <a href="#">...</a></td>';
                    echo '<td><img src="' . URL . 'assets/img_produit/' . $ligne['photo'] . '" width="50"></td>';
                    echo '<td>' . $ligne['pays'] . '</td>';
                    echo '<td>' . $ligne['ville'] . '</td>';
                    echo '<td>' . $ligne['adresse'] . '</td>';
                    echo '<td>' . $ligne['cp'] . '</td>';
                    echo '<td>' . $ligne['capacite'] . '</td>';
                    echo '<td>' . $ligne['categorie'] . '</td>';

                    echo '<td><a href="?action=modifier&id_produit=' . $ligne['id_salle'] . '" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a></td>';

                    echo '<td><a href="?action=supprimer&id_produit=' . $ligne['id_salle'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fa-solid fa-trash-can"></i></a></td>';

                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


<?php
include '../inc/footer.inc.php';

?>