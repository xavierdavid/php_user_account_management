<h1>Modifier mes informations</h1>
<!-- Affichage du formulaire d'inscription -->
<section class="form-container">
    <div class="login-icon">
        <img src="<?= URL ?>/public/img/edit.png" alt="icône de profil">
    </div>
    <div class="form-container-secondary">
        <form method="post" action="<?= URL ?>compte/validation_modification_profil" enctype="multipart/form-data">
            <div class="form-wrap form-wrap-secondary">
                <!-- Affichage des champs du formulaire d'inscription de l'utilisateur -->
                <div class="input-section-secondary">

                    <!-- champ prénom -->
                    <label for="firstName">Prénom <span>*</span></label>
                        <?php 
                        if(!empty($_SESSION['errors'])) {
                            if(in_array("INVALID_FIRSTNAME", $_SESSION['errors'])) {
                                ?> <div class="form-error">Le prénom est invalide. Il doit comporter au moins 3 caractères</div>
                            <?php }                                     
                        } ?>
                    <input type="text" name="firstName" id="firstName" placeholder="Veuillez saisir votre prénom" required value="<?php echo ($user_data->getFirstName());?>">
        
                    <!-- champ nom -->      
                    <label for="lastName">Nom <span>*</span></label>
                        <?php 
                        if(!empty($_SESSION['errors'])) {
                            if(in_array("INVALID_LASTNAME", $_SESSION['errors'])) {
                                ?> <div class="form-error">Le nom est invalide. Il doit comporter au moins 2 caractères</div>
                            <?php } 
                        } ?>
                    <input type="text" name="lastName" id="lastName" placeholder="Veuillez saisir votre nom" required value="<?php echo ($user_data->getLastName());?>">

                    <!-- Champ photo de profil -->
                    <label for="coverImage">Photo de profil</label>
                    <input type="file" class="input-file" name="coverImage" id="coverImage" placeholder="Ajouter une photo de profil">

                    <!-- champ téléphone -->
                    <label for="phone">Téléphone <span>*</span></label>
                        <!-- Affichage des erreurs du champ téléphone -->
                        <?php 
                        if(!empty($_SESSION['errors'])) {
                            if(in_array("INVALID_PHONE", $_SESSION['errors'])) {
                                ?> <div class="form-error">Le téléphone est invalide</div>
                            <?php }
                        } ?>
                    <input type="text" name="phone" id="phone" placeholder="Votre numéro de téléphone" required value="<?php echo ($user_data->getPhone());?>">
                </div>

                <div class="input-section-secondary">
                    <!-- champ adresse -->
                    <label for="address">Adresse <span>*</span></label>
                        <!-- Affichage des erreurs du champ adresse -->
                        <?php 
                        if(!empty($_SESSION['errors'])) {
                            if(in_array("INVALID_ADDRESS", $_SESSION['errors'])) {
                                ?> <div class="form-error">L'adresse est invalide</div>
                            <?php } 
                        } ?>
                    <input type="text" name="address" id="address" placeholder="Veuillez saisir votre adresse" required value="<?php echo ($user_data->getAddress());?>">

                    <!-- champ code postal -->
                    <label for="postal">Code postal <span>*</span></label>
                        <!-- Affichage des erreurs du champ code postal -->
                        <?php 
                        if(!empty($_SESSION['errors'])) {
                            if(in_array("INVALID_POSTAL", $_SESSION['errors'])) {
                                ?> <div class="form-error">Le code postal est invalide</div>
                            <?php }
                        } ?>
                    <input type="text" name="postal" id="postal" placeholder="Veuillez saisir votre code postal" required value="<?php echo ($user_data->getPostal());?>">

                    <!-- champ ville -->
                    <label for="city">Ville <span>*</span></label>
                        <!-- Affichage des erreurs du champ ville -->
                        <?php 
                        if(!empty($_SESSION['errors'])) {
                            if(in_array("INVALID_CITY", $_SESSION['errors'])) {
                                ?> <div class="form-error">La ville est invalide</div>
                            <?php }
                        } ?>
                    <input type="text" name="city" id="city" placeholder="Veuillez saisir votre ville" required value="<?php echo ($user_data->getCity());?>">

                    <!-- champ pays -->
                    <label for="country">Pays <span>*</span></label>
                        <!-- Affichage des erreurs du champ pays -->
                        <?php 
                        if(!empty($_SESSION['errors'])) {
                            if(in_array("INVALID_COUNTRY", $_SESSION['errors'])) {
                                ?> <div class="form-error">Le pays est invalide</div>
                            <?php }
                        } ?>
                    <input type="text" name="country" id="country" placeholder="Veuillez saisir votre pays" required value="<?php echo ($user_data->getCountry());?>">      
               
                    <!-- Lien de retour vers la page de profil -->
                    <div class="form-link">
                        <p><a href="<?= URL ?>/compte/profil"><i class="fas fa-undo"></i> Retour vers la page de profil</a></p>
                    </div>
                </div>

                <div class="input-section-footer">
                    <!-- Bouton de validation du formulaire -->
                    <div class="form-button">
                        <button class="button button-info" type="submit">Modifier</button>
                    </div>

                    <?php
                        // Suppression des variables de session
                        unset($_SESSION['errors']); 
                        unset($_SESSION['editUserData']);
                    ?>
                </div>
            </div>
        </form>
    </div>
</section>