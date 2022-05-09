<?php 

namespace App\Controllers\Admin;

use App\Controllers\MainController;


class AdminController extends MainController
{

    /**
     * Contrôle le paramétrage et l'affichage de la page d'accueil de l'interface d'administration
     *
     * @return void
     */
    public function adminHome()
    {
        // Définition d'un tableau associatif regroupant les données d'affichage de la page d'accueil de l'interface d'administration
        $data_page = [
            "page_description" => "Interface d'administration",
            "page_title" => "Page d'administration",
            "view" => "views/admin/admin_home.php",
            "template" => "views/common/template.php"
        ];
        // Affichage de la page à l'aide de la méthode generatePage à laquelle on envoie le tableau de données
        $this->generatePage($data_page); 
    }
}