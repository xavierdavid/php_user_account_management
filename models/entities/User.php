<?php  

namespace App\Models\Entities;

class User 
{
    // Attributs de la classe 
    private $id;
    private $firstName;
    private $lastName;
    private $address;
    private $phone;
    private $postal;
    private $city;
    private $country;
    private $coverImage;
    private $email;
    private $password;
    private $slug;
    private $activationToken;
    private $isValid;
    private $role;
    private $createdAt;
    private $isRgpd;
    private $errors=[]; // Attribut spécifique stockant les éventuelles erreurs dans un tableau

    // Constantes de classe
    const INVALID_FIRSTNAME = "INVALID_FIRSTNAME";
    const INVALID_LASTNAME = "INVALID_LASTNAME";
    const INVALID_ADDRESS = "INVALID_ADDRESS";
    const INVALID_PHONE = "INVALID_PHONE";
    const INVALID_POSTAL = "INVALID_POSTAL";
    const INVALID_CITY = "INVALID_CITY";
    const INVALID_COUNTRY = "INVALID_COUNTRY";
    const INVALID_EMAIL = "INVALID_EMAIL";
    const INVALID_PASSWORD = "INVALID_PASSWORD";
    const INVALID_RGPD = "INVALID_RGPD";

   /**
    * Constructeur de la classe User
    * Récupère un tableau associatif contenant les données d'un utilisateur
    * Utilise ces données pour hydrater un nouvel objet lors de la phase d'instanciation
    *
    * @param array $data
    */
    public function __construct(array $data = []) 
    {
        // Si le tableau de données n'est pas vide
        if(!empty($data)){
            // Alors on appelle la méthode hydrate de la classe pour hydrater l'objet
            $this->hydrate($data);
        }
    }

    /**
     * Permet d'hydrater un objet en assignant des valeurs à ses attributs
     *
     * @param array $data
     * @return void
     */
    public function hydrate(array $data)
    {
        // On boucle sur le tableau associatif $data
        foreach ($data as $key => $value) {
           // Définition du setter correspondant à la clé 'attribut'
           $setterMethod = 'set'.ucfirst($key);
           // Si le setter existe dans la classe ou l'objet courant
           if (method_exists($this, $setterMethod)) {
               // Alors on appelle le setter pour assigner la valeur 
               $this->$setterMethod($value);
           } 
        }
    }

    public function getId()
    {
       return $this->id;
    }

    public function setId(int $id)
    {
        // Si l'identifiant n'est pas vide
        if(!empty($id)) {
            // Alors on affecte la valeur $id à l'attribut de l'objet en cours
            // Contrôle de validité - $id doit être un entier
            $this->id = (int) $id;
            return $this;
        };
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName)
    {
        // Si le prénom est vide ou si ce n'est pas une chaîne d'au moins 3 caractères
        if(empty($firstName) || !is_string($firstName) || strlen($firstName) < 3) {
            // Alors on affecte une erreur à l'attribut $errors (tableau des erreurs)
            $this->errors[] = self::INVALID_FIRSTNAME;
        } else {
            // Sinon on affecte la valeur $firstName à l'attribut de l'objet en cours
            // Contrôle de validité - $firstName doit être une chaîne
            $this->firstName = (string) $firstName;
            return $this;
        }
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName)
    {
        // Si le nom est vide ou si ce n'est pas une chaîne d'au moins 2 caractères
        if(empty($lastName) || !is_string($lastName) || strlen($lastName) < 2) {
            // Alors on affecte une erreur à l'attribut $errors (tableau des erreurs)
            $this->errors[] = self::INVALID_LASTNAME;
        } else {
            // Sinon on affecte la valeur $lastName à l'attribut de l'objet en cours
            // Contrôle de validité - $lastName doit être une chaîne
            $this->lastName = (string) $lastName;
            return $this;
        }
    }

    public function getAddress()
    {
        return $this->address;
    } 

    public function setAddress(string $address)
    {
        // Si le nom est vide ou si ce n'est pas une chaîne de caractères
        if(empty($address) || !is_string($address)) {
            // Alors on affecte une erreur à l'attribut $errors (tableau des erreurs)
            $this->errors[] = self::INVALID_ADDRESS;
        } else {
            // Sinon on affecte la valeur $address à l'attribut de l'objet en cours
            // Contrôle de validité - $address doit être une chaîne
            $this->address = (string) $address;
            return $this;
        }
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone(string $phone)
    {
        // Si le téléphone est vide ou s'il n'est pas conforme à l'expression régulière 
        if(empty($phone) || !preg_match('#^0[1-68][0-9]{8}$#', $phone)) {
            // Alors on affecte une erreur à l'attribut $errors (tableau des erreurs)
            $this->errors[] = self::INVALID_PHONE;
        } else {
            // Sinon on affecte la valeur $phone à l'attribut de l'objet en cours
            // Contrôle de validité - $phone doit être une chaîne
            $this->phone = (string) $phone;
            return $this; 
        }
    }

    public function getPostal()
    {
        return $this->postal;
    }

    public function setPostal(string $postal)
    {
        // Si le nom est vide ou ou s'il n'est pas conforme à l'expression régulière
        if(empty($postal) || !preg_match('#^(([0-8][0-9])|(9[0-5])|(2[ab]))[0-9]{3}$#', $postal)) {
            // Alors on affecte une erreur à l'attribut $errors (tableau des erreurs)
            $this->errors[] = self::INVALID_POSTAL;
        } else {
            // Sinon on affecte la valeur $postal à l'attribut de l'objet en cours
            // Contrôle de validité - $postal doit être une chaîne
            $this->postal = (string) $postal;
            return $this;
        }
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity(string $city)
    {
        // Si la ville est vide ou si ce n'est pas une chaîne de caractères
        if(empty($city) || !is_string($city)) {
            // Alors on affecte une erreur à l'attribut $errors (tableau des erreurs)
            $this->errors[] = self::INVALID_CITY;
        } else {
            // Sinon on affecte la valeur $city à l'attribut de l'objet en cours
            // Contrôle de validité - $city doit être une chaîne
            $this->city = (string) $city;
            return $this;
        }
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry(string $country)
    {
        // Si le pays est vide ou s'il' ce n'est pas une chaîne de caractères
        if(empty($country) || !is_string($country)) {
            // Alors on affecte une erreur à l'attribut $errors (tableau des erreurs)
            $this->errors[] = self::INVALID_COUNTRY;
        } else {
            // Sinon on affecte la valeur $country à l'attribut de l'objet en cours
            // Contrôle de validité - $country doit être une chaîne
            $this->country = (string) $country;
            return $this;
        }
    }

    public function getCoverImage()
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage)
    {
        // On affecte la valeur $coverImage à l'attribut de l'objet en cours
        // Contrôle de validité - $coverImage doit être une chaîne
        $this->coverImage = (string) $coverImage;
        return $this;
    }

    public function getEmail()
    {
       return $this->email;
    }

    public function setEmail(string $email)
    {
        // Si l'email est vide ou s'il ne correspond pas au type 'email'
        if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
             // Alors on affecte une erreur à l'attribut $errors (tableau des erreurs)
             $this->errors[] = self::INVALID_EMAIL;
        } else {
            $this->email = (string) $email;
            return $this;
        }
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        // Si le mot de passe est vide ou si ce n'est pas une chaîne de caractères
        if(empty($password) || !is_string($password)) {
            // Alors on affecte une erreur à l'attribut $errors (tableau des erreurs)
            $this->errors[] = self::INVALID_PASSWORD;
        } else {
            // Sinon on affecte la valeur $password à l'attribut de l'objet en cours
            // Contrôle de validité - $password doit être une chaîne
            $this->password = (string) $password;
            return $this;
        }
    }
    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug(string $slug)
    {
        // Si le mot de passe est vide ou si ce n'est pas une chaîne de caractères
        if(empty($slug) || !is_string($slug)) {
            // Alors on affecte une erreur à l'attribut $errors (tableau des erreurs)
            $this->errors[] = self::INVALID_PASSWORD;
        } else {
            // Sinon on affecte la valeur $slug à l'attribut de l'objet en cours
            // Contrôle de validité - $slug doit être une chaîne
            $this->slug = (string) $slug;
            return $this;
        }
    }

    public function getActivationToken()
    {
        return $this->activationToken;
    }

    public function setActivationToken(string $activationToken)
    {
        // Si le token de validation n'est pas vide
        if(!empty($activationToken)) {
            // Alors on affecte la valeur $activationToken à l'attribut de l'objet en cours
            // Contrôle de validité - $activationToken doit être une châine
            $this->activationToken = (string) $activationToken;
            return $this;
        }
    }

    public function getIsValid()
    {
        return $this->isValid;
    }

    public function setIsValid(int $isValid)
    {
        // Si $isValid est définie (peut être nulle)
        if(isset($isValid)) {
            // Alors on affecte la valeur $isValid à l'attribut de l'objet en cours
            // Contrôle de validité - $isValid doit être un entier
            $this->isValid = (int) $isValid;
            return $this;
        }
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole(string $role)
    {
        // Si le role n'est pas vide
        if(!empty($role)) {
            // Alors on affecte la valeur $role à l'attribut de l'objet en cours
            // Contrôle de validité - $role doit être un entier
            $this->role = (string) $role;
            return $this;
        }
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt)
    {
        // Si la date de création n'est pas vide
        if(!empty($createdAt)) {
            // Alors on affecte la valeur $createdAt à l'attribut de l'objet en cours
            // Contrôle de validité - $createdAt doit être un entier
            $this->createdAt = (string) $createdAt;
            return $this;
        }
    }

    public function getIsRgpd()
    {
        return $this->isRgpd;
    }

    public function setIsRgpd(int $isRgpd)
    {
        // Si $isRgpd a une valeur égale à '0'
        if($isRgpd == 0) {
            // Alors on affecte une erreur à l'attribut $errors (tableau des erreurs)
            $this->errors[] = self::INVALID_RGPD;
        } else {
            // Sinon on affecte la valeur $isRgpd à l'attribut de l'objet en cours
            // Contrôle de validité - $isRgpd doit être un entier
            $this->isRgpd = (int) $isRgpd;
            return $this;
        }
    }
   
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Vérifie si tous les pré-requis nécessaires sont vérifiés pour valider un utilisateur
     *
     * @return boolean
     */
    public function isUserValid(): bool
    {
        // Retourne 'true' si les informations requises suivantes ne sont pas vides
        return !(empty($this->firstName) || empty($this->lastName) || empty($this->address) || empty($this->phone) || empty($this->postal) || empty($this->city) || empty($this->country) || empty($this->email) || empty($this->password));
    }
} 