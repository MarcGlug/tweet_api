<?php 

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    
    include_once('../config/Database.php');
    include_once('../classes/Tweet.php');
    include_once('../classes/Tag.php');

    // Instancie la DB
    $database = new Database();
    $db = $database->getConnection();

    // Instancie Tweet et Tag
    $tweet = new Tweet($db);
    $tag = new Tag($db);

    //On récupère les inputs
    $inputs = json_decode(file_get_contents("php://input"));
    $input_user = $inputs->user;
    $input_tag = $inputs->tag;

    //On gère les différents cas en fonction des demandes
    if(!empty($input_user)){
        if(!empty($input_tag)){
            //Ici on récupère en fonction d'un utilisateur ET d'un tag précis!
            $donnees = $tweet->get_user_tag_tweet($input_user, $input_tag);
        }else{
            //Ici on récupère en fonction d'un utilisateur
            $donnees = $tweet->get_user_tweets($input_user);
        }
    }elseif(!empty($input_tag)){
        //Ici on récupère en fonction d'un tag. 
        $donnees = $tweet->get_tag_tweets($input_tag);
    }else{
        //Ici on retourne TOUT! #jaipasdoursindansmespoches
         $donnees = $tweet->get_tweets();
    }
    
    //On construit le JSON
    // Verification qu'il y a au moins un tweet
    if(count($donnees) > 0){
        $tweetsArray['tweets'] = [];

        foreach($donnees as $donnee){
            $id = $donnee['id'];//id du tweet
            
            //On récupère les tags associés au tweet
            $tags = $tag->get_tweet_tags($id);
            $value = [
                'id' => $donnee['id'],
                'user' => $donnee['user'],
                'message' => $donnee['message'], 
                'tags' => $tags,
                'created_at' => $donnee['created_at']
            ];
            array_push($tweetsArray['tweets'],$value);
        }

        echo json_encode($tweetsArray);
        http_response_code(200);

    }else{
        //On gère le cas ou le résultat est vide
        echo json_encode("Aucun résultat trouvé");
    }

}else{
    //On gère le cas ou la demande est faite avec un méthode non autorisée
    http_response_code(405);
    echo json_encode(array("message"=>"Méthode non autorisée!"));
}