<h1>Page d'inscription</h1>

<!-- Affichage du formulaire de connexion -->
<section class="form-container">
    <form method="post" action="validation_inscription">
        <div class="form-wrap">
            <!-- Affichage des champs du formulaire de login de l'utilisateur -->
            <div class="input-section-main">

                <!-- champ prénom -->
                <label for="first_name">Prénom <span>*</span></label>
                    <?php 
                    if(!empty($_SESSION['errors'])) {
                        if(in_array("INVALID_FIRSTNAME", $_SESSION['errors'])) {
                            ?> <div class="form-error">Le prénom est invalide. Il doit comporter au moins 3 caractères</div>
                        <?php }                                     
                    } ?>
                <input type="text" name="first_name" id="first_name" placeholder="Veuillez saisir votre prénom" required value="<?php if(isset($_SESSION['registrationUserData'])){echo ($_SESSION['registrationUserData']['firstName']);}?>">
    
                <!-- champ nom -->      
                <label for="last_name">Nom <span>*</span></label>
                    <?php 
                    if(!empty($_SESSION['errors'])) {
                        if(in_array("INVALID_LASTNAME", $_SESSION['errors'])) {
                            ?> <div class="form-error">Le nom est invalide. Il doit comporter au moins 2 caractères</div>
                        <?php } 
                    } ?>
                <input type="text" name="last_name" id="last_name" placeholder="Veuillez saisir votre nom" required value="<?php if(!empty($_SESSION['registrationUserData'])){echo ($_SESSION['registrationUserData']['lastName']);}?>">

                <!-- champ adresse -->
                <label for="address">Adresse <span>*</span></label>
                    <!-- Affichage des erreurs du champ adresse -->
                    <?php 
                    if(!empty($_SESSION['errors'])) {
                        if(in_array("INVALID_ADDRESS", $_SESSION['errors'])) {
                            ?> <div class="form-error">L'adresse est invalide</div>
                        <?php } 
                    } ?>
                <input type="text" name="address" id="address" placeholder="Veuillez saisir votre adresse" required value="<?php if(!empty($_SESSION['registrationUserData'])){echo ($_SESSION['registrationUserData']['address']);}?>">

                <!-- champ code postal -->
                <label for="postal">Code postal <span>*</span></label>
                    <!-- Affichage des erreurs du champ code postal -->
                    <?php 
                    if(!empty($_SESSION['errors'])) {
                        if(in_array("INVALID_POSTAL", $_SESSION['errors'])) {
                            ?> <div class="form-error">Le code postal est invalide</div>
                        <?php }
                    } ?>
                <input type="text" name="postal" id="postal" placeholder="Veuillez saisir votre code postal" required value="<?php if(!empty($_SESSION['registrationUserData'])){echo ($_SESSION['registrationUserData']['postal']);}?>">

                <!-- champ ville -->
                <label for="city">Ville <span>*</span></label>
                    <!-- Affichage des erreurs du champ ville -->
                    <?php 
                    if(!empty($_SESSION['errors'])) {
                        if(in_array("INVALID_CITY", $_SESSION['errors'])) {
                            ?> <div class="form-error">La ville est invalide</div>
                        <?php }
                    } ?>
                <input type="text" name="city" id="city" placeholder="Veuillez saisir votre ville" required value="<?php if(!empty($_SESSION['registrationUserData'])){echo ($_SESSION['registrationUserData']['city']);}?>">

                <!-- champ pays -->
                <label for="country">Pays <span>*</span></label>
                    <!-- Affichage des erreurs du champ pays -->
                    <?php 
                    if(!empty($_SESSION['errors'])) {
                        if(in_array("INVALID_COUNTRY", $_SESSION['errors'])) {
                            ?> <div class="form-error">Le pays est invalide</div>
                        <?php }
                    } ?>
                <input type="text" name="country" id="country" placeholder="Veuillez saisir votre pays" required value="<?php if(!empty($_SESSION['registrationUserData'])){echo ($_SESSION['registrationUserData']['country']);}?>">
            </div>

            <div class="input-section-aside">
                <!-- Champs photo de profil -->
                <label for="cover_image">Photo de profil</label>
                <input type="text" name="cover_image" id="cover_image" placeholder="Votre photo de profil">

                <!-- champ téléphone -->
                <label for="phone">Téléphone <span>*</span></label>
                    <!-- Affichage des erreurs du champ téléphone -->
                    <?php 
                    if(!empty($_SESSION['errors'])) {
                        if(in_array("INVALID_PHONE", $_SESSION['errors'])) {
                            ?> <div class="form-error">Le téléphone est invalide</div>
                        <?php }
                    } ?>
                <input type="text" name="phone" id="phone" placeholder="Votre numéro de téléphone" value="<?php if(!empty($_SESSION['registrationUserData'])){echo ($_SESSION['registrationUserData']['phone']);}?>">
                   
                <!-- champ email -->
                <label for="email">Email <span>*</span></label>
                    <!-- Affichage des erreurs du champ email -->
                    <?php 
                    if(!empty($_SESSION['errors'])) {
                        if(in_array("INVALID_EMAIL", $_SESSION['errors'])) {
                            ?> <div class="form-error">L'email est invalide</div>
                        <?php } 
                    }
                    if(!empty($_SESSION['errorEmailUserExist'])) {
                        if(in_array("EMAIL_USER_EXIST", $_SESSION['errorEmailUserExist'])) {
                            ?> <div class="form-error">L'email n'est pas disponible</div>
                        <?php }
                    } ?>
                <input type="email" name="email" id="email" placeholder="Veuillez saisir votre email" required value="<?php if(!empty($_SESSION['registrationUserData'])){echo ($_SESSION['registrationUserData']['email']);}?>">


                <!-- champ mot de passe -->
                <label for="password">Mot de passe <span>*</span></label>
                <input type="password" name="password" id="password" placeholder="Veuillez saisir un mot de passe" required>

                <label for="password_confirm">Confirmation du mot de passe <span>*</span></label>
                    <!-- Affichage des erreurs du champ mot de passe de confirmation -->
                    <?php 
                    if(!empty($_SESSION['errorConfirmPassword'])) {
                        if(in_array("INVALID_CONFIRM_PASSWORD", $_SESSION['errorConfirmPassword'])) {
                            ?> <div class="form-error">Les deux mots de passe ne sont pas identiques !</div>
                        <?php } 
                    } ?>
                <input type="password" name="password_confirm" id="password_confirm" placeholder="Veuillez saisir à nouveau votre mot de passe" required>

               <!-- champ RGPD -->
               <div class="checkbox-wrap">
                    <input type="checkbox" name="is_rgpd" class="chekbox-item" required>
                    <label for="is_rgpd">Accord RGPD <span>*</span></label><br>
                    <!-- Affichage des erreurs du champ RGPD -->
                    <?php 
                    if(!empty($_SESSION['errors'])) {
                        if(in_array("INVALID_RGPD", $_SESSION['errors'])) {
                            ?> <div class="form-error">La case RGPD doit être cochée</div>
                        <?php }
                    } ?>
                </div>
                <div class="checkbox-wrap">
                    <small>En cochant cette case, j'accepte que mes données personnelles soient utilisées pour les besoins du site.</small>
                </div>
                        
                <div class="form-link">
                    <!-- Lien vers la page des mentions légales -->
                    <p><a href="#"><i class="fas fa-question"></i> Notre politique de protection des données...</a></p>
                </div>
                    
            </div>
            <div class="input-section-footer">
                <!-- Bouton de validation du formulaire -->
                <div class="form-button">
                    <button class="button button-info" type="submit">S'inscrire</button>
                </div>

                <?php
                    // Suppression des variables de session
                    unset($_SESSION['errors']); 
                    unset($_SESSION['errorConfirmPassword']);
                    unset($_SESSION['registrationUserData']);
                    unset($_SESSION['errorEmailUserExist']);
                ?>
            </div>
        </div>
    </form>
</section>
            