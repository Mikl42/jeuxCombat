<?php

class Player {

    protected $table = "player";
    protected $listeChamps = ["hp", "strength", "agility", "resistance", "pseudo", "pwd", "room"];
    protected $valeurs = [];
    protected $id = 0;

    function __construct($id = null) {
        if (!is_null($id))
            $this->loadById($id);
    }

    /**
     * @param $champ
     * @return mixed|string
     */
    function get($champ) {
        if (isset($this->valeurs[$champ])) {
            return $this->valeurs[$champ];
        } else {
            return "";
        }
    }

    /**
     * @return int
     */
    function getId() {
        return $this->id;
    }

    /**
     * @param $champ
     * @param $valeur
     * @return bool
     */
    public function set($champ, $valeur) {
        if (!in_array($champ, $this->listeChamps)) {
            return false;
        }
        $this->valeurs[$champ] = $valeur;
        return true;
    }

    /**
     * @return bool
     */
    public function create() {
        // Role : insert dans la base de donnée
        // Paramètre : néant
        // Retour : true si tout est ok , false sinon
        // Construction de la requête
        $sql = "INSERT INTO `$this->table` SET ";
        $param = [];
        $liste = [];
        foreach ($this->listeChamps as $champ) {
            $liste[] = "`$champ` = :$champ";
            $param[":$champ"] = $this->get($champ);
        }

        $param["hp"] = 100;
        $param["room"] = 0;

        $sql .= implode(", ", $liste);
        $req = $this->executeSql($sql, $param);
        if ($req === false) {
            echo "Il y a une erreur dans la requête $sql (paramètres : $param)";
            return false;
        }

        return true;
    }

    /**
     * @param $sql
     * @param array $param
     * @return false|PDOStatement
     */
    private function executeSql($sql, $param = []) {
        // Preparer et executer la requete
        // Recupere le connecteur a la base de donnée
        global $bdd;
        // Prepare la requete
        $req = $bdd->prepare($sql);
        // Test si la requete a rencontré une erreur on retourne false
        if (!$req->execute($param)) {
            echo "Erreur SQL = $sql <br>";
            return false;
        }
        // La requete a bien été executer
        return $req;
    }

    /**
     * @param $tab
     */
    public function loadFromPost($tab) {
        foreach ($tab as $index => $value) {
            $this->set($index, $value);
        }
    }
/*
    function verifiePlayer($pseudo) {
        //role : verifie que le pseudo existe ds la table et le pwd
        //retour : true si on trouve false sinon
        // parametre
        //      $pseudo 
        //      $pwd
        
        
      
        $sql="SELECT * FROM `player`";
        $sql .= "WERE `pseudo` LIKE :pseudo";
        
        //init des parametre
        $param = [
            ":pseudo" => $pseudo,
        ];
       $req = $this->executeSql($sql, $param);
        if ($req === false) {
            echo "Il y a une erreur dans la requête $sql (paramètres : $param)";
            return false;
        }
        $ligne=
        
     
    }*/

    function loadStat($id){
        //role : charge les stats du player 
        //parametre : $id de perso
        //return un tableau de l'objet player

$sql="SELECT `id`,`strength`,`agility`,`resistance`";
$sql .= " FROM `$this->table` WHERE `id`=:id";
$param=[":id" => $id];


        // On exécuite la requête
         $req = $this->executeSql($sql, $param);
         // Si elle a échoue, on retourne un tabelau vide
         if ($req === false) return [];
         
         // ON transforme les lignes retournées par la requête en tableau d'objet
         $result = [];      // Tableau vide qui va accuillir les objets à retourner
         // ON parcours la requête
         while($ligne = $req->fetch(PDO::FETCH_ASSOC)) {
             // On crée et charge un objet player
             $player = new Player();
             $player->loadFromTab($ligne, $ligne["id"]);
             // On ajoute l'objet créé au résultat
             $result[] = $player;
         }
        
         return $result;  
    }
    
    private function loadFromTab($tab, $id = null) {
        // Rôle : charge les attributs d'un objet à partir des valeurs de $tab
        // Retour : néant
        // Paramètres :
        //  $tab : tableau indexé des champs  (les index sont des noms de champs, les valeurs la valeur à donner
        //  $id (optionnel) : si il est précisé, on remplit aussi l'id

        foreach($tab as $nom=>$valeur) $this->set($nom, $valeur);       // Set gérera les erreurs
        if ( ! is_null($id)) $this->id = $id;
    }
    
}
 /*  
        //verifie que le password correspond bien au pwd haché
        if (password_verify($pwd, PASSWORD_DEFAULT)) {
            return true;
        } else {
            echo "pseudo ou mot de passe incorrect";
            exit();
        }*/