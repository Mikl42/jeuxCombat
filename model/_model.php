<?php

/*
 * Classe mère pour les objets du modèle de données
 * 
 * Ce modèle s'applioque à un objet du MCD :
 *    - sans liens "multiples"
 *    - on ne s'occupe pas si les attributs sont du texet, les nombres, des dates
 *    - il y a une table physuique poiur implémeter dans la BDD l'objet du MCD
 *    - la clé primaire dans cette table physique es'apelle id et st un entier en auto-incrément
 */


class _model {
    
    // Attributs à valoriser obligatoirement dans les classes héritées (ne pas modifier)
    
    protected $table ;  // Nom de la table dans la base de donnéés
    protected $listeChamps = [ ];       // liste des champs du modèlé conceptuel : liste simple                          
    protected $listeLiens = [ ];        // liens du modèle conceptuel : nomChamp => objetPointé

    
    // Attributs fixes, remplis au cours de la vie de l'objet
    
    // Valeurs du modèle physique (trouvées dans la table, ou aà mettre das la table)
    // Pour les champs de type "objet", le tableau contient l'ID de l'objet pointé
    protected $valeurs = [];    // Dans ce tableau, on aura pour cahque champ (nom du champ en inddex), la valeur dans la base de données
    // Pour les champs de type objet, on a aussi besoin de l'objet lui-même : on les stocke dans $this->liens
    protected $liens = [];      // Dans ce tableau, on va stocker les objets liés (nom du champ lien en index)
    
    protected $id = 0;              // Id de l'objet (si chargé) (valeur de l'id)
    
    
    function __construct($id = null) {      
        // Rôle : le constructeur charge l'objt si on lui donne un id
        // Retour : néant
        // Paramètres :
        //      $id : facultatif ; id de l'objet à charger
        if ( ! is_null($id)) $this->loadById($id);
        
    }
    
    function id() {                                                             
        // Role : récupérer l'id 
        // Retour : id de l'objet ou 0
        // Paramètres : néant
        return $this->id;
    }
    
           
    function get($nomChamp) {
        // Rôle : retourner la valeur d'un champ
        // Retour : la valeur si c'est un champ simple, l'objet pointé si le champ est un lien
        // Paramètre : 
        //    $nomChamp : nom du champ à récupérer
        
        // On doit traiter différemment les liens et les champs simples :
        // - les champs simples : on retoirne la valeur récupéréedans la table (valeur physique)
        // - les champs de  type objet : on retourne l'objet précis pointé
        // 
        // 
        // PREMIER CAS : champs simple
        // c'est un champ simple si ce n'est pas un lien : c'est à dire si le champ n'est pas un index dans la table $this->listeLiens, cad l'index nomchamp n'exisye  
        
        if ( empty($this->listeLiens[$nomChamp])) {  
            // Ce n'est pas un lien, c'est un champ simple, direct
            return $this->getValue($nomChamp);
        } else {
            // c'est un lien
            return $this->getLien($nomChamp);
        }
        
    }
    
    
    function getValue($nomChamp) {
        // Rôle : retourner la valeur physique d'un champ 
        // Retour : la valeur si c'est un champ simple
        // Paramètre : 
        //    $nomChamp : nom du champ à récupérer  
        
        // Récupérer la valeur du champ dans $valeur, si elle existe (sinon, on retourne "")
        // La valeur existe si il y a une valeur dans la tableau $this->valeurs à l'index $nomChamp : $this->valeurs[$nomChamp]
        if (isset($this->valeurs[$nomChamp])) {
            // Il existe une valeur : on la retourne 
            return $this->valeurs[$nomChamp];
        } else {
            return  "";
        }
        
    }

    function getLien($nomChamp) {
        // Rôle : retourner la valeur d'un champ (en supposant que le champ est un lien) champ de typoe lien, on retourne l'objet pointé)
        // Retour : l'objet pointé (si on n'est pas sur un lien, un objet générique non chargé, si on a un lien, mais pas de valeur pour ce lien, un objet de la bonne classe non chargé)
        // Paramètre : 
        //    $nomChamp : nom du champ à récupérer 
        
        
        // On teste le cas ou le champ n'est pas un lien
        if ( ! isset($this->listeLiens[$nomChamp])) return new _model();  // pour bien envoyé un objet, comme indiqué dans le retour
        
        $model = $this->listeLiens[$nomChamp];       // Nom de la classe de l'objet pointé
        // Si on n'a pas de valeur pour la clé de l'objet pointé (si le champ n'est pas renseigné dans la BDD), on retourne un objet vide
        if ( empty($this->valeurs[$nomChamp])) return new $model(); 
        
        // On vérifie si on a déjà l'objet 
        // On va tester si on n'a pas d'onbjet pôinté valide (pour le charger)
        // Pas le bon : c'est soit on n'a pas de lien (lien est vide), soit l'id n'est pas celui stockés dans les valeurs
        if ( empty($this->liens[$nomChamp]) or $this->liens[$nomChamp]->id() != $this->valeurs[$nomChamp]) {
            // On ne l'a pas ou on n'a pas le bon : on le (re)charge
            $this->liens[$nomChamp] = new $model( $this->valeurs[$nomChamp] );
        }
        // On retourne l'objet pointé
        return $this->liens[$nomChamp];
    }  
    
    function set($nomChamp, $valeur) {
        // Rôle : changer la valeur d'un champ
        // Retour: true si accepté, false sinon
        // Paramètres :
        //      $nomChamp : champs que l'on veut changer
        //      $valeur : nouvelle valeur (pour un lien, on passe l'id)
        
        // On doit mettre à jour la valeur physique (ce qui correspond à ce qui est dans la table)
        // cad $this->valeurs
        
        // On vérifie si le champ existe
        if ( ! in_array($nomChamp, $this->listeChamps)) return false;
        
        $this->valeurs[$nomChamp] = $valeur;
        return true; 
    }
    
    function loadById($id) {
        // Rôle : charger l'objet depuis la base de données
        // Retour : true si on l'a trouvé, false sinon
        // Paramètres : 
        //      $id : id de l'objet à charger
        
        // Construit la requête SQL et le tableau des paramètres
        // Requête : SELECT `id`, `ch1`, `ch2, ... FROM `nomTable` WHERE `id` = :id
        $sql = "SELECT `id` ";
        // ON ajoute les champs, cad ,`nomchamp`   
        foreach($this->listeChamps as $nomChamp) $sql .= ", `$nomChamp`";
        // On finit le texte de la requête
        $sql .= " FROM `$this->table` WHERE `id` = :id";
        $param = [ ":id" => $id ];
        
        
        $req = $this->executeSql($sql, $param );
        if ($req === false) {
            $this->id = 0;
            return false;
        }

        
        // La requête est exécutée : on récupère la première ligne
        $ligne = $req->fetch(PDO::FETCH_ASSOC);
        // Si il n'y a pas de première ligne (pas d'objet pour $id)
        if ($ligne === false) {
            $this->id = 0;
            return false;
        }
        
        // On a bien un objet :
        $this->loadFromTab($ligne);
        $this->id = $id;

        return true;
        
    }
    
    
    function create() {
        // Rôle : créer dans la base de données l'objet courant
        // Retour : true si ok, false si echec
        // Paramètres : neant
        
        // Construit la requête SQL et le tableau des paramètres
        // INSERT into `table` SET `ch1` = :ch1, `ch2` = : ch2, ...
        $sql = "INSERT INTO `$this->table` SET ";
        $param = [];    // on prépare $param pour la méthode execute des requêtes PDO
        $liste = [];  // on prépare la liste des champs ( `champ` = :champ prépare la liste pour ensuite utiliser implode pour créer une cahine ou les éléments sont spéarés par un texte fixe
        // Pour chaque champ, on prépare le texte SQL et la valorisation pour $param
        foreach ($this->listeChamps as $nomChamp) {
            $liste[] = " `$nomChamp` = :$nomChamp ";
            $param[":$nomChamp"] = $this->getValue($nomChamp);
        }
        // Ajouter les éléments du tableau $liste à $sql en les spéarant par des virgules
        $sql .= implode(",", $liste);               
        
        // exécution de la requête
        $req = $this->executeSql($sql, $param);
        if ( $req === false) return false;
        
        // vérifier que l'on a bien créé une ligne (avec rowCount)
        if ( $req->rowCount() !== 1 ) {
            return false;
        }
        
        // On a crée une ligne (et une seule)
        // ON récupère l'id créé
        global $bdd;
        $this->id = $bdd->lastInsertId();
        return true;     
    }
    
    function update() {
        // Rôle : modifier dans la base de données l'objet courant
        // Retour : true si ok, false si echec
        // Paramètres : neant
        
        // Construit la requête SQL et le tableau des paramètres
        // UPDATE `table` SET `ch1` = :ch1, `ch2` = : ch2, ... WHERE `id` = :id
        $sql = "UPDATE `$this->table` SET ";
        $param = [];    // on prépare $param pour la méthode execute des requêtes PDO
        $liste = [];  // on prépare la liste des champs ( `champ` = :champ prépare la liste pour ensuite utiliser implode pour créer une cahine ou les éléments sont spéarés par un texte fixe
        // Pour chaque champ, on prépare le texte SQL et la valorisation pour $param
        foreach ($this->listeChamps as $nomChamp) {
            $liste[] = " `$nomChamp` = :$nomChamp ";
            $param[":$nomChamp"] = $this->getValue($nomChamp);
        }
        // Ajouter les éléments du tableau $liste à $sql en les séparant par des virgules
        $sql .= implode(",", $liste);
        // Ajouter la clause WHERE
        $sql .= " WHERE `id` = :id";
        $param[":id"] = $this->id;    
        
        // exécution de la requête
        if ( ! $this->executeSql($sql, $param)) return false;

        return true;     
    }    
    
    function delete() {
        // Rôle : supprimer dans la base de données l'objet courant
        // Retour : true si ok, false si echec
        // Paramètres : neant
        
        // Construit la requête SQL et le tableau des paramètres
        $sql = "DELETE FROM `$this->table` SET WHERE `id` = :id";
        $param[":id"] = $this->id;    
        
        // exécution de la requête
        if ( ! $this->executeSql($sql, $param)) return false;
        return true;     
    }     
    
    function executeSql($sql, $param = []) {
        // Rôle : prépare et exécute une requête dans la base de données
        // Retour : false en cas d'échec, un objet requête PDO exécuté sinon
        // Param :
        //      $sql : texte de la requête
        //      $param : tableau de valorisation de paramètres :XXX inclus dans le texte de la requête
        
        // récupération du connecteur à la base de données (stocké en varaible globale dans init)
        global $bdd;
        // Préparer une requête
        $req = $bdd->prepare($sql);
        // Execute la requête
        if ( ! $req->execute($param)) {
            // Ce n'est pas un cas qui peut se produire en exploitation
            echo "Erreur sur requête SQL : $sql<br>";
            print_r($param);
            return false;
        }
        
        return $req;
        
    }
    
    function loadFromTab($tab, $id = null) {
        // Rôle : charge les attributs d'un objet à partir des valeurs de $tab
        // Retour : néant
        // Paramètres : 
        //  $tab : tableau indexé des champs  (les index sont des noms de champs, les valeurs la valeur à donner
        //  $id (optionnel) : si il est précisé, on remplit aussi l'id
        
        foreach($tab as $nom=>$valeur) $this->set($nom, $valeur);       // Set gérera les erreurs
        if ( ! is_null($id)) $this->id = $id;
    }
    
    
    function getAllValeurs() {
        // Rôle : retourner un tableau de toutes les valeurs
        // Retour : tableau indexé : [ nomChampp => valeurChamp, ... ]
        // paramètres : néant        
        return $this->valeurs;
    }
    
    function loadByCritere($criteres) {
        // Rôle : charger l'objet courant qui corresponds aux critères (si il on en a plusieurs, on charge le prmeir)
        // Retour : true si on trouve, false sinon
        // Paramètres :
        //      $criteres : tableau de critères de recherche : [ nomChamp => valeur_a_chercher, ....]

                
        // Construit la requête SQL et le tableau des paramètres
        // Requête : SELECT `id`, `ch1`, `ch2, ... FROM `nomTable` WHERE  nomChamp1 = valeurCherchéeCh1 AND nomChamp2 = valeurCherchéeCh2
        $sql = "SELECT `id` ";
        // ON ajoute les champs, cad ,`nomchamp`   
        foreach($this->listeChamps as $nomChamp) $sql .= ", `$nomChamp`";
        // On finit le texte de la requête
        $sql .= " FROM `$this->table` WHERE ";
        
        
       // ON construit les conditions
        $listeCond = [];        // On passe par une liste des condistion,son les séparera par AND esnsuite
        $param = [];
        foreach ($criteres as $nomChamp => $valeurCherchee) {
            $listeCond[] = " `$nomChamp` = :$nomChamp";
            $param[":$nomChamp"] = $valeurCherchee;
        }
        $sql .= implode(" AND ", $listeCond);
  
        $req = $this->executeSql($sql, $param );
        if ($req === false) {
            $this->id = 0;
            return false;
        }

        
        // La requête est exécutée : on récupère la première ligne
        $ligne = $req->fetch(PDO::FETCH_ASSOC);
        // Si il n'y a pas de première ligne (pas d'objet pour $id)
        if ($ligne === false) {
            $this->id = 0;
            return false;
        }
        
        // On a bien un objet :
        $this->loadFromTab($ligne);
        $this->id = $ligne["id"];

        return true;
        
    }

    function loadFromPost($tab){
        foreach ($tab as $index => $value) {
            $this->set($index, $value);
        }
    }
}
