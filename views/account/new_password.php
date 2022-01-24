<h1>Modifier votre mot de passe</h1>

<!-- Affichage du formulaire de saisie du nouveau mot de passe -->
<section class="form-container form-container-primary">
    <div class="login-icon">
        <img src="<?= URL ?>/public/img/padlock.png" alt="icône de cadenas">
    </div>
    <form method="post" action="<?= URL ?>validation_nouveau_mot_de_passe">
        <div class="form-wrap form-wrap-primary">
            <!-- Affichage des champs du formulaire de saisie du nouveau mot de passe -->
            <div class="input-section-primary">
                <!-- Champ new_password -->
                <label for="new_password">Votre nouveau mot de passe</label>
                <input type="password" name="new_password" id="new_password" placeholder="Veuillez saisir votre nouveau mot de passe" required>

                <!-- Champ new_password_confirm -->
                <label for="new_password_confirm">Confirmation du mot de passe</label>
                <input type="password" name="new_password_confirm" id="new_password_confirm" placeholder="Veuillez confirmer votre nouveau mot de passe" required>

                <!-- Champ caché - Slug de l'utilisateur -->
                <input type="hidden" id="userSlug" name="userSlug" value=<?php echo $userSlug ?>>

                <!-- Champ caché - ResetToken de l'utilisateur -->
                <input type="hidden" id="resetToken" name="resetToken" value=<?php echo $resetToken ?>>
               
                <!-- Lien vers le formulaire d'inscription -->
                <div class="form-link">
                    <p><a href="<?= URL ?>/inscription"><i class="fas fa-hand-point-right"></i> Pas encore inscrit ?</a></p>
                </div>
                
                <div class="input-section-footer">
                    <!-- Bouton de validation du formulaire -->
                    <div class="form-button">
                        <button class="button button-info" type="submit">Modifier</button>
                    </div>
                </div> 
            </div> 
        </div>
    </form>
</section>