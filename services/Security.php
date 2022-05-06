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

    /**
     * Permet de vérifier qu'un utilisateur dispose du rôle 'utilisateur
     *
     * @return boolean
     */
    public static function isUser()
    {
        // On retourne 'true' si la variable de session 'role' est égale à 'ROLE_USER'
        return ($_SESSION['user']['role'] === (string) 'ROLE_USER');
    }

    /**
     * Permet de vérifier qu'un utilisateur dispose du rôle 'administrateur'
     *
     * @return boolean
     */
    public static function isAdmin()
    {
        // On retourne 'true' si la variable de session 'role' est égale à 'ROLE_ADMIN'
        return ($_SESSION['user']['role'] === (string) 'ROLE_ADMIN');
    }

    /**
     * Permet de vérifier qu'un utilisateur dispose du statut de 'membre'
     *
     * @return boolean
     */
    public static function isMember()
    {
        // On retourne 'true' si la variable de session 'isMember' est égale à 1
        return ($_SESSION['user']['isMember'] == (int) 1);
    }
}