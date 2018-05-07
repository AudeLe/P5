<!-- Header with a nav bar displayed if the visitor is connected -->
<ul class="navbar-nav mr-auto">
    <!-- Log out button -->
    <li class="nav-item">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#logOut">
            <i class="fas fa-sign-out-alt">Déconnexion</i>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="logOut" tabindex="-1" role="dialog" aria-labelledby="logOutLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="logOutLabel">Déconnexion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p>Êtes-vous sûr(e) de vouloir vous déconnecter ?</p>
                    </div>

                    <div class="modal-footer">
                        <a href="../public/index.php?action=logOut">Déconnexion</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    </div>

                </div>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <?php
        if($_SESSION['status'] == 'admin'){
            ?>
            <a class="nav-link" href="../public/index.php?action="><!--Action à déterminer--><i class="fas fa-user-circle"></i><?$_SESSION['login'] ?></a>
            <?php
        } else {
            ?>
            <a class="nav-link" href="../public/index.php?action="><!-- Action à déterminer --><i class="fas fa-user-circle"><?= $_SESSION['login'] ?></i></a>
            <?php
        }
        ?>
    </li>

    <li class="nav-item">
        <a class="nav-item" href="../public/index.php"><i class="fas fa-home"></i>Accueil du site</a>
    </li>
</ul>