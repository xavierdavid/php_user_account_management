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
        INSERT INTO user (first_name, last_name, address, phone, postal, city, country, cover_image, email, password, slug, activation_token, is_valid, role, created_at, is_rgpd) 
        VALUES(:first_name, :last_name, :address, :phone, :postal, :city, :country, :cover_image, :email, :password, :slug, :activation_token, :is_valid, :role, :created_at, :is_rgpd)
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
        $stmt->bindValue(":is_rgpd", $user->getIsRgpd(), PDO::PARAM_INT);
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
        $req = "UPDATE user set is_valid = 1 WHERE slug = :slug AND activation_token = :activationToken";
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
     * Permet de vérifier que l'email et le mot de passe existent en base de données
     *
     * @param [type] $email
     * @param [type] $password
     * @return boolean
     */
    public function isAuthenticationValid($email, $password)
    {
        // Récupération en base de données du mot de passe crypté correspondant à l'email de l'utilisateur
        $userPassword = $this->getUserPassword($email);
        // On vérifie si le mot de passe saisi et le mot de passe crypté récupéré en base de données sont identiques
        return password_verify($password, $userPassword);
    }

    /**
     * Permet de récupérer en base de données le mot de passe correspondant à l'email saisi par l'utilisateur
     *
     * @param [type] $email
     * @return void
     */
    private function getUserPassword($email)
    {
        // Définition de la requête
        $req="SELECT password FROM user WHERE email = :email";
        // Connexion à la base de données et préparation d'une requête
        $stmt = $this->getDataBase()->prepare($req);
        // On établit la liaison entre marqueurs de requête et les valeurs correspondantes 
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        // On exécute la requête 
        $stmt->execute();
        // On récupère le résultat sous forme d'un tableau associatif
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // On clôture la requête
        $stmt->closeCursor();
        // On clôture la requête
        $stmt->closeCursor();
        // On retourne le résultat de la requête (mot de passe crypté)
        return $result['password'];
    }

    /**
     * Permet de vérifier que le compte
     *
     * @param [type] $email
     * @return boolean
     */
    public function isAccountValid($email)
    {
        // Définition de la requête
        $req="SELECT is_valid FROM user WHERE email = :email";
        // Connexion à la base de données et préparation d'une requête
        $stmt = $this->getDataBase()->prepare($req);
        // On établit la liaison entre marqueurs de requête et les valeurs correspondantes 
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        // On exécute la requête 
        $stmt->execute();
        // On récupère le résultat sous forme d'un tableau associatif
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // On clôture la requête
        $stmt->closeCursor();
        // On clôture la requête
        $stmt->closeCursor();
        // On retourne true ou false selon le résultat de la requête (au format int)
        return ((int)$result['is_valid'] === 1) ? true : false;
    }
}