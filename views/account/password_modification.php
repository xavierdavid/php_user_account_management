<h1>Modification du mot de passe</h1>

<!-- Affichage du formulaire de modification du mot de passe -->
<section class="form-container form-container-primary">
    <div class="login-icon">
        <img src="<?= URL ?>/public/img/modification_password.png" alt="icône de modification du mot de passe">
    </div>
    <form method="post" action="<?= URL ?>compte/validation_modification_mot_de_passe">
        <div class="form-wrap form-wrap-primary">
            <!-- Affichage des champs du formulaire de modification -->
            <div class="input-section-primary">
                <!-- champ mot de passe actuel -->
                <label for="oldPassword">Mot de passe actuel</label>
                <input type="password" name="oldPassword" id="oldPassword" placeholder="Veuillez saisir votre mot de passe" required>
                
                <!-- champ nouveau mot de passe -->
                <label for="newPassword">Nouveau mot de passe</label>
                <input type="password" name="newPassword" id="newPassword" placeholder="Veuillez saisir votre nouveau mot de passe" required>
                
                <!-- champ confirmation nouveau mot de passe -->
                <label for="confirmNewPassword">Confirmation du nouveau mot de passe</label>
                <input type="password" name="confirmNewPassword" id="confirmNewPassword" placeholder="Veuillez confirmer votre nouveau mot de passe" required>

                <!-- Message d'erreur -->
                <div class="form-error display-none" id="errorMessage">Les deux mots de passe ne sont pas identiques</div>
               
                <!-- Lien de retour vers la page de profil -->
                <div class="form-link">
                    <p><a href="<?= URL ?>/compte/profil"><i class="fas fa-undo"></i> Retour vers la page de profil</a></p>
                </div>
                
                <div class="input-section-footer">
                    <!-- Bouton de validation du formulaire -->
                    <div class="form-button">
                        <button class="button button-info" type="submit" id="validationBtn" disabled>Modifier</button>
                    </div>
                </div> 
            </div> 
        </div>
    </form>
</section>