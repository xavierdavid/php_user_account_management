<?php 

//Namespace 'Home' des contrôleurs
namespace App\Controllers\Home;

// Utilisation des classes
use App\Services\Utility;
use App\Models\Entities\User;
use App\Controllers\MainController;
use App\Models\Managers\UserManager;

class HomeController extends MainController
{
    private $userManager;

    /**
     * Initialisation d'une instance de la classe UserManager
     */
    public function __construct() 
    {
        $this->userManager = new UserManager;
    }

    /**
     * Contrôle le paramétrage et l'affichage de la page d'accueil 
     *
     * @return void
     */
    public function home()
    {
        // Définition d'un tableau associatif regroupant les données d'affichage de la page d'accueil du site
        $data_page = [
            "page_description" => "Description de la page d'accueil",
            "page_title" => "Titre de la page d'accueil",
            "view" => "views/home/home.php",
            "template" => "views/common/template.php"
        ];
        // Affichage de la page à l'aide de la méthode generatePage à laquelle on envoie le tableau de données
        $this->generatePage($data_page);
    }

    /**
     * Rédéfinition de méthode pour appeler la méthode parente 'errorPage' du MainController
     *
     * @param [type] $errorMessage
     * @return void
     */
    public function errorPage($errorMessage)
    {
        parent::errorPage($errorMessage);
    }

    /**
     * Contrôle l'affichage de la page formulaire d'inscription utilisateur
     *
     * @return void
     */
    public function register()
    {
        // Définition d'un tableau associatif regroupant les données d'affichage de la page d'accueil du site
        $data_page = [
            "page_description" => "Page d'inscription",
            "page_title" => "Page d'inscription",
            "view" => "views/home/register.php",
            "template" => "views/common/template.php"
        ];
        // Affichage de la page à l'aide de la méthode generatePage à laquelle on envoie le tableau de données
        $this->generatePage($data_page);
    }

    /**
     * Contrôle le traitement du formulaire d'inscription utilisateur
     *
     * @return void
     */
    public function register_validation()
    {
        // Vérification de la soumission du formulaire
        if(!empty($_POST)) {
            // Vérification que tous les champs requis sont renseignés
            if(isset($_POST['first_name'], $_POST['last_name'], $_POST['address'], $_POST['postal'], $_POST['city'], $_POST['country'], $_POST['email'], $_POST['password'], $_POST['password_confirm']) 
                && !empty($_POST['first_name']) 
                && !empty($_POST['last_name']) 
                && !empty($_POST['address']) 
                && !empty($_POST['postal']) 
                && !empty($_POST['city']) 
                && !empty($_POST['country']) 
                && !empty($_POST['email']) 
                && !empty($_POST['password'])
                && !empty($_POST['password_confirm'])) {

                    // Récupération et sécurisation des données
                    $firstName = strip_tags($_POST['first_name']);
                    $lastName = strip_tags($_POST['last_name']);
                    $address = strip_tags($_POST['address']);
                    $postal = strip_tags($_POST['postal']);
                    $city = strip_tags($_POST['city']);
                    $country = strip_tags($_POST['country']);
                    $coverImage = strip_tags($_POST['cover_image']);
                    $phone = strip_tags($_POST['phone']);
                    $email = strip_tags($_POST['email']);
                    $password = strip_tags($_POST['password']);
                    $password_confirm = strip_tags($_POST['password_confirm']);

                    // Si l'email renseigné n'est pas de type email
                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        // Affichage d'un message d'erreur
                        die("L'adresse email saisie est incorrecte !");
                    }

                    // Si le mot de passe confirmé n'est pas identique au mot de passe 
                    if($password_confirm !== $password) {
                        die("Les deux mots de passe saisis ne sont pas identiques");
                    }

                    // Hachage du mot de passe
                    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Génération automatique du slug de l'utilisateur à l'aide de la fonction generateSlug(), à partir d'une chaîne composée de son nom et de son prénom
                    $text = $firstName . " " . $lastName;
                    $slug = Utility::generateSlug($text);

                    // Génération d'un token d'activation de compte
                    $activationToken = Utility::generateActivationToken();
                    var_dump("Le token d'activation est : " . $activationToken);

                    // Validation de l'activation par email
                    $isValid = true;

                    // Génération du rôle de l'utilisateur par défaut
                    $role = "ROLE_USER";

                    // Génération de la date de création du compte
                    $createdAt = Utility::generateDate();

                    // Instanciation et hydratation d'un nouvel objet User ...
                    $user = new User(
                        // ... A partir du tableau associatif des données utilisateurs renseignées dans le formulaire d'inscription
                        [
                            'firstName' => $firstName,
                            'lastName' => $lastName,
                            'address' => $address,
                            'postal' => $postal,
                            'city' => $city,
                            'country' => $country,
                            'coverImage' => $coverImage,
                            'phone' => $phone,
                            'email' => $email,
                            'password' => $hashPassword,
                            'slug' => $slug,
                            'activationToken' => $activationToken,
                            'isValid' => $isValid,
                            'role' => $role,
                            'createdAt' => $createdAt
                        ]
                    );
    
                    // Si le nouvel objet User instancié est valide
                    if($user->isUserValid()) {
                        // Alors on envoie les données de l'utilisateur en base de données à l'aide du UserManager
                        $this->userManager->new($user);
                        echo "L'utilisateur a bien été enregistré !";
                    } else {
                        echo "L'utilisateur n'est pas valide";
                    }
  
            } else {
                // Affichage d'un message d'erreur si le formulaire est incomplet
                die("Le formulaire n'est pas complet");
            }
        } 
    }
}