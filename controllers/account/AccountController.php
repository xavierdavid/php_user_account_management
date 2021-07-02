<?php  

namespace App\Controllers\Account;

use App\Services\Utility;
use App\Controllers\MainController;
use App\Models\Managers\UserManager;


class AccountController extends MainController
{
    private $userManager;

    /**
     * Initialisation d'une instance de la classe UserManager
     */
    public function __construct() 
    {
        $this->userManager = new UserManager;
    }

   /**
    * Permet d'activer le compte d'un utilisateur
    *
    * @param [type] $userSlug
    * @param [type] $activationToken
    * @return void
    */
    public function account_activation($userSlug, $activationToken)
    {
        // On vérifie si le slug de l'utilisateur et le token d'activation récupérés via l'url sont identiques à ceux stockés dans la base de données
        if($this->userManager->activate_account($userSlug, $activationToken)){
            // Si tel est le cas, on indique à l'utilisateur que son compte est activé
            Utility::addAlertMessage("Votre compte a été activé !", Utility::SUCCESS_MESSAGE);
            // On redirige l'utilisateur vers la page de login
            Utility::redirect(URL."accueil");
        } else {
            // Si tel est le cas, on affiche un message d'échec
            Utility::addAlertMessage("Votre compte n'a pas été activé. Veuillez cliquer sur le lien d'activation envoyé par mail !", Utility::DANGER_MESSAGE);
            // On redirige l'utilisateur vers la page de login
            Utility::redirect(URL."login");
        }
    }
}