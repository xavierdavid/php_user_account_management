<?php  

namespace App\Services;

use App\Services\Utility;
use App\Models\Entities\User;

class Mail
{
    /**
     * Permet d'envoyer une url d'activation de compte par mail à l'utilisateur en cours
     *
     * @param User $user
     * @return void
     */
    public static function userActivationMail($user)
    {
        // On génère une url d'activation à partir du slug de l'utilisateur et du token d'activation
        $userActivationUrl = URL."accountActivation/".$user->getSlug()."/".$user->getActivationToken();
        // On définit le sujet du mail
        $subject = "Activation du compte de ". $user->getFirstName() . $user->getLastName();
        // On génère le message
        $message = "Bienvenue sur notre site. Nous vous remercions de bien vouloir cliquer sur le lien ci-après afin de pouvoir activer votre compte : ".$userActivationUrl;
        // On crée un entête
        $header = "From: siteassociation@gmail.com";
        // On récupère l'email de l'utilisateur en cours
        $userMail = $user->getEmail();
        // On envoie le mail et on teste si l'envoi a abouti
        if(mail($userMail, $subject, $message, $header)) {
            // On génère un message d'alerte de succès à l'utilisateur
            Utility::addAlertMessage("Mail envoyé !", Utility::SUCCESS_MESSAGE);
        } else {
            // On génère un message d'alerte d'échec à l'utilisateur
            Utility::addAlertMessage("Echec de l'envoi du mail !", Utility::DANGER_MESSAGE);
        }
    }
}