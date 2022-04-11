<!-- Création du header du template de base -->
<header>
    <nav>
        <!-- Liste des items du menu de navigation -->
        <ul class="list-nav">
            <li class="item-nav">
                <a href="<?= URL ?>accueil"><i class="fas fa-home"></i> Accueil</a>
            </li>
            <li class="item-nav">
                <a href="<?= URL ?>#">Qui sommes-nous ?</a>
            </li>
            <li class="item-nav">
                <a href="<?= URL ?>#">Contact</a>
            </li>
        </ul>

        <!-- Items de login et logout -->
        <div class="item-login-logout">
            
            <!-- Utilisteur authentifié -->
            <div class="authenticated-user-wrap">
                <!-- Si un utilisateur est authentifié -->
                <?php if(!empty($_SESSION['user'])) { ?>
                    <!-- On affiche le lien de la dropdown de navigation -->
                    <!-- Si une image référencée en base de données, stockée dans le fichier 'uploads' et dans la session existe pour cet utilisateur -->
                    <?php if(!empty($_SESSION['user']['coverImage'])) {?>
                        <!-- On affiche cette image -->
                        <div class="item-user-image">
                            <img src="<?= URL?>public/img/users/<?php echo($_SESSION['user']['coverImage']) ?>" alt="Avatar de l'utilisateur">
                        </div>
                    <?php } else { ?>
                        <!-- Sinon on affiche par défaut une icône avatar -->
                        <i class="far fa-user"></i>
                    <?php } ?>
                    
                    <div class="dropdown-link">
                        <a href="<?= URL ?>#" class="dropdown">
                            <!-- On affiche le prénom de l'utilisateur -->
                           <span> Hello <?= $_SESSION['user']['firstName']?> </span>
                        </a>    
                    </div>

                    <!-- Elément dropdown -->
                    <div class="dropdown-box">
                        <!-- Dropdown menu -->
                        <div class="dropdown-content">
                            <!-- Lien vers la page du compte utilisateur -->
                            <a href="<?=URL?>compte/profil">Mon compte</a>
                            <!-- Lien vers la page de modification des informations de profil -->
                            <a href="<?=URL?>compte/modification_profil">Modifier mes informations</a>
                            <!-- Lien vers la page des publications -->
                            <a href="#">Blog de l'association</a>
                            <!-- Lien vers la page de modification du mot de passe -->
                            <a href="<?=URL?>compte/modification_mot_de_passe">Modifier mon mot de passe</a>
                            <!-- Si l'utilisateur authentifié est administrateur -->
                            
                            <!-- On affiche le lien vers l'espace d'administration sécurisé -->
                            <div class="admin-item">
                                <a href="#"><i class="fas fa-cog admin-icon"></i> Accéder au backoffice</a>
                            </div>
                            
                            <hr>
                            <!-- Lien de déconnexion -->
                            <div class="logout-item">
                                <a href="<?= URL ?>compte/deconnexion"><i class="fas fa-power-off logout-icon"></i> Déconnection</a>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div> 
                
            <!-- Utilisateur anonyme -->  
            <div class="anonymous-user-wrap">
                <!-- Si aucun utilisateur n'est authentifié-->
                <?php if(empty($_SESSION['user'])) {?>
                    <a href="<?= URL ?>connexion"><i class="fas fa-sign-in-alt "></i><span> Connexion</span></a>
                    <!-- Liens d'inscription -->
                    <a href="<?= URL ?>inscription"><span> Inscription</span></a>
                <?php } ?>  
            </div>
        </div>
        
        <!-- Icône de menu hamburger responsive -->
        <div class="btn-toggle-container" role="button">
            <img src="<?= URL ?>public/img/hamburger.svg" alt="Icône du menu hamburger" class="hamburger-img">
        </div>
        
    </nav>
</header>
    