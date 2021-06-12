<?php 

//Namespace 'Home' des contrôleurs
namespace App\Controllers\Home;

// Utilisation de la classe Maincontroller associée au namespace des Controllers
use App\Controllers\MainController;

class HomeController extends MainController
{
    /**
     * Contrôle le paramétrage et l'affichage de la page d'accueil 
     *
     * @return void
     */
    public function home()
    {
        // Définition d'un tableau associatif regroupant les données d'affichage de la page d'accueil du site
        $data_page = [
            "page_description" => "Description de la page d'accueil",
            "page_title" => "Titre de la page d'accueil",
            "view" => "views/home/home.view.php",
            "template" => "views/common/template.php"
        ];
        // Affichage de la page à l'aide de la méthode generatePage à laquelle on envoie le tableau de données
        $this->generatePage($data_page);
    }

    /**
     * Rédéfinition de méthode pour appeler la méthode parente 'errorPage' du MainController
     *
     * @param [type] $errorMessage
     * @return void
     */
    public function errorPage($errorMessage)
    {
        parent::errorPage($errorMessage);
    }
}