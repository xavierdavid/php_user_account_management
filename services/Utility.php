<?php

// Namespace des Services
namespace App\Services;
 
class Utility {

    // Définition de constantes de classe pour gérer les alertes 
    public const DANGER_MESSAGE = "alert-danger";
    public const WARNING_MESSAGE = "alert-warning";
    public const SUCCESS_MESSAGE = "alert-success";

    /**
     * Permet d'ajouter des alertes (message et type) à la session courante
     *
     * @param [type] $message
     * @param [type] $type
     * @return void
     */
    public static function addAlertMessage($message, $type)
    {
        // Ajout à la session d'un tableau 'alert' contenant des tableaux avec les messages et les types
        $_SESSION['alert'][] = [
            "message" => $message,
            "type" => $type
        ];
    }

     /**
     * Permet de générer une redirection vers une url spécifique
     *
     * @param string $url
     * @return void
     */
    public function redirect(string $url): void
    {
        header("Location: $url");
        exit();
    }
}