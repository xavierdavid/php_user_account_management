<?php

// Namespace des services
namespace App\Services;

class Security 
{
    /**
     * Permet de sécuriser les chaînes envoyées via un formulaire en supprimant les balises HTML
     * 
     * @return void
     */
    public static function secureHtml(string $string)
    {
        return strip_tags($string);
    }

    /**
     * Permet de vérifier qu'un utilisateur est connecté
     *
     * @return boolean
     */
    public static function isAuthenticated()
    {
        // On retourne 'true' si une variable de session 'user' existe ou faux si tel n'est pas le cas
        return(!empty($_SESSION['user']));
    }
}