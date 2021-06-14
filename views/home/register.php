<h1>Page d'inscription</h1>

<!-- Affichage du formulaire de connexion -->
<section class="form-container">
    <form method="post" action="validation_inscription">
        <div class="form-wrap">
            <!-- Affichage des champs du formulaire de login de l'utilisateur -->
            <div class="input-section-main">
                <label for="first_name">Prénom</label>
                <input type="text" name="first_name" id="first_name" placeholder="Veuillez saisir votre prénom" required>
                        
                <label for="last_name">Nom</label>
                <input type="text" name="last_name" id="last_name" placeholder="Veuillez saisir votre nom" required>

                <label for="address">Adresse</label>
                <input type="text" name="address" id="address" placeholder="Veuillez saisir votre adresse" required>

                <label for="postal">Code postal</label>
                <input type="text" name="postal" id="postal" placeholder="Veuillez saisir votre code postal" required>

                <label for="city">Ville</label>
                <input type="text" name="city" id="city" placeholder="Veuillez saisir votre ville" required>

                <label for="country">Pays</label>
                <input type="text" name="country" id="country" placeholder="Veuillez saisir votre pays" required>
            </div>

            <div class="input-section-aside">
                <label for="cover_image">Photo de profil</label>
                <input type="text" name="cover_image" id="cover_image" placeholder="Votre photo de profil">

                <label for="phone">Téléphone</label>
                <input type="text" name="phone" id="phone" placeholder="Votre numéro de téléphone">
                        
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Veuillez saisir votre email" required>

                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Veuillez saisir un mot de passe" required>

                <label for="password_confirm">Confirmation du mot de passe</label>
                <input type="password" name="password_confirm" id="password_confirm" placeholder="Veuillez saisir à nouveau votre mot de passe" required>

                <div class="checkbox-wrap">
                    <input type="checkbox" name="_remember_me" class="chekbox-item">
                    <label>Se souvenir de moi</label>
                </div>
                        
                <div class="form-link">
                    <!-- Lien vers la route du formulaire de mot de passe oublié -->
                    <p><a href="#"><i class="fas fa-question"></i> Mot de passe oublié...</a></p>
                    <!-- Lien vers la route du formulaire d'inscription -->
                    <p><a href="#"><i class="far fa-arrow-alt-circle-right"></i></i> S'inscrire</a></p>
                </div>
                    
                <!-- Bouton de validation du formulaire -->
                <div class="form-button">
                    <button class="button button-info" type="submit">S'inscrire</button>
                </div>
            </div>
        </form>
</section>
            