<?php

class Player {

    protected $table = "player";
    protected $listeChamps = ["hp", "strength", "agility", "resistance", "pseudo", "pwd", "room"];
    protected $valeurs = [];
    protected $id = 0;
    protected $listeLiens = [];

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
        // on pars de tableau vide pour les paramètres et la liste des champs de la requete
        $param = [];
        $liste = [];
        // On parcourt la liste des champs en ajoutant les champs et paramètre dans les tableaux
        foreach ($this->listeChamps as $champ) {
            $liste[] = "`$champ` = :$champ";
            $param[":$champ"] = $this->get($champ);
        }
        // On force les valeurs de hp a 100 et room a 0 ( car a la creation ce sont les valeurs par défault )
        $param["hp"] = 100;
        $param["room"] = 0;


        $sql .= implode(", ", $liste);

        // On prepare et execute la requete avec la fonction executeSql()
        $req = $this->executeSql($sql, $param);
        // Si $req retourne false alors ont a eu une erreur , on retourne false
        if ($req === false) {
            echo "Il y a une erreur dans la requête $sql (paramètres : $param)";
            return false;
        }
        // La requete c'est bien éxécuté
        return true;
    }

    /**
     * @return bool
     */
    public function update() {
        // Role : modifie l objet
        // Paramètre : Neant
        // Retour : True si tout est ok , false sinon
        // Construction de la requete SQL
        $sql = "UPDATE `$this->table` SET ";
        $param = [];
        $liste = [];
        foreach ($this->listeChamps as $champ) {
            $liste[] = "`$champ` = :$champ";
            $param[":$champ"] = $this->get($champ);
        }
        $sql .= implode(", ", $liste);
        $sql .= " WHERE `id` = :id";
        $param[':id'] = $this->id;
        if (!$this->executeSql($sql, $param))
            return false;
        return true;
    }

    /**
     * @return bool
     */
    public function delete() {
        // Role : Supprime l objet de la base de donnée
        // Parametre : Néant
        // Retour : true si tout es ok, false sinon
        // Construction de la requete SQL
        $sql = "DELETE FROM `$this->table` WHERE `id` = :id";
        $param = [":id" => $this->id];
        if (!$this->executeSql($sql, $param))
            return false;
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
        // La requete a bien été executer on renvoie sont resultat
        return $req;
    }

    /**
     * @param $id
     * @return bool
     */
    public function loadById($id) {
        // Role : charge l'objet par son id
        // Paramètre : $id : Id de l'objet voulu
        // Retour : true si chargé , false sinon
        // Construction de la requete
        $sql = "SELECT * FROM `$this->table` WHERE `id` = :id";
        $param = [":id" => $id];

        // Exécute la requete
        $req = $this->executeSql($sql, $param);

        if ($req === false) {
            $this->id = 0;
            return false;
        }
        // requete executer
        $ligne = $req->fetch(PDO::FETCH_ASSOC);
        if ($ligne === false) {
            $this->id = 0;
            return false;
        }
        $this->loadFromTab($ligne, $id);

        return true;
    }

    /**
     * @param $tab
     */
    public function loadFromPost($tab) {
        foreach ($tab as $index => $value) {
            $this->set($index, $value);
        }
    }

    /**
     * @param $tab
     * @param null $id
     */
    private function loadFromTab($tab, $id = null) {
        // Rôle : charge les attributs d'un objet à partir des valeurs de $tab
        // Retour : néant
        // Paramètres :
        //  $tab : tableau indexé des champs  (les index sont des noms de champs, les valeurs la valeur à donner
        //  $id (optionnel) : si il est précisé, on remplit aussi l'id

        foreach ($tab as $nom => $valeur)
            $this->set($nom, $valeur);       // Set gérera les erreurs
        if (!is_null($id))
            $this->id = $id;
    }

    public function pseudoExist() {
        // Role : verifie si le pseudo existe déjà dans la base de donnée
        // Paramètre : Néant
        // Retour : true si le pseudo existe false sinon
        // Construction de la requete SQL
        $sql = "SELECT `pseudo` FROM `$this->table` WHERE `pseudo` = :pseudo";
        $param = [":pseudo" => $this->get("pseudo")];

        // Prepare et executer la requete
        $req = $this->executeSql($sql, $param);
        if (!$req) {
            return false;
        }
        $ligne = $req->fetch(PDO::FETCH_ASSOC);
        var_dump($ligne);
        // Si $req contient une ou plusieur ligne alors aucun pseudo existe
        if ($ligne['pseudo'] != $this->get('pseudo')) {
            return false;
        }
        // Sinon un pseudo existe on retourn true
        return true;
    }

    public function playerExist() {
        // Role : vérifie si le player existe en bdd
        // Paramètre : Néant
        // Retour :
        //      - un objet player chargé

        $sql = "SELECT `id`, `pwd` FROM `$this->table` WHERE `pseudo` = :pseudo";
        $param = [":pseudo" => $this->get("pseudo")];

        // preparer et executer la requete
        $req = $this->executeSql($sql, $param);
        // on verifie si on a une ligne
        $ligne = $req->fetch(PDO::FETCH_ASSOC);
        if ($ligne === false) {
            return false;
        }
        // on a bien une ligne
        // on verifie que les mot de passe corresponde
        if (!password_verify($this->get("pwd"), $ligne['pwd'])) {
            return false;
        }
        return new Player($ligne['id']);
    }

    public function listePlayerSameRoom() {
        // Role : Récupère la liste de tout les joueurs qui sont dans la meme room que le joueur appelants
        // Paramètre : Néant
        // Retour : Liste
        // Construction de la requete de récupération des players
        $sql = "SELECT * FROM `player` WHERE `room` = :room AND `pseudo` != :pseudo";
        $param = [
            ":room" => $this->get("room"),
            ":pseudo" => $this->get('pseudo')
        ];

        // Prepare et execute la requete
        $req = $this->executeSql($sql, $param);

        if (!$req) {
            return false;
        }
        // On part d'un tableau vide qui récupèrera les joueurs
        $listePlayer = [];
        // Tant qu'il y a des joueurs on les ajoute au tableau
        while ($ligne = $req->fetch(PDO::FETCH_ASSOC)) {
            $adverssaire = new Player();
            $adverssaire->loadFromTab($ligne, $ligne['id']);
            $listePlayer[] = $adverssaire;
        }
        return $listePlayer;
    }

    function augmenterForce() {
        //role augmenter la force
        //parametre neant
        //return un tableau des erreurs rencontré si celui ci n'est pas vid
        //si agility est strictement superieur a 3 et la force <= a 14 alors et resistance strictement sup a 1.
        // Tableau vide pour recupérer les erreurs

        if (($this->get('agility') > 3) && ($this->get('strength') <= 14) && ($this->get('resistance') > 1)) {
            //agility perd 3 point, force gagne 1 pt et resistance perd 1pt
            $this->set("agility", $this->get('agility') - 3);
            $this->set("strength", $this->get('strength') + 1);
            $this->set("resistance", $this->get('resistance') - 1);

//appel la methode qui mettra a jour les stat
            $this->update();
            return;
        }
        //sinon si point d'agi insuffisant
        if ($this->get('agility') <= 3) {
            $message = "agilité insuffisante";
            //retour au template plateauJeu en passant par ajax pour la MAJ des stat
            return $message;
        }
        //sinon si force deja trop elevée
        if ($this->get('strength') >= 15) {
            $message = "point de force au maximum";
            //retour au template plateauJeu en passant par ajax pour la MAJ des stat
            return $message;
        }
//sinon si resistance insuffisante
        if ($this->get('resistance') <= 1) {
            $message = "point de resistance insuffisant";
            //retour au template plateauJeu en passant par ajax pour la MAJ des stat
            return $message;
        }
    }

    function augmenterResistance() {
        //role : augmente la resistance
        //parametre neant
        //retour :
        //si agility est strictement superieur a 3 et la force <= a 14 alors et resistance strictement sup a 1.
        if (($this->get('agility') > 3) && ($this->get('resistance') <= 14) && ($this->get('strength') > 1)) {
            //agility perd 3 point, force gagne 1 pt et resistance perd 1pt
            $this->set("agility", $this->get('agility') - 3);
            $this->set("strength", $this->get('strength') - 1);
            $this->set("resistance", $this->get('resistance') + 1);

            //appel la methode qui mettra a jour les stat
            $this->update();
            return;
        }
        //sinon si point d'agi insuffisant
        if ($this->get('agility') <= 3) {
            $message = "agilité insuffisante";
            return $message;
            //retour au template plateauJeu en passant par ajax pour la MAJ des stat            
        }
//sinon si force deja trop elevée
        if ($this->get('strength') <= 1) {
            $message = "point de force au maximum";
            return $message;
            //retour au template plateauJeu en passant par ajax pour la MAJ des stat            
        }
//sinon si resistance insuffisante
        if ($this->get('resistance') >= 15) {
            $message = "point de resistance insuffisant";
            return $message;
            //retour au template plateauJeu en passant par ajax pour la MAJ des stat            
        }
    }

    function avancer() {
        //role : avancer dans la salle suivante
        //parametre : neant
        //retuour
        //recupere l'agilité
        $agility = $this->get("agility");
        //recupere la salle + 1 
        $nextRoom = $this->get("room") + 1;

        //si agility est superieur ou egal a nextroom
        if (($agility >= $nextRoom) AND ($nextRoom <= 10)) {
            //on avance dans la room suivante = room +1
            $this->set("room", $nextRoom);
            $this->set("agility", $this->get('agility') - $nextRoom);
            //appel de la methode qui met a jour les stats
            $this->update();
            return;
        }
    }

    function reculer() {
        //role reculer a la salle precedente
        //parametre neant
        //retour neant

        if ($this->get("room") >= 1) {
            $this->set("room", $this->get('room') - 1);

            $this->update();
            return;
        }
    }
}