<?php

class Historic extends Player{

    protected $table = "historic";
    protected $listeChamps = ["typeaction", "room", "opponent", "resultfight", "hp", "strength", "agility", "resistance","timeaction", "idplayer"];
    protected $listeLiens = ["idplayer" => "player" ];

    protected $valeurs = [];
    protected $liens = [];

    protected $id = 0;

    public function get($nomChamp) {
        // Rôle : retourner la valeur d'un champ
        // Retour : la valeur si c'est un champ simple, l'objet pointé si le champ est un lien
        // Paramètre :
        //    $nomChamp : nom du champ à récupérer

        // On doit traiter différemment les liens et les champs simples :
        // - les champs simples : on retoirne la valeur récupéréedans la table
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

    public function getLien($nomChamp) {
        // Rôle : retourner la valeur d'un champ (en supposant que le champ est simple)champ de typoe lien, on retourne l'objet pointé)
        // Retour : la valeur si c'est un champ simple, l'objet pointé si le champ est un lien
        // Paramètre :
        //    $nomChamp : nom du champ à récupérer

        if ( ! isset($this->liens[$nomChamp])) return new _model();


        $model = $this->liens[$nomChamp];
        // Si on n'a pas de clé primaire pour le lien, on retourne un objet vide
        if ( ! isset($this->valeurs[$nomChamp])) return new $model();
        // On vérifie si on a déjà l'objet et le bon
        if ( ! empty($this->liens[$nomChamp]) or $this->liens[$nomChamp]->id() != $this->valeurs[$nomChamp]) {
            // ONn doit charger l'objet
            $this->liens[$nomChamp] = new $model($this->valeurs[$nomChamp]);
        }
        // On retourne l'objet pointé
        return $this->liens[$nomChamp];
    }


}