<?php

// Namespace des Services
namespace App\Services;
 
class Utility {

    // Définition de constantes de classe pour gérer les types d'alertes 
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
    public static function redirect(string $url): void
    {
        header("Location: $url");
        exit();
    }

    /**
     * Permet de générer un slug à partir d'une chaîne et d'un séparateur
     *
     * @param [type] $text
     * @param string $divider
     * @return void
     */
    public static function generateSlug($text, string $divider = '-')
    {
        // Remplacement des caractères qui ne sont ni des lettres (\pL) ni des chiffres (\d) (ex: apostrophes, espaces), par le séparateur '-' 
        $text = preg_replace('#[^\pL\d]+#u', $divider, $text);

        // Conversion de la chaîne selon la norme 'us-ascii'
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // Suppression des caractères qui ne sont ni des '-', ni des caractères alphanumériques
        $text = preg_replace('#[^-\w]+#', '', $text);

        // Suppression des espaces présents en début et fin de chaîne
        $text = trim($text, $divider);

        // Remplacement des '-' éventuellement dupliqués par le séparateur '-'
        $text = preg_replace('#-+#', $divider, $text);

        // Transformation de la chaîne en minuscules
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

   /**
    * Permet de générer la date du jour au format datetime
    *
    * @return void
    */
    public static function generateDate()
    {
        $dateTime = date('Y-m-d H:i:s');
        return $dateTime;
    }

    /**
     * Permet de générer un token d'activation avec chiffrement
     *
     * @return void
     */
    public static function generateActivationToken()
    {
        $activationToken = md5(uniqid());
        return $activationToken;
    }
}