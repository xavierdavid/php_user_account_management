<?php 

// Namespace des Models
namespace App\Models;

// Utilisation de la classe Model associée au namespace des Models
use App\Models\Model;
// Utilisation de la classe PDO
use PDO;

class MainManager extends Model
{
    /**
     * Permet de récupérer des données stockées dans la base
     *
     * @return void
     */
    public function getDatas()
    {
        // Connexion à la base de données et préparation d'une requête
        $req = $this->getDataBase->prepare("SELECT * FROM phpbasetable");
        // Exécution de la requête
        $req->execute();
        // Récupération des données
        $datas = $req->fetchAll(PDO::FETCH_ASSOC);
        // Fermeture de la requête
        $req->closeCursor();
        // Retour des données
        return $datas;
    }
}