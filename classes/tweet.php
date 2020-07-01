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
     */
    public function get_user_tweets($user){
        $sql = "SELECT * FROM tweet WHERE tweet.user = :user";
        $query = $this->connexion->prepare($sql);
        $query->execute(['user' => $user]);
        $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
        return $donnees;
    }

    /**
     * Récupère tout les tweets
     */
    public function get_tweets(){
        $sql = "SELECT * FROM tweet";
        $query = $this->connexion->prepare($sql);
        $query->execute();
        $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
        return $donnees;
    }

    /**
     * Récupère les tweets associés à un tag
     */
    public function get_tag_tweets($tag){
        $sql = "SELECT tweet.id, tweet.user, tweet.message, tweet.created_at FROM `tweet_has_tag` JOIN tags ON(tags.id = tweet_has_tag.tag_id) JOIN tweet ON(tweet.id = tweet_has_tag.tweet_id) WHERE tags.nom  = :tag";
        $query = $this->connexion->prepare($sql);
        $query->execute(['tag' => $tag]);
        $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
        return $donnees;
    }

    /**
     * Récupère les tweets associés à un user et un tag particulier. On prend en param l'utilisateur et l'id du tweet
     *
     */
    public function get_user_tag_tweet($user, $tag){
        $sql = "SELECT tweet.id, tweet.user, tweet.message, tweet.created_at FROM `tweet_has_tag` JOIN tweet ON(tweet.id = tweet_has_tag.tweet_id) JOIN tags ON(tags.id = tweet_has_tag.tag_id) WHERE tweet.user = :user AND tags.nom  = :tag ;";
        $query = $this->connexion->prepare($sql);
        $query->execute([
            'user' => $user,
            'tag' => $tag
        ]);
        $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
        return $donnees;
    }
}