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
                <li class="nav-item">
                    <a class="nav-link" href="<?= URL; ?>panier.php">Panier</a>
                </li>

                <?php if (!user_is_connected()) { ?>


                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL; ?>connexion.php">Connexion</a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL; ?>inscription.php">Inscription</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL; ?>profil.php">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="connexion.php?action=deconnexion">Déconnexion</a>
                    </li>
                <?php } ?>


                <?php
                //? menu administration
                if (user_is_admin()) {
                ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">Administration</a>
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
                <input class="form-control me-2" type="search" placeholder="Rechercher" aria-label="Rechercher">
                <button class="btn btn-outline-light" type="submit">Rechercher</button>
            </form>


            <!-- <div class="text-center">
                <a href="" class="btn btn-default btn-rounded mb-4" data-toggle="modal" data-target="#modalLoginForm">Se connecter&nbsp;<i class="fa-solid fa-user"></i></a>
            </div>
         -->
            <!-- Button trigger modal -->
            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Se connecter <i class="fa-solid fa-user"></i>
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ...
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>


<main class="container">