<?php 

class Tag{
    //Connexion
    private $connexion;

    //Properties
    public $id;
    public $nom;

    /**
     * Constructeur avec $db pour la connexion à la base de données
     *
     * @param $db
     */
    public function __construct($db){
        $this->connexion = $db;
    }
    /**
     * Récupère tous les tags
     */
    public function get_tags(){
        $sql = "SELECT * FROM tags";
        $query = $this->connexion->prepare($sql);
        $query->execute();
        $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
        return $donnees;
    }

    /**
     * Récupère un tag en particulier
     */
    public function get_tag($nom){
        $sql = "SELECT tags.id FROM tags WHERE tags.nom = :nom";
        $query = $this->connexion->prepare($sql);
        $query->execute(['nom' => $nom]);
        $donnee = $query->fetch(PDO::FETCH_ASSOC);
        return $donnee;
    }

    /**
     * Récupère les tags associés à un tweet
     */
    public function get_tweet_tags($id){
        $sql = "SELECT tags.nom FROM `tweet_has_tag` JOIN tags ON(tags.id = tweet_has_tag.tag_id) WHERE tweet_has_tag.tweet_id = :id";
        $query = $this->connexion->prepare($sql);
        $query->execute(['id' => $id]);
        $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
        return $donnees;
    }
}