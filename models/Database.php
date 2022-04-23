<?php 

// Namespace des Models
namespace App\Models;

// Utilisation de la classe PDO
use PDO;

abstract class Database
{
    private static $pdo;

    // Informations de connexion - Constantes de classe
    private const DBHOST = 'localhost';
    private const DBUSER = 'root';
    private const DBPASS = '';
    private const DBNAME = 'accountmanager';

    /**
     * Permet de créer une instance de la classe PDO pour établir la connexion à la base de données
     *
     * @return void
     */
    private static function setDataBase()
    {
        self::$pdo = new PDO('mysql:host='.self::DBHOST.';dbname='.self::DBNAME.';charset=utf8',self::DBUSER,self::DBPASS);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
    }

    /**
     * Permet de tester si une instance de PDO existe et retourne une instance unique de PDO
     *
     * @return void
     */
    protected function getDataBase()
    {
        // Si aucune instance $pdo n'existe 
        if(self::$pdo === null) {
            // Alors on appelle la fonction setDataBase() pour créer une nouvelle instance PDO
            self::setDataBase();
        }
        // On retourne dans tous les cas l'instance de PDO 
        return self::$pdo;
    }
}