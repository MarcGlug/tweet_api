<?php 

class Tweet{
    //Connexion
    private $connexion;

    //Properties
    public $id;
    public $user;
    public $message;
    public $created_at;

     /**
     * Constructeur avec $db pour la connexion à la base de données
     *
     * @param $db
     */
    public function __construct($db){
        $this->connexion = $db;
    }

    /**
     * Récupère les tweets d'un utilisateur
     * @return void
     */
    public function get_user_tweets($user, $limit){
        $sql = "SELECT * FROM tweet WHERE tweet.user = :user ORDER BY created_at DESC LIMIT ".$limit;
        $query = $this->connexion->prepare($sql);
        $query->execute(['user' => $user]);
        $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
        return $donnees;
    }

    /**
     * Récupère tout les tweets
     * @return void
     */
    public function get_tweets($limit){
        $sql = "SELECT * FROM tweet ORDER BY created_at DESC LIMIT ".$limit;
        $query = $this->connexion->prepare($sql);
        $query->execute();
        $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
        return $donnees;
    }

    /**
     * Récupère les tweets associés à un tag
     * @return void
     */
    public function get_tag_tweets($tag, $limit){
        $sql = "SELECT tweet.id, tweet.user, tweet.message, tweet.created_at FROM `tweet_has_tag` JOIN tags ON(tags.id = tweet_has_tag.tag_id) JOIN tweet ON(tweet.id = tweet_has_tag.tweet_id) WHERE tags.nom  = :tag ORDER BY created_at DESC LIMIT ".$limit;
        $query = $this->connexion->prepare($sql);
        $query->execute([
            'tag' => $tag]);
        $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
        return $donnees;
    }

    /**
     * Récupère les tweets associés à un user et un tag particulier. On prend en param l'utilisateur et l'id du tweet
     *@return void
     */
    public function get_user_tag_tweet($user, $tag, $limit){
        $sql = "SELECT tweet.id, tweet.user, tweet.message, tweet.created_at FROM `tweet_has_tag` JOIN tweet ON(tweet.id = tweet_has_tag.tweet_id) JOIN tags ON(tags.id = tweet_has_tag.tag_id) WHERE tweet.user = :user AND tags.nom  = :tag ORDER BY created_at DESC LIMIT ".$limit;
        $query = $this->connexion->prepare($sql);
        $query->execute([
            'user' => $user,
            'tag' => $tag
        ]);
        $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
        return $donnees;
    }

    public function create_tweet(){
        $sql = " INSERT INTO tweet SET user = :user, message = :message";
        $query = $this->connexion->prepare($sql);

        // Protection contre les injections
        $this->user=htmlspecialchars(strip_tags($this->user));
        $this->message=htmlspecialchars(strip_tags($this->message));

        // Ajout des données protégées
        $query->bindParam(":user", $this->user);
        $query->bindParam(":message", $this->message);

        // Exécution de la requête
        if($query->execute()){
            return true;
        }
        return false;
    }

}