<h1>Réinitialisation de votre mot de passe</h1>

<!-- Affichage du formulaire de réinitialisation du mot de passe -->
<section class="form-container form-container-primary">
    <div class="login-icon">
        <img src="<?= URL ?>/public/img/forgotten_password.png" alt="icône de mot de passe oublié">
    </div>
    <form method="post" action="reinitialisation_mot_de_passe">
        <div class="form-wrap form-wrap-primary">
            <!-- Affichage des champs du formulaire de réinitialisation -->
            <div class="input-section-primary">
                <!-- champ email -->
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Veuillez saisir votre adresse email" required>
               
                <!-- Lien vers le formulaire d'inscription -->
                <div class="form-link">
                    <p><a href="<?= URL ?>/inscription"><i class="fas fa-hand-point-right"></i> Pas encore inscrit ?</a></p>
                </div>
                
                <div class="input-section-footer">
                    <!-- Bouton de validation du formulaire -->
                    <div class="form-button">
                        <button class="button button-info" type="submit">Réinitialiser</button>
                    </div>
                </div> 
            </div> 
        </div>
    </form>
</section>