<?php 

namespace App\Models\Managers;

use App\Models\Model;
use App\Models\Entities\User;
use PDO;

class UserManager extends Model
{
    /**
     * Permet d'ajouter un nouvel utilisateur en base de données
     *
     * @param User $user
     * @return void
     */
    public function new(User $user)
    {
        // Définition de la requête
        $req = "
        INSERT INTO user (first_name, last_name, address, phone, postal, city, country, cover_image, email, password, slug, activation_token, is_valid, role, created_at) 
        VALUES(:first_name, :last_name, :address, :phone, :postal, :city, :country, :cover_image, :email, :password, :slug, :activation_token, :is_valid, :role, :created_at)
        ";
        // Connexion à la base de données et préparation d'une requête
        $stmt = $this->getDataBase()->prepare($req);
        // On établit la liaison entre marqueurs de requête et les valeurs correspondantes 
        $stmt->bindValue(":first_name", $user->getFirstName(), PDO::PARAM_STR);
        $stmt->bindValue(":last_name", $user->getLastName(), PDO::PARAM_STR);
        $stmt->bindValue(":address", $user->getAddress(), PDO::PARAM_STR);
        $stmt->bindValue(":phone", $user->getPhone(), PDO::PARAM_STR);
        $stmt->bindValue(":postal", $user->getPostal(), PDO::PARAM_STR);
        $stmt->bindValue(":city", $user->getCity(), PDO::PARAM_STR);
        $stmt->bindValue(":country", $user->getCountry(), PDO::PARAM_STR);
        $stmt->bindValue(":cover_image", $user->getCoverImage(), PDO::PARAM_STR);
        $stmt->bindValue(":email", $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(":password", $user->getPassword(), PDO::PARAM_STR);
        $stmt->bindValue(":slug", $user->getSlug(), PDO::PARAM_STR);
        $stmt->bindValue(":cover_image", $user->getCoverImage(), PDO::PARAM_STR);
        $stmt->bindValue(":activation_token", $user->getActivationToken(), PDO::PARAM_STR);
        $stmt->bindValue(":is_valid", $user->getIsValid(), PDO::PARAM_INT);
        $stmt->bindValue(":role", $user->getRole(), PDO::PARAM_STR);
        $stmt->bindValue(":created_at", $user->getCreatedAt(), PDO::PARAM_STR);
        // On exécute la requête 
        $stmt->execute();
        // On clôture la requête
        $stmt->closeCursor();
    }

    /**
     * Permet de vérifier si l'email d'un utilisateur est présent en base de données
     *
     * @param [type] $email
     * @return boolean
     */
    public function isUserEmailExist($email)
    {
        // Définition de la requête
        $req = "SELECT * FROM user WHERE email = :email";
        // Connexion à la base de données et préparation d'une requête
        $stmt = $this->getDataBase()->prepare($req);
        // On établit la liaison entre marqueurs de requête et les valeurs correspondantes 
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        // On exécute la requête 
        $stmt->execute();
        // On récupère le résultat
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // On clôture la requête
        $stmt->closeCursor();
        // Retourne 'true' si le résultat n'est pas vide
        return !empty($result);
    }
}