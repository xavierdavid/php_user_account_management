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
}