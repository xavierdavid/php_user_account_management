<h1>Bonjour <?PHP echo ($user_data->getFirstName()." ".$user_data->getLastName()); ?></h1>

<section class="profile-wrapper">
    <div class="user-profile">
        <div class="smiling-icon">
            <img src="<?= URL ?>/public/img/smiling-face.svg" alt="icône de profil" class="largeSizeImage">
        </div>
        <div class="user-identity">
            <h2>Bienvenue sur votre page de profil !</h2>
            <div class="user-info">
                <p>Nom : <strong><?PHP echo ($user_data->getLastName());?></strong></p>
                <p>Prénom : <strong><?PHP echo ($user_data->getFirstName());?></strong></p>
                <p>Adresse : <strong><?PHP echo ($user_data->getAddress());?></strong></p>
                <p>CP : <strong><?PHP echo ($user_data->getPostal());?></strong></p>
                <p>Commune : <strong><?PHP echo ($user_data->getCity());?></strong></p>
                <p>Téléphone : <strong><?PHP echo ($user_data->getPhone());?></strong></p>
            </div>
            <div id="emailProfile">
                <div class="email-profile-wrap">
                    <div>
                        <p class="login-email-title">Modifier votre email de connexion</p>
                        <p class="login-email"><strong><?PHP echo ($user_data->getEmail());?></strong></p>
                    </div>
                    <button class="button button-small button-info" id="editEmailButton"><i class="fas fa-pen"></i></button>
                </div>
                <!-- Lien vers la modification des informations du profil -->
                <div class="email-edit-form-return">
                    <i class=" fas fa-location-arrow"></i><a href="<?=URL?>compte/modification_profil"> Modifier mes informations</a>
                </div>
            </div> 
            <!-- Formulaire de modification de l'email -->
            <div id="emailEditForm" class="email-edit-form-wrap display-none">
                <form method="POST" action="<?=URL?>compte/validation_modification_email">
                    <!-- Affichage des champs du formulaire de modification de l'email -->
                    <div class="email-edit-form">
                        <label for="mail">Email :</label>
                        <input type="mail" name="email" value="<?PHP echo ($user_data->getEmail());?>">
                        <button class="button button-small button-success" id="checkEmailButton"><i class="fas fa-check"></i></button>
                    </div>
                    <!-- Lien de retour -->
                    <div class="email-edit-form-return">
                        <i class="fas fa-undo"></i><a href="<?=URL?>compte/profil"> Retour</a>
                    </div>
                </form>
            </div> 
           <!-- Bouton de suppression du compte -->
            <button class="button modal-button button-large button-danger modal-trigger">Supprimer mon compte</button>
        </div> 
    </div> 
    <!-- Fenêtre modale de suppression de compte -->
    <div class="modal-container">
        <div class="overlay modal-trigger"></div>
         <div class="modal">
            <button class="close-modal modal-trigger"><i class="fas fa-times"></i></button>
            <h2>Suppression de votre compte</h2>
            <p>Attention, vous vous apprêtez à supprimer votre compte. Merci de bien vouloir confirmer.</p>
            <a href="<?= URL ?>compte/validation-suppression-compte" class="button button-medium button-danger">Confirmer</a>
        </div>
    </div> 
</section>