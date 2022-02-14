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
</section>