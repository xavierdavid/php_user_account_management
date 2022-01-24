<h1>Connexion</h1>

<!-- Affichage du formulaire de connexion -->
<section class="form-container form-container-primary">
    <div class="login-icon">
        <img src="<?= URL ?>/public/img/profile-user.png" alt="icône de profil">
    </div>
    <form method="post" action="<?= URL ?>validation_connexion">
        <div class="form-wrap form-wrap-primary">
            <!-- Affichage des champs du formulaire de connexion -->
            <div class="input-section-primary">
                <!-- champ email -->
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value = "<?php if(isset($_COOKIE['email'])){echo $_COOKIE['email'];}?>" placeholder="Veuillez saisir votre email" required>
                <!-- champ mot de passe -->
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" value = "<?php if(isset($_COOKIE['password'])){echo $_COOKIE['password'];}?>" placeholder="Veuillez saisir votre mot de passe" required>
                <!-- Case à cocher "Se souvenir de moi" -->
                <div class="checkbox-wrap">
                    <input type="checkbox" name="rememberMe" class="chekbox-item" id="rememberMe">
                    <label for="rememberMe">Se souvenir de moi</label>
                </div>
                <!-- Lien vers la gestion du mot de passe oublié -->
                <div class="form-link">
                    <p><a href="<?= URL ?>mot_de_passe_oublie"><i class="fas fa-question"></i> Mot de passe oublié...</a></p>
                </div>
                <!-- Lien vers le formulaire d'inscription -->
                <div class="form-link">
                    <p><a href="<?= URL ?>/inscription"><i class="fas fa-hand-point-right"></i> Pas encore inscrit ?</a></p>
                </div>
                <div class="input-section-footer">
                    <!-- Bouton de validation du formulaire -->
                    <div class="form-button">
                        <button class="button button-info" type="submit">S'identifier</button>
                    </div>
                </div> 
            </div> 
        </div>
    </form>
</section>