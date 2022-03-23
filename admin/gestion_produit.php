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
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_produit'])) {

    $suppression = $pdo->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
    $suppression->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
    $suppression->execute();
    $msg .= '<div class="alert alert-success mb-3">Le produit n°' . $_GET['id_produit'] . ' a bien été supprimé.</div>';
}

//Do not modify the below line - to diplay all the rooms
$liste_salle = $pdo->query("SELECT * FROM salle ORDER BY id_salle");

$id_produit = ''; // utilisé pour la modif

$date_arrivee = '';
$date_depart = '';
$id_salle = '';
$prix = '';

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// Modification produit
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_produit'])) {
    $recup_produit = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $recup_produit->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
    $recup_produit->execute();

    if ($recup_produit->rowCount() > 0) {
        $infos_produit = $recup_produit->fetch(PDO::FETCH_ASSOC);

        $id_produit = $infos_produit['id_produit']; // utilisée pour la modif

        $date_arrivee = $infos_produit['date_arrivee'];
        $date_depart = $infos_produit['date_depart'];
        $id_salle = $infos_produit['id_salle'];
        $prix = $infos_produit['prix'];
    }
}


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// Enregistrement produit
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

if (isset($_POST['id_salle']) && isset($_POST['date_arrivee']) && isset($_POST['date_depart']) && isset($_POST['prix'])) {

    $id_salle = trim($_POST['id_salle']);
    $date_arrivee = trim($_POST['date_arrivee']);
    $date_depart = trim($_POST['date_depart']);
    $prix = trim($_POST['prix']);

    //echo 'Ok'; // To verify if it works

    $erreur = false;

    // Pour la modification :
    // récupération de l'id_produit
    if (!empty($_POST['id_produit'])) {
        $id_produit = $_POST['id_produit'];
    }

    //Si prix est vide, on affecte 0 pour éviter une erreur sql
    if (empty($prix) || !is_numeric($prix)) {
        $_SESSION['message_utilisateur'] .= '<div class="alert alert-warning mb-3">Attention,<br>le prix a été affecté à 0.</div>';
        $prix = 0;
    }

    if (!$erreur) {

        // si l'id_produit est vide : INSERT INTO sinon : UPDATE
        if (!empty($id_produit)) {
            // Modification du produit 
            $enregistrement = $pdo->prepare("UPDATE produit SET id_salle = :id_salle, date_arrivee = :date_arrivee,date_depart = :date_depart,prix = :prix WHERE id_produit = :id_produit");
            $enregistrement->bindParam(':id_produit', $id_produit, PDO::PARAM_STR);

            // on crée un message dans la session pour confirmation la modif:
            $_SESSION['message_utilisateur'] .= '<div class="alert alert-success mb-3">Le produit n°' . $id_produit . ' a bien été modifié.</div>';
        } else {
            // Enregistrement du produit 
            $enregistrement = $pdo->prepare("INSERT INTO produit (id_produit, id_salle, date_arrivee, date_depart, prix, etat) VALUES (NULL, :id_salle, :date_arrivee, :date_depart, :prix, 'libre')");
        }

        $enregistrement->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);
        $enregistrement->bindParam(':date_arrivee', $date_arrivee, PDO::PARAM_STR);
        $enregistrement->bindParam(':date_depart', $date_depart, PDO::PARAM_STR);
        $enregistrement->bindParam(':prix', $prix, PDO::PARAM_STR);
        $enregistrement->execute();

        header('location: gestion_produit.php');
        exit();
    }
}

// Message si modification
if (!empty($_SESSION['message_utilisateur'])) {
    $msg .= $_SESSION['message_utilisateur']; // on affiche le message
    $_SESSION['message_utilisateur'] = ''; // on vide le message
}

//for the products
$liste_produit = $pdo->query("SELECT * FROM produit,salle WHERE produit.id_salle = salle.id_salle ORDER BY id_produit");



//Debut des affichages
include '../inc/header.inc.php';
include '../inc/nav.inc.php';

?>




<div class="bg-light p-5 rounded text-center">
    <h1 class="letter">Gestion des produits <i class="fa-solid fa-book"></i></h1>
</div>

<div class="row mt-4">
    <div class="col-sm-6 mx-auto">
        <?= $msg; // affichage des messages utilisateur  
        ?>
        <form action="gestion_produit.php" method="post" class="border mt-3 p-3 mb-4">
            <!-- champ caché id_produit pour la modification -->
            <input type="hidden" name="id_produit" id="id_produit" value="<?= $id_produit; ?>">


            <div class="mb-3">
                <label for="id_salle">Salle :</label>
                <select name="id_salle" id="id_salle" class="form-select">
                    <?php
                    while ($ligne = $liste_salle->fetch(PDO::FETCH_ASSOC)) {
                        // on remet la variable $selected vide à chaque tour de boucle
                        $selected = '';

                        if ($id_salle == $ligne['id_salle']) {
                            // si la valeur précédemment choisie est égal à la valeur de la BDD on met 'selected' dans la variable pour que cette option soit affichée par défaut.
                            $selected = 'selected';
                        }

                        echo '<option value="' . $ligne['id_salle'] . '" ' . $selected . '>' . $ligne['id_salle'] . ' - ' . ' Salle ' . $ligne['titre'] . ' - ' .  $ligne['adresse'] . ', ' .  $ligne['cp'] . ', ' .  $ligne['ville'] . ' - ' . ($ligne['capacite']) . ' pers' . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="date_arrivee">Date d'arrivée</label>
                <input type="datetime-local" name="date_arrivee" class="form-control" id="date_arrivee" value="<?= str_replace(" ", "T", substr($infos_produit["date_arrivee"], 0, 16)); ?>" />
            </div>
            <div class="mb-3">
                <label for="date_depart">Date d'départ</label>
                <input type="datetime-local" name="date_depart" class="form-control" id="date_depart" value="<?= str_replace(" ", "T", substr($infos_produit["date_depart"], 0, 16)); ?>" />
            </div>
            <div class="mb-3">
                <label for="prix">Prix</label>
                <input type="text" name="prix" class="form-control" id="prix" autocomplete="off" placeholder="prix en euros" value="<?= $prix; ?>" />
            </div>

            <div class="mb-3 col-md-12 text-center">
                <button class="btn btn-danger" type="submit">Ajouter</button>
            </div>
        </form>
    </div>
</div>


<div class="row mt-4">
    <div class="col-12">
        <table class="table table-bordered text-center">
            <thead class="bg-red text-white text-center">
                <tr>
                    <th>Id produit</th>
                    <th>Date d'arrivée</th>
                    <th>Date d'départ</th>
                    <th>Id Salle</th>
                    <th>Prix</th>
                    <th>Etat</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($ligne = $liste_produit->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td>' . $ligne['id_produit'] . '</td>';
                    echo '<td>' . $ligne['date_arrivee'] . '</td>';
                    echo '<td>' . $ligne['date_depart'] . '</td>';
                    echo '<td>' . $ligne['id_salle'] . ' - ' . 'Salle ' . $ligne['titre'] . '<br>' . '<img src="' . URL . 'assets/img_produit/' . $ligne['photo'] . '" alt="salle" style="width :100px">' .  '</td>';
                    echo '<td>' . $ligne['prix'] . ' &euro;' . '</td>';
                    echo '<td>' . $ligne['etat'] . '</td>';

                    echo '<td><a href="?action=modifier&id_produit=' . $ligne['id_produit'] . '" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a></td>';

                    echo '<td><a href="?action=supprimer&id_produit=' . $ligne['id_produit'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fa-solid fa-trash-can"></i></a></td>';

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