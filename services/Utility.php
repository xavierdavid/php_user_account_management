<?php

// Namespace des Services
namespace App\Services;

use Exception;
 
class Utility 
{
    // Définition de constantes de classe pour gérer les types d'alertes 
    public const DANGER_MESSAGE = "alert-danger";
    public const WARNING_MESSAGE = "alert-warning";
    public const SUCCESS_MESSAGE = "alert-success";
    public const INFO_MESSAGE = "alert-info";

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
     * Permet de stocker le tableau d'erreurs d'une entité dans la session
     *
     * @param [type] $errors
     * @return void
     */
    public static function addFormErrorsIntoSession($errors) {
        // Ajout du tableau des erreurs de validation d'une entité à la session
        $_SESSION['errors'] = $errors;
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
     * Permet de générer un token avec chiffrement
     *
     * @return void
     */
    public static function generateToken()
    {
        $token = md5(uniqid());
        return $token;
    }

    /**
     * Permet de gérer le traitement et les vérifications pour l'upload d'un fichier image
     *
     * @param [type] $file // Fichier uploadé
     * @param [type] $dir // Répertoire cible pour le stockage du fichier
     * @return void // Retourne le nom unique du fichier 
     */
    public static function uploadImage($file, $dir) 
    {
        // Si aucun fichier n'est envoyée via le formulaire 
        if(!isset($file['name']) && !empty($file['name'])) {
            // On lance une exception
            throw new Exception("Vous devez uploader un fichier image !");
        }
        // Si aucun répertoire cible n'est défini
        if(!file_exists($dir)) {
            // On créé un nouveau répertoire avec les droits d'accès tout public (0777)
            mkdir($dir, 0777);
        }
        // On récupère l'extension du fichier (en minuscules)
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        // On génère un nombre aléatoire 
        $random = rand(0, 99999);
        // On renomme le fichier uploadé avec ce nombre pour éviter les doublons
        $target_file = $dir.$random.'_'.$file['name']; 

        // On vérifie que l'on a bien reçu le fichier à partir d'informations caractéristiques
    
        // Si le fichier temporaire n'a pas de taille ... 
        if(!getimagesize($file['tmp_name'])) {
            // On lance une exception
            throw new Exception("Erreur - Le fichier uploadé n'est pas une image !");
        }
        // si le fichier n'a pas l'extension caractéristique d'une image ...
        if($extension !== "jpg" && $extension !== "jpeg" && $extension !== "png" && $extension !== "gif"){
            // On lance une exception
            throw new Exception("Erreur - Le fichier uploadé n'est pas reconnu !");
        }
        // Si le fichier existe déjà dans le répertoire cible ...
        if(file_exists($target_file)) {
            // On lance une exception
            throw new Exception("Le fichier uploadé existe déjà !");
        }
        // Si la taille de l'image est trop grande ...
        if($file['size'] > 800000) {
            // On lance une exception
            throw new Exception("Le fichier uploadé est trop volumineux !");
        }
        // Si le fichier temporaire de l'image n'a pas été uploadé dans le répertoire cible ... 
        if(!move_uploaded_file($file['tmp_name'], $target_file)) {
            // On lance une exception
            throw new Exception("L'ajout de l'image a échoué !");
        } else {
            // Sinon, on upload par défaut le fichier et on retourne son nom pour son insertion en BD
            return ($random . "_" . $file['name']);
        }
    }
}