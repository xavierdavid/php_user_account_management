<h1>Connexion</h1>

<!-- Affichage du formulaire de connexion -->
<section class="form-container form-container-primary">
    <div class="login-icon">
        <img src="<?= URL ?>/public/img/profile-user.png" alt="icône de profil">
    </div>
    <form method="post" action="validation_connexion">
        <div class="form-wrap form-wrap-primary">
            <!-- Afæfichage des champs du formulaire de connexion -->
            <div class="input-section-primary">
                <!-- champ email -->
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Veuillez saisir votre email" required>
                <!-- champ mot de passe -->
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Veuillez saisir votre mot de passe" required>
                <div class="form-link">
                    <!-- Lien vers la gestion du mot de passe oublié -->
                    <p><a href="#"><i class="fas fa-question"></i> Mot de passe oublié...</a></p>
                </div>
                <div class="form-link">
                    <!-- Lien vers le formulaire d'inscription -->
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