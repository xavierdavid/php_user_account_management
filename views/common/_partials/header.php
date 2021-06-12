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
            <!-- Si un utilisateur est authentifié -->
           
                <a href="<?= URL ?>#" class="dropdown">
                    <!-- Si une image référencée en base de données et stockée dans le fichier 'uploads' existe pour cet utilisateur -->
                  
                        <!-- On affiche cette image -->
                        <div class="item-user-image">
                           <!-- <img src="#" alt="Avatar de l'utilisateur"> --> 
                        </div>
                   
                    <!-- Sinon on affiche par défaut une icône avatar -->
                        <i class="far fa-user"></i>
                    
                    <!-- On affiche le prénom de l'utilisateur -->
                    <span> </span>
                </a>
              
                <!-- Elément dropdown -->
                <div class="dropdown-box">
                    <!-- Dropdown menu -->
                    <div class="dropdown-content">
                        <!-- Lien vers la page du compte utilisateur -->
                        <a href="#">Mon compte</a>
                        <!-- Lien vers la page de modification du mot de passe -->
                        <a href="#">Editer mon profil</a>
                        <!-- Lien vers la page des publications -->
                        <a href="#">Blog de l'association</a>
                        <!-- Lien vers la page de modification du mot de passe -->
                        <a href="#">Modifier mon mot de passe</a>
                        <!-- Si l'utilisateur authentifié est administrateur -->
                        
                            <!-- On affiche le lien vers l'espace d'administration sécurisé -->
                            <div class="admin-item">
                                <a href="#"><i class="fas fa-cog admin-icon"></i> Accéder au backoffice</a>
                            </div>
                        
                        <hr>
                        <!-- Lien de déconnexion -->
                        <div class="logout-item">
                            <a href="#"><i class="fas fa-power-off logout-icon"></i> Déconnection</a>
                        </div>
                    </div>
                </div>
           
                <!-- Liens de connexion -->
                <a href="#"><i class="fas fa-sign-in-alt login-icon"></i><span> Connexion</span></a>
                <!-- Liens d'inscription -->
                <a href="#"><span> Inscription</span></a>
        </div>
        
        <!-- Icône de menu hamburger responsive -->
        <div class="btn-toggle-container" role="button">
            <img src="<?= URL ?>public/img/hamburger.svg" alt="Icône du menu hamburger" class="hamburger-img">
        </div>
    </nav>
</header>
