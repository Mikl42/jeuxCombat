<?php


class Player
{
    protected $table = "player";
    protected $listeChamps = ["hp", "strength", "agility", "resistance", "pseudo", "pwd", "room"];

    protected $valeurs = [];
    protected $id = 0;

    function __construct($id = null){
    if (! is_null($id)) $this->loadById($id);
    }

    /**
     * @param $champ
     * @return mixed|string
     */
    public function get($champ){
        if(isset($this->valeurs[$champ])){
            return $this->valeurs[$champ];
        }else{
            return "";
        }
    }

    /**
     * @return int
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @param $champ
     * @param $valeur
     * @return bool
     */
    public function set($champ, $valeur){
        if(!in_array($champ, $this->listeChamps)){
            return false;
        }
        $this->valeurs[$champ] = $valeur;
        return true;
    }

    /**
     * @return bool
     */
    public function create(){
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
    public function update(){
        // Role : modifie l objet
        // Paramètre : Neant
        // Retour : True si tout est ok , false sinon

        // Construction de la requete SQL
        $sql = "UPDATE `$this->table` SET ";
        $param = [];
        $liste = [];
        foreach ($this->listeChamps as $champ){
            $liste[] = "`$champ` = :$champ";
            $param[":$champ"] = $this->get($champ);
        }
        $sql .= implode(", ", $liste);
        $sql .= " WHERE `id` = :id";
        $param[':id'] = $this->id;
        if(!$this->executeSql($sql, $param)) return false;
        return true;
    }

    /**
     * @return bool
     */
    public function delete(){
        // Role : Supprime l objet de la base de donnée
        // Parametre : Néant
        // Retour : true si tout es ok, false sinon

        // Construction de la requete SQL
        $sql = "DELETE FROM `$this->table` WHERE `id` = :id";
        $param = [":id" => $this->id];
        if (!$this->executeSql($sql, $param)) return false;
        return true;
    }
    /**
     * @param $sql
     * @param array $param
     * @return false|PDOStatement
     */
    private function executeSql($sql, $param = []){
        // Preparer et executer la requete
        // Recupere le connecteur a la base de donnée
        global $bdd;
        // Prepare la requete
        $req = $bdd->prepare($sql);
        // Test si la requete a rencontré une erreur on retourne false
        if(!$req->execute($param)){
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
    public function loadById($id)
    {
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
        if($ligne === false){
            $this->id = 0;
            return false;
        }
        $this->loadFromTab($ligne, $id);

        return true;
    }

    /**
     * @param $tab
     */
    public function loadFromPost($tab){
        foreach ($tab as $index => $value) {
            $this->set($index, $value);
        }
    }
    
    /**
     * @param $tab
     * @param null $id
     */
    protected function loadFromTab($tab, $id = null) {
        // Rôle : charge les attributs d'un objet à partir des valeurs de $tab
        // Retour : néant
        // Paramètres :
        //  $tab : tableau indexé des champs  (les index sont des noms de champs, les valeurs la valeur à donner
        //  $id (optionnel) : si il est précisé, on remplit aussi l'id

        foreach($tab as $nom=>$valeur) $this->set($nom, $valeur);       // Set gérera les erreurs
        if ( ! is_null($id)) $this->id = $id;
    }

    /**
     * @return bool
     */
    public function pseudoExist(){
        // Role : verifie si le pseudo existe déjà dans la base de donnée
        // Paramètre : Néant
        // Retour : true si le pseudo existe false sinon

        // Construction de la requete SQL
        $sql = "SELECT `pseudo` FROM `$this->table` WHERE `pseudo` = :pseudo";
        $param = [":pseudo" => $this->get("pseudo")];

        // Prepare et executer la requete
        $req = $this->executeSql($sql, $param);
        if(!$req) {
            return false;
        }
        $ligne = $req->fetch(PDO::FETCH_ASSOC);
        var_dump($ligne);
        // Si $req contient une ou plusieur ligne alors aucun pseudo existe
        if($ligne['pseudo'] != $this->get('pseudo')){
            return false;
        }
        // Sinon un pseudo existe on retourn true
        return true;
    }

    /**
     * @return false|Player
     */
    public function playerExist(){
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
        if($ligne === false) { return false;}
        // on a bien une ligne
        // on verifie que les mot de passe corresponde
        if (!password_verify($this->get("pwd"), $ligne['pwd'])){
            return false;
        }
        return new Player($ligne['id']);

    }

    /**
     * @return array|false
     */
    public function listePlayerSameRoom(){
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

        if(!$req){return false;};
        // On part d'un tableau vide qui récupèrera les joueurs
        $listePlayer = [];
        // Tant qu'il y a des joueurs on les ajoute au tableau
        while($ligne = $req->fetch(PDO::FETCH_ASSOC)){
            $adverssaire = new Player();
            $adverssaire->loadFromTab($ligne, $ligne['id']);
            $listePlayer[] = $adverssaire;
        }
        return $listePlayer;
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

    public function loadHistoric(){
        // Role : Récupère tout l' historiques du joueur
        // Paramètre : Néant
        // Retour : tableau d'objet Historic
        $sql = "SELECT * FROM `historic` WHERE `idplayer` = :idPlayer";
        $param = [":idPlayer" => 29];
        $req = $this->executeSql($sql, $param);

        if ($req === false)return false;

        $listHistoric = [];

        while($ligne = $req->fetch(PDO::FETCH_ASSOC)){
            $historic = new Historic();
            $historic->loadFromTab($ligne);
            $listHistoric[] = $historic;
        }
        return $listHistoric;
    }

    public function attack($idAdversaire){
        // Role : déreoulement d'une attaque
        // Paramètre :
        //      - $force = force donnée
        //      - $idAversaire = id du joueur attaqué
        // Retour :
        //      - "EQUAL" = pour une égalité
        //      - "WIN" = pour combat gagné
        //      - "LOOSE" = pour combat perdu

        // On recupère notre adversaire et on le charge
        $adversaire = new Player($idAdversaire);
        // donc adversaire subit une attaque
        // Si on perd le combat : on perd 1 point de vie.
        if($adversaire->subirAttack($this->get("strength"), $this->getId()) === "WIN"){
            $this->set("hp", $this->get("hp") - 1);
            $this->update();
            return "LOOSE";
        }
        // Si l’adversaire esquive et que l’on a 10 points de force ou plus, un point de force devient un point de résistance.
        if($adversaire->subirAttack($force, $this->getId()) === "ESQUIVE") {
            if($this->get("strength") >= 10){
                $this->set("strength", $this->get("strength") - 1);
                $this->set("resistance", $this->get("resistance") + 1);
                $this->update();
                return "EQUAL";
            }else{
                return "EQUAL";
            }
        }
        // Si on gagne le combat, on récupère un point d'agilité (ça motive ! ), ou un point de vie si on a déjà 15 points d'agilité.
        // on recupère ses point de vie avant le combat
        $stockHpAdversaire = $adversaire->get("hp");
        if($adversaire->subirAttack($force, $this->getId()) === "LOOSE") {
            if($this->get("agility") < 15){
                $this->set("agility", $this->get("agility") + 1);
                $this->update();
                return "WIN";
            }else{
                $this->set("hp", $this->get("hp") + 1);
                $this->update();
                return "WIN";
            }
            if ($adversaire->get("hp") === 0){
                $this->set("hp", $this->get("hp") + $stockHpAdversaire);
                $this->update();
                return "WIN";
            }
        }
    }

    public function subirAttack($force, $idAttaquant){
        // Role : Subir une attaque
        // Paramètre:
        //      - $force = force de l'attaque donnée
        //      - $idAttaquant = l'id du joueur qui attaque
        // Retour:
        //      - "E" = pour une esquive
        //      - "WIN" = pour combat gagné
        //      - "LOOSE" = pour combat perdu

        // On recupere l'attaquant et on le charge
        $attaquant = new Player ($idAttaquant);
        //Si notre agilité dépasse la force d'attaque d'au moins 3 points, on esquive. Personne n'a alors gagné ou perdu le combat,
        //et on perd 1 point d'agilité.
        if ($this->get("agility") >= $force + 3){
            $this->set("agility", $this->get("agility") - 1);
            $this->update();
            return "ESQUIVE";
        }
        // Si notre force est supérieure strictement à celle de l'attaque, on riposte : voir ci-après la riposte. On gagne le combat et
        //un point de vie si on gagne la riposte, on perd le combat et 2 points de vie si on perd la riposte.
        if($this->get("strength") > $force){
            if($this->attack($this->get("strength"), $idAttaquant) === "WIN"){
                // Si WIN on gagne le combat et + 1 pt de vie
                $this->set("hp", $this->get("hp") + 1);
                $this->update();
                return "WIN";
            }else if ($this->attack($this->get("strength"), $idAttaquant) === "LOOSE"){
               //si on perd le combat et  - 2 points de vie
                $this->set("hp", $this->get("hp") - 2);
                $this->update();
                return "LOOSE";
            }else if($this->attack($this->get("strength"), $idAttaquant) === "ESQUIVE"){
                // Si combat egaliter on perd 1 point d agiliter
                $this->set("agility", $this->get("agility") - 1);
                $this->update();
                return "EQUAL";
            }
        }
        // Sinon, on se défend : si notre résistance est supérieure ou égale à la force de l'attaque, on gagne le combat, si elle est
        //inférieure, on le perd et on perd en points de vie la différence entre notre résistance et la force de l'attaque
        if($this->get("resistance") >= $force){
            return "WIN";
        }else if($this->get("resistance") < $force){
            // On recupère la différence entre la resistance et la force dans une variable
            $pvASoustraire = $this->get("resistance") - $force;
            // on la soustrait au point de vie
            $this->set("hp", $this->get("hp") - $pvASoustraire);
            $this->update();
            return "LOOSE";
         }
        return "EQUAL";
    }
}
