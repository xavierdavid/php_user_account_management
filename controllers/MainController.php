<?php 

// Namespace des Controllers
namespace App\Controllers;

// Utilisation de la classe MainManager associée au namespace des Models
use App\Models\MainManager;

/**
 * Contrôle la génération et l'affichage des différentes pages du site
 */
abstract class MainController
{
    private $mainManager;

    /**
     * Constructeur de classe
     */
    public function __construct()
    {
        // Création d'une instance du MainManager utilisable dans toute la classe
        $this->mainManager = new MainManager;
    }

    /**
     * Permet de construire une page à partir des données et du template de base
     *
     * @param [type] $data
     * @return void
     */
    protected function generatePage($data)
    {
        // Import du tableau de données de page et transformation en variables
        extract($data);
        // Mise en place d'un buffer de temporisation pour stocker le contenu de la vue
        ob_start();
        // Chargement de la vue spécifique
        require_once($view);
        $page_content = ob_get_clean();
        // Chargement du template de base du site
        require_once($template); 
    }

    /**
     * Contrôle le paramétrage et l'affichage de la page d'erreur
     *
     * @return void
     */
    protected function errorPage($errorMessage)
    {
        // Définition d'un tableau associatif regroupant les données d'affichage de la page d'erreur du site
        $data_page = [
            "page_description" => "Page permettant de gérer les erreurs",
            "page_title" => "Page d'erreur",
            "errorMessage" => $errorMessage,
            "view" => "./views/error.view.php",
            "template" => "views/common/template.php"
        ];
        // Affichage de la page à l'aide de la méthode generatePage à laquelle on envoie le tableau de données
        $this->generatePage($data_page);
    }
}



