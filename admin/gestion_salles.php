<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

//CODE ...
// Restriction d'accès : si l'utilisateur n'est pas admin, on le redirige vers connexion.php
if (!user_is_admin()) {
    header('location: ../connexion.php');
    exit(); // permet de bloquer le code php de la page (dans le cas ou qq'un passerait des informations get dans l'url)
}

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// Suppression produit
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_salle'])) {

    // on va chercher en bdd les infos de ce produit afin de connaitre la photo qui doit être supprimée
    $recup_photo = $pdo->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
    $recup_photo->bindParam(':id_salle', $_GET['id_salle'], PDO::PARAM_STR);
    $recup_photo->execute();

    if ($recup_photo->rowCount() > 0) {
        $infos = $recup_photo->fetch(PDO::FETCH_ASSOC);
        $chemin_photo = ROOT_PATH . ROOT_SITE . 'assets/img_produit/' . $infos['photo'];
        if (!empty($infos['photo']) && file_exists($chemin_photo)) {
            //unlink() permet de supprimer un fichier sur le serveur
            unlink($chemin_photo);
        }
    }

    $suppression = $pdo->prepare("DELETE FROM salle WHERE id_salle = :id_salle");
    $suppression->bindParam(':id_salle', $_GET['id_salle'], PDO::PARAM_STR);
    $suppression->execute();
    $msg .= '<div class="alert alert-success mb-3">Le produit n°' . $_GET['id_salle'] . ' a bien été supprimé.</div>';
}

$id_salle = ''; // utilisé pour la modif


$titre = '';
$description = '';
$photo = '';
$pays = '';
$ville = '';
$adresse = '';
$cp = '';
$capacite = '';
$categorie = '';

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// Modification produit
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_salle'])) {
    $recup_salle = $pdo->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
    $recup_salle->bindParam(':id_salle', $_GET['id_salle'], PDO::PARAM_STR);
    $recup_salle->execute();

    if ($recup_salle->rowCount() > 0) {
        $infos_salle = $recup_salle->fetch(PDO::FETCH_ASSOC);

        $id_salle = $infos_salle['id_salle']; // utilisée pour la modif

        $titre = $infos_salle['titre'];
        $description = $infos_salle['description'];
        $photo_actuelle = $infos_salle['photo']; // utilisée pour conserver l'ancienne photo pour la modif
        $pays = $infos_salle['pays'];
        $ville = $infos_salle['ville'];
        $adresse = $infos_salle['adresse'];
        $cp = $infos_salle['cp'];
        $capacite = $infos_salle['capacite'];
        $categorie = $infos_salle['categorie'];
    }
}

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


    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';

    $erreur = false;

    // Pour la modification :
    // récupération de l'id_salle et de la photo actuelle
    if (!empty($_POST['id_salle'])) {
        $id_salle = $_POST['id_salle'];
    }
    if (!empty($_POST['photo_actuelle'])) {
        $photo = $_POST['photo_actuelle'];
    }

    //vérification disponibilité référence :
    $verif_titre = $pdo->prepare("SELECT * FROM salle WHERE titre = :titre");
    $verif_titre->bindParam(':titre', $titre, PDO::PARAM_STR);
    $verif_titre->execute();
    //on vérifie si on a récupéré une ligne que si l'id_salle est vide (si l'id_salle n'est pas vide, c'est une modif et le titre existe déjà)
    if ($verif_titre->rowCount() > 0 && empty($id_salle)) {
        $msg .= '<div class="alert alert-danger mb-3">Attention,<br>titre indisponible.</div>';
        //cas d'erreur 
        $erreur = true;
    }

    // Si capacite est vide, on affecte 0 pour éviter une erreur sql
    if (empty($capacite) || !is_numeric($capacite)) {
        $_SESSION['message_utilisateur'] .= '<div class="alert alert-warning mb-3">Attention,<br>le capacité a été affecté à 0.</div>';
        $capacite = 0;
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

            // le nom de la photo peut correspondre à une autre photo déjà enregistrée. Pour éviter de l'écraser, on place le titre (qui est unique) devant le nom de la photo
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
    }

    if (!$erreur) {

        // si l'id_salle est vide : INSERT INTO sinon : UPDATE
        if (!empty($id_salle)) {
            // Modification du salle 
            $enregistrement = $pdo->prepare("UPDATE salle SET titre = :titre, description = :description, photo = :photo, pays = :pays , ville = :ville,adresse = :adresse,cp = :cp,capacite = :capacite, categorie = :categorie WHERE id_salle = :id_salle");
            $enregistrement->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);

            // on crée un message dans la session pour confirmation la modif:
            $_SESSION['message_utilisateur'] .= '<div class="alert alert-success mb-3">La salle n°' . $id_salle . ' a bien été modifié.</div>';
        } else {
            // Enregistrement de la salle 
            $enregistrement = $pdo->prepare("INSERT INTO salle (id_salle, titre, description, photo, pays, ville, adresse, cp, capacite,categorie) VALUES (NULL, :titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite,:categorie)");
            $enregistrement->bindParam(':titre', $titre, PDO::PARAM_STR);
        }


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

// Message si modification
if (!empty($_SESSION['message_utilisateur'])) {
    $msg .= $_SESSION['message_utilisateur']; // on affiche le message
    $_SESSION['message_utilisateur'] = ''; // on vide le message
}

// Récupération des salles en BDD
$liste_salle = $pdo->query("SELECT * FROM salle");
//do i add WHERE salle.id_salle = produit.id_salle   ???????????

//Debut des affichages
include '../inc/header.inc.php';
include '../inc/nav.inc.php';
?>


<div class="bg-light p-5 rounded text-center">
    <h1 class="letter">Gestion Salles <i class="fa-solid fa-book"></i></h1>
</div>

<div class="row mt-4">
    <div class="col-sm-12">
        <?= $msg; // affichage des messages utilisateur  
        ?>
        <form method="post" action="gestion_salles.php" class="border p-3 row" enctype="multipart/form-data">
            <!-- champ caché id_salle pour la modification -->
            <input type="hidden" name="id_salle" id="id_salle" value="<?= $id_salle; ?>">
            <!-- champ caché id_salle pour la modification -->
            <div class="col-sm-6">

                <div class="mb-3">
                    <label for="titre">Titre</label>
                    <input type="text" name="titre" id="titre" class="form-control" <?php if (!empty($id_salle)) {
                                                                                        echo 'readonly';
                                                                                    } ?> value="<?= $titre; ?>">
                </div>
                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4"><?= $description; ?></textarea>
                </div>

                <?php
                // conservation de l'ancienne image lors d'une modification produit.
                if (!empty($photo_actuelle)) {
                    echo '<div class="mb-3">';
                    echo '<label for="photo_actuelle">Photo actuelle</label><hr>';
                    echo '<img src="' . URL . 'assets/img_produit/' . $photo_actuelle . '" width="100">';
                    echo '<input type="hidden" name="photo_actuelle" value="' . $photo_actuelle . '">';
                    echo '</div>';
                }

                ?>

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

                        <option <?php if ($ville == 'Lyon') {
                                    echo ' selected ';
                                } ?>>Lyon</option>

                        <option <?php if ($ville == 'Marseille') {
                                    echo ' selected ';
                                } ?>>Marseille</option>
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

                        <option <?php if ($categorie == 'bureau') {
                                    echo ' selected ';
                                } ?>>Bureau</option>

                        <option <?php if ($categorie == 'formation') {
                                    echo ' selected ';
                                } ?>>Formation</option>


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
        <table class="table table-bordered text-center mb-4">
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

                    echo '<td><a href="?action=modifier&id_salle=' . $ligne['id_salle'] . '" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a></td>';

                    echo '<td><a href="?action=supprimer&id_salle=' . $ligne['id_salle'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fa-solid fa-trash-can"></i></a></td>';

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