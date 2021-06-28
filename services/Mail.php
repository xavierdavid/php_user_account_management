<?php  

namespace App\Services;

use App\Services\Utility;
use App\Models\Entities\User;
use App\Vendor\PHPMailer\Exception;
use App\Vendor\PHPMailer\PHPMailer;
use App\Vendor\PHPMailer\SMTP;

class Mail
{
    
    /**
     * Permet d'envoyer une url d'activation de compte par mail à l'utilisateur en cours
     *
     * @param [type] $user
     * @return void
     */
    public static function userActivationMail($user)
    {
        // On crée une instance de PHPMailer
        $mail = new PHPMailer(true);

        // On génère une url d'activation à partir du slug de l'utilisateur et du token d'activation
        $userActivationUrl = URL."activation_compte/".$user->getSlug()."/".$user->getActivationToken();
        // On définit le sujet du mail
        $subject = "Activation du compte de ". $user->getFirstName() . " " . $user->getLastName();
        // On génère le message
        $message = "<h1>Bienvenue sur notre site !</h1><br><p>Nous vous remercions de bien vouloir cliquer sur le lien ci-après afin de pouvoir activer votre compte : "."<strong>".$userActivationUrl."</strong></p>";
        // On récupère l'email de l'utilisateur en cours
        $userMail = $user->getEmail();
        
        try {
            // On configure les informations de debug
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            // On configure le protocole SMTP de transfert de mail
            $mail->isSMTP();
            $mail->Host = "localhost";
            $mail->Port = 1025;
            //On paramètre le charset
            $mail->CharSet = "utf-8";
            // On indique le destinataires 
            $mail->addAddress($userMail);
            // On définit l'expéditeur 
            $mail->setFrom("no-reply@site-associatif.fr");
            // On précise le sujet du mail
            $mail->Subject = $subject;
            // On autorise un contenu au format HTML
            $mail->isHTML(true);
            // On ajoute le message 
            $mail->Body = $message;
            // On envoie le mail
            $mail->send();

            // On génère un message d'alerte de succès à l'utilisateur
            Utility::addAlertMessage("Mail envoyé !", Utility::SUCCESS_MESSAGE);

        } catch (Exception $e) {
            // On génère un message d'alerte d'échec à l'utilisateur
            Utility::addAlertMessage("Echec de l'envoi du mail ! : {$mail->ErrorInfo}", Utility::DANGER_MESSAGE);
        }
    }
}