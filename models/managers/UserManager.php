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
        INSERT INTO user (firstName, lastName, address, phone, postal, city, country, coverImage, email, password, slug, activationToken, isValid, role, createdAt, isRgpd) 
        VALUES(:firstName, :lastName, :address, :phone, :postal, :city, :country, :coverImage, :email, :password, :slug, :activationToken, :isValid, :role, :createdAt, :isRgpd)
        ";
        // Connexion à la base de données et préparation d'une requête
        $stmt = $this->getDataBase()->prepare($req);
        // On établit la liaison entre marqueurs de requête et les valeurs correspondantes 
        $stmt->bindValue(":firstName", $user->getFirstName(), PDO::PARAM_STR);
        $stmt->bindValue(":lastName", $user->getLastName(), PDO::PARAM_STR);
        $stmt->bindValue(":address", $user->getAddress(), PDO::PARAM_STR);
        $stmt->bindValue(":phone", $user->getPhone(), PDO::PARAM_STR);
        $stmt->bindValue(":postal", $user->getPostal(), PDO::PARAM_STR);
        $stmt->bindValue(":city", $user->getCity(), PDO::PARAM_STR);
        $stmt->bindValue(":country", $user->getCountry(), PDO::PARAM_STR);
        $stmt->bindValue(":coverImage", $user->getCoverImage(), PDO::PARAM_STR);
        $stmt->bindValue(":email", $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(":password", $user->getPassword(), PDO::PARAM_STR);
        $stmt->bindValue(":slug", $user->getSlug(), PDO::PARAM_STR);
        $stmt->bindValue(":activationToken", $user->getActivationToken(), PDO::PARAM_STR);
        $stmt->bindValue(":isValid", $user->getIsValid(), PDO::PARAM_INT);
        $stmt->bindValue(":role", $user->getRole(), PDO::PARAM_STR);
        $stmt->bindValue(":createdAt", $user->getCreatedAt(), PDO::PARAM_STR);
        $stmt->bindValue(":isRgpd", $user->getIsRgpd(), PDO::PARAM_INT);
        // On exécute la requête 
        $stmt->execute();
        // On vérifie si la requête a abouti
        $isRequestSuccess = $stmt->rowCount() > 0;
        // On clôture la requête
        $stmt->closeCursor();
        // On retourne le statut de la requête
        return $isRequestSuccess;
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

    /**
     * Permet de rechercher un utilisateur pour activer son compte
     *
     * @param [type] $userSlug
     * @param [type] $activationToken
     * @return void
     */
    public function activate_account($userSlug, $activationToken)
    {
        // Définition de la requête
        $req = "UPDATE user set isValid = 1 WHERE slug = :slug AND activationToken = :activationToken";
        // Connexion à la base de données et préparation d'une requête
        $stmt = $this->getDataBase()->prepare($req);
        // On établit la liaison entre marqueurs de requête et les valeurs correspondantes 
        $stmt->bindValue(":slug", $userSlug, PDO::PARAM_STR);
        $stmt->bindValue(":activationToken", $activationToken, PDO::PARAM_STR);
        // On exécute la requête 
        $stmt->execute();
        // On récupère le résultat sous forme d'un tableau associatif
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // On clôture la requête
        $stmt->closeCursor();
        // On vérifie si la requête a abouti
        $isRequestSuccess = $stmt->rowCount() > 0;
        // On clôture la requête
        $stmt->closeCursor();
        // On retourne le statut de la requête
        return $isRequestSuccess;
    }

    /**
     * Permet de récupérer en base de données une instance de l'objet Utilisateur correspondant à l'email saisi dans le formulaire de connexion
     *
     * @param [type] $email
     * @return void
     */
    public function getUserByEmail($email)
    {
        // Définition de la requête
        $req="SELECT * FROM user WHERE email = :email";
        // Connexion à la base de données et préparation d'une requête
        $stmt = $this->getDataBase()->prepare($req);
        // On établit la liaison entre marqueurs de requête et les valeurs correspondantes 
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        // On définit le mode de récupération sous la forme d'une instance de la classe 'User'
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, User::class);
        // On exécute la requête 
        $stmt->execute();
        // On récupère le résultat
        $result = $stmt->fetch();
        // On clôture la requête
        $stmt->closeCursor();
        // On retourne le résultat de la requête
        return $result;
    }
}