<?php  

// Namespace 'racine' du projet
namespace App;

class Autoloader
{
    /**
     * Permet d'appeler l'autoloader PHP et d'identifier le namespace de la classe courante
     *
     * @return void
     */
    static function register()
    {
        // Mise en place de l'autoload PHP
        spl_autoload_register([
            __CLASS__, // Identification de la classe courante
            'autoload' // Appel de la fonction 'autoload'
        ]);
    }

    /**
     * Permet de charger la classe courante
     *
     * @param [type] $className
     * @return void
     */
    static function autoload($className)
    {
        // Récupération dans $className de la totalité du namespace de la classe concernée
        // On retire le préfixe 'App\'
        $className =  str_replace(__NAMESPACE__ . '\\', '', $className);
       
        // On remplace les '\' par des '/'
        $className = str_replace('\\', '/', $className);

        // On transforme le premier caractère de la chaîne en minuscule
        $className = lcfirst($className);

        // On stocke le chemin absolu du fichier de la classe dans la variable $classFile
        $classFile = __DIR__ . '/' . $className . '.php';

        // On vérifie si le fichier $classFile existe
        if(file_exists($classFile)) {
            // Alors on charge le fichier de la classe 
            require_once $classFile;
        }
    }  
}