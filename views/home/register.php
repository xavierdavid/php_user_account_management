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
                <input type="text" name="first_name" id="first_name" placeholder="Veuillez saisir votre prénom" required>

                <!-- champ nom -->      
                <label for="last_name">Nom <span>*</span></label>
                    <?php 
                    if(!empty($_SESSION['errors'])) {
                        if(in_array("INVALID_LASTNAME", $_SESSION['errors'])) {
                            ?> <div class="form-error">Le nom est invalide. Il doit comporter au moins 2 caractères</div>
                        <?php } 
                    } ?>
                <input type="text" name="last_name" id="last_name" placeholder="Veuillez saisir votre nom" required>

                <!-- champ adresse -->
                <label for="address">Adresse <span>*</span></label>
                    <!-- Affichage des erreurs du champ adresse -->
                    <?php 
                    if(!empty($_SESSION['errors'])) {
                        if(in_array("INVALID_ADDRESS", $_SESSION['errors'])) {
                            ?> <div class="form-error">L'adresse est invalide</div>
                        <?php } 
                    } ?>
                <input type="text" name="address" id="address" placeholder="Veuillez saisir votre adresse" required>

                <!-- champ code postal -->
                <label for="postal">Code postal <span>*</span></label>
                    <!-- Affichage des erreurs du champ code postal -->
                    <?php 
                    if(!empty($_SESSION['errors'])) {
                        if(in_array("INVALID_POSTAL", $_SESSION['errors'])) {
                            ?> <div class="form-error">Le code postal est invalide</div>
                        <?php }
                    } ?>
                <input type="text" name="postal" id="postal" placeholder="Veuillez saisir votre code postal" required>

                <!-- champ ville -->
                <label for="city">Ville <span>*</span></label>
                    <!-- Affichage des erreurs du champ ville -->
                    <?php 
                    if(!empty($_SESSION['errors'])) {
                        if(in_array("INVALID_CITY", $_SESSION['errors'])) {
                            ?> <div class="form-error">La ville est invalide</div>
                        <?php }
                    } ?>
                <input type="text" name="city" id="city" placeholder="Veuillez saisir votre ville" required>

                <!-- champ pays -->
                <label for="country">Pays <span>*</span></label>
                    <!-- Affichage des erreurs du champ pays -->
                    <?php 
                    if(!empty($_SESSION['errors'])) {
                        if(in_array("INVALID_COUNTRY", $_SESSION['errors'])) {
                            ?> <div class="form-error">Le pays est invalide</div>
                        <?php }
                    } ?>
                <input type="text" name="country" id="country" placeholder="Veuillez saisir votre pays" required>
            </div>

            <div class="input-section-aside">
                <!-- Champs photo de profil -->
                <label for="cover_image">Photo de profil</label>
                <input type="text" name="cover_image" id="cover_image" placeholder="Votre photo de profil">

                <!-- champ téléphone -->
                <label for="phone">Téléphone</label>
                    <!-- Affichage des erreurs du champ téléphone -->
                    <?php 
                    if(!empty($_SESSION['errors'])) {
                        if(in_array("INVALID_PHONE", $_SESSION['errors'])) {
                            ?> <div class="form-error">Le téléphone est invalide</div>
                        <?php }
                    } ?>
                <input type="text" name="phone" id="phone" placeholder="Votre numéro de téléphone">
                   
                <!-- champ email -->
                <label for="email">Email <span>*</span></label>
                    <!-- Affichage des erreurs du champ email -->
                    <?php 
                    if(!empty($_SESSION['errors'])) {
                        if(in_array("INVALID_EMAIL", $_SESSION['errors'])) {
                            ?> <div class="form-error">L'email est invalide</div>
                        <?php } 
                    } ?>
                <input type="email" name="email" id="email" placeholder="Veuillez saisir votre email" required>


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

               <!--  <div class="checkbox-wrap">
                    <input type="checkbox" name="_remember_me" class="chekbox-item">
                    <label>Se souvenir de moi</label>
                </div> -->
                        
                <div class="form-link">
                    <!-- Lien vers la route du formulaire de mot de passe oublié -->
                    <!-- <p><a href="#"><i class="fas fa-question"></i> Mot de passe oublié...</a></p> -->
                    <!-- Lien vers la route du formulaire d'inscription -->
                    <!-- <p><a href="#"><i class="far fa-arrow-alt-circle-right"></i> S'inscrire</a></p> -->
                </div>
                    
                <!-- Bouton de validation du formulaire -->
                <div class="form-button">
                    <button class="button button-info" type="submit">S'inscrire</button>
                </div>
            </div>

            <?php
                // Suppression des erreurs de la session
                unset($_SESSION['errors']); 
                unset($_SESSION['errorConfirmPassword']);
            ?>
        </div>
    </form>
</section>
            