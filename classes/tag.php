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
     * @return void
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
     * @return void
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
     * @return void
     */
    public function get_tweet_tags($id){
        $sql = "SELECT tags.nom FROM `tweet_has_tag` JOIN tags ON(tags.id = tweet_has_tag.tag_id) WHERE tweet_has_tag.tweet_id = :id";
        $query = $this->connexion->prepare($sql);
        $query->execute(['id' => $id]);
        $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
        return $donnees;
    }

    public function create_tag(){
        $sql = " INSERT INTO tags SET nom = :nom";
        $query = $this->connexion->prepare($sql);

        // Protection contre les injections
        $this->nom=htmlspecialchars(strip_tags($this->nom));
        
        // Ajout des données protégées
        $query->bindParam(":nom", $this->nom);

        // Exécution de la requête
        if($query->execute()){
            return true;
        }
        return false;
    }

    /**
     * Lie le tag à un tweet
     */
    public function create_junction($tweet_id, $tag_id){
        $sql = " INSERT INTO tweet_has_tag SET tweet_id = :tweet_id, tag_id = :tag_id";
        $query = $this->connexion->prepare($sql);
        $query->execute([
            'tweet_id' => $tweet_id,
            'tag_id' => $tag_id
            ]);
        // Exécution de la requête
        if($query->execute()){
            return true;
        }
        return false;
    }
}