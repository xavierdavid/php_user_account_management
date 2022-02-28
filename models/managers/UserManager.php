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
     * Permet de rechercher un utilisateur à partir de son slug et son token d'activation pour activer son compte
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
        // On vérifie si la requête a abouti
        $isRequestSuccess = $stmt->rowCount() > 0;
        // On clôture la requête
        $stmt->closeCursor();
        // On retourne le statut de la requête
        return $isRequestSuccess;
    }

    /**
     * Permet de récupérer en base de données une instance de l'objet Utilisateur correspondant à l'email saisi dans le formulaire de connexion ou stocké dans la session
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
        // On récupère le résultat (utilisateur)
        $user = $stmt->fetch();
        // On clôture la requête
        $stmt->closeCursor();
        // On retourne le résultat la requête (utilisateur)
        return $user;
    }

    /**
     * Permet de récupérer en base de données une instance de l'objet Utilisateur à partir de son identifiant unique (id)
     *
     * @param [type] $id
     * @return void
     */
    public function getUserById($id)
    {
        // Définition de la requête
        $req="SELECT * FROM user WHERE id = :id";
        // Connexion à la base de données et préparation d'une requête
        $stmt = $this->getDataBase()->prepare($req);
        // On établit la liaison entre marqueurs de requête et les valeurs correspondantes 
        $stmt->bindValue(":id", $id, PDO::PARAM_STR);
        // On définit le mode de récupération sous la forme d'une instance de la classe 'User'
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, User::class);
        // On exécute la requête 
        $stmt->execute();
        // On récupère le résultat (utilisateur)
        $user = $stmt->fetch();
        // On clôture la requête
        $stmt->closeCursor();
        // On retourne le résultat la requête (utilisateur)
        return $user;
    }

    /**
     * Permet d'enregistrer en base de données une nouvelle valeur de token d'activation de compte pour une instance d'objet utilisateur User
     *
     * @param [type] $user
     * @param [type] $newActivationToken
     * @return void
     */
    public function updateActivationToken($user, $newActivationToken)
    {
        // Définition de la requête
        $req = "UPDATE user set activationToken = :activationToken WHERE email = :email";
        // Connexion à la base de données et préparation d'une requête
        $stmt = $this->getDataBase()->prepare($req);
        // On établit la liaison entre marqueurs de requête et les valeurs correspondantes 
        $stmt->bindValue(":email", $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(":activationToken", $newActivationToken, PDO::PARAM_STR);
         // On exécute la requête 
         $stmt->execute();
         // On vérifie si la requête a abouti
         $isUpdateToken = $stmt->rowCount() > 0;
         // On clôture la requête
         $stmt->closeCursor();
         // On retourne le statut de la requête
         return $isUpdateToken;
    }

    /**
     * Permet d'enregistrer en base de données le token de réinitialisation de compte pour une instance d'objet utilisateur User
     *
     * @param [type] $user
     * @param [type] $resetToken
     * @return void
     */
    public function setResetTokenIntoDatabase($user, $resetToken) {
        // Définition de la requête
        $req = "UPDATE user set resetToken = :resetToken WHERE email = :email";
        // Connexion à la base de données et préparation d'une requête
        $stmt = $this->getDataBase()->prepare($req);
        // On établit la liaison entre marqueurs de requête et les valeurs correspondantes 
        $stmt->bindValue(":email", $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(":resetToken", $resetToken, PDO::PARAM_STR);
         // On exécute la requête 
         $stmt->execute();
         // On vérifie si la requête a abouti
         $isResetToken = $stmt->rowCount() > 0;
         // On clôture la requête
         $stmt->closeCursor();
         // On retourne le statut de la requête
         return $isResetToken;
    }

    /**
     * Permet de rechercher un utilisateur à partir du slug et du token de réinitialisation de mot de passe
     *
     * @param [type] $userSlug
     * @param [type] $resetToken
     * @return void
     */
    public function isUserWithSlugAndResetToken($userSlug, $resetToken)
    {
        // Définition de la requête
        $req = "SELECT * FROM user WHERE slug = :slug AND resetToken = :resetToken";
        // Connexion à la base de données et préparation d'une requête
        $stmt = $this->getDataBase()->prepare($req);
        // On établit la liaison entre marqueurs de requête et les valeurs correspondantes 
        $stmt->bindValue(":slug", $userSlug, PDO::PARAM_STR);
        $stmt->bindValue(":resetToken", $resetToken, PDO::PARAM_STR);
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
     * Permet de récupérer en base de données une instance de l'objet Utilisateur correspondant à un slug et un restToken
     *
     * @param [type] $userSlug
     * @param [type] $resetToken
     * @return void
     */
    public function getUserBySlugAndResetToken($userSlug, $resetToken)
    {
        // Définition de la requête
        $req="SELECT * FROM user WHERE slug = :slug AND resetToken = :resetToken";
        // Connexion à la base de données et préparation d'une requête
        $stmt = $this->getDataBase()->prepare($req);
        // On établit la liaison entre marqueurs de requête et les valeurs correspondantes 
        $stmt->bindValue(":slug", $userSlug, PDO::PARAM_STR);
        $stmt->bindvalue(":resetToken", $resetToken, PDO::PARAM_STR);
        // On définit le mode de récupération sous la forme d'une instance de la classe 'User'
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, User::class);
        // On exécute la requête 
        $stmt->execute();
        // On récupère le résultat (utilisateur)
        $user = $stmt->fetch();
        // On clôture la requête
        $stmt->closeCursor();
        // On retourne le résultat la requête (utilisateur)
        return $user;
    }

    /**
     * Permet d'enregistrer en base de données un nouveau mot de passe pour une instance d'objet utilisateur User
     *
     * @param [type] $user
     * @param [type] $hashNewPassword
     * @return void
     */
    public function setNewPasswordIntoDatabase($user, $hashNewPassword)
    {
        // Définition de la requête
        $req = "UPDATE user set password = :password WHERE email = :email";
        // Connexion à la base de données et préparation d'une requête
        $stmt = $this->getDataBase()->prepare($req);
        // On établit la liaison entre marqueurs de requête et les valeurs correspondantes 
        $stmt->bindValue(":email", $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(":password", $hashNewPassword, PDO::PARAM_STR);
         // On exécute la requête 
         $stmt->execute();
         // On vérifie si la requête a abouti
         $isNewPasswordIntoDatabase = $stmt->rowCount() > 0;
         // On clôture la requête
         $stmt->closeCursor();
         // On retourne le statut de la requête
         return $isNewPasswordIntoDatabase;
    }

    /**
     * Permet d'enregistrer en base de données un nouvel email pour une instance d'objet utilisateur User
     *
     * @param [type] $user
     * @return void
     */
    public function setUserNewEmailIntoDatabase($user, $newEmail)
    {
        // Définition de la requête
        $req = "UPDATE user set email = :email WHERE id = :id";
        // Connexion à la base de données et préparation d'une requête
        $stmt = $this->getDataBase()->prepare($req);
        // On établit la liaison entre marqueurs de requête et les valeurs correspondantes 
        $stmt->bindValue(":email", $newEmail, PDO::PARAM_STR);
        $stmt->bindValue(":id",$user->getId(), PDO::PARAM_STR);
         // On exécute la requête 
         $stmt->execute();
         // On vérifie si la requête a abouti
         $isUserNewEmailIntoDatabase = $stmt->rowCount() > 0;
         // On clôture la requête
         $stmt->closeCursor();
         // On retourne le statut de la requête
         return $isUserNewEmailIntoDatabase;
    }
}