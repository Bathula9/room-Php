<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-red">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= URL; ?>index.php">Room</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?= URL; ?>index.php">Boutique</a>
                </li>

                <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">Espace membre</a>
                    <ul class="dropdown-menu" aria-labelledby="dropdown01">

                        <?php if (!user_is_connected()) { ?>

                            <li class="nav-item">
                                <a class="nav-link text-dark" href="<?= URL; ?>connexion.php">Connexion</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link text-dark" href="<?= URL; ?>inscription.php">Inscription</a>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="<?= URL; ?>profil.php">Profil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="<?= URL; ?>connexion.php?action=deconnexion">Déconnexion</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>

                <?php
                // menu administration
                if (user_is_admin()) {
                ?>

                    <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle <?= class_act(['gestion_produit', 'gestion_membre', 'gestion_salles', 'gestion_avis', 'gestion_commande', 'gestion_membre', 'statistique']); ?>" href=" #" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">Administration</a>


                        <ul class="dropdown-menu" aria-labelledby="dropdown03">
                            <li><a class="dropdown-item" href="<?= URL; ?>admin/gestion_produit.php">Gestion produit</a></li>
                            <li><a class="dropdown-item" href="<?= URL; ?>admin/gestion_membre.php">Gestion membre</a></li>
                            <li><a class="dropdown-item" href="<?= URL; ?>admin/gestion_salles.php">Gestion salles</a></li>
                            <li><a class="dropdown-item" href="<?= URL; ?>admin/gestion_avis.php">Gestion avis</a></li>
                            <li><a class="dropdown-item" href="<?= URL; ?>admin/gestion_commande.php">Gestion commande</a></li>
                            <li><a class="dropdown-item" href="<?= URL; ?>admin/statistique.php">Statistiques</a></li>

                        </ul>
                    </li>

                <?php } ?>

            </ul>


            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Rechercher" name="rechercher" aria-label="Rechercher">
                <button class="btn btn-outline-light" type="submit">Rechercher</button>
            </form>



        </div>
    </div>
</nav>


<main class="container">