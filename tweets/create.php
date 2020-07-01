<?php 

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
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

//----------------------------------> GESTION ERREUR <-------------------------------------------

    //On va maintenant gerer les différentes erreurs. Je choisis de bien séparer pour pouvoir donner un message d'erreur précis
    if(!empty($inputs->user) && !empty($inputs->message) && !empty($inputs->tags) ){
        $input_user = $inputs->user;
        $input_message = $inputs->message;
        $input_tags = $inputs->tags;
        $user_regex = "/^[a-z0-9-]{4,16}$/i";
        $tags_regex = "/(#\w+)/i";

        //Vérification du format des donnees pour le nom d'utilisateur
        if(preg_match($user_regex , $input_user) == 0 ){
            http_response_code(409);
            echo json_encode("Format de nom incorrecte");
           return;
        }
        //Vérification du format des donnees pour le message
        if(strlen($input_message) > 1000 ){
            http_response_code(409);
            echo json_encode("Message trop long! Il ne doit pas dépasser 1000 charactères!");
           return;
        }
        foreach($input_tags as $input_tag){
            if(preg_match($tags_regex, $input_tag->nom) == 0){
                http_response_code(409);
                echo json_encode("Format de tag incorrecte");
                return;
            }
        }

//-------------------------------------------> CREATION TWEET ET TAG <-------------------------------

        //Par cette chaleur il faut hydrater notre petit tweet
        // $tweet->user = $input_user;
        // $tweet->message = $input_message;

        // //Maintenant que tout est OK on s'occupe du tweet en premier et on le créer
        // if($tweet->create_tweet()){
        //     // Ici la création a fonctionné
        //     http_response_code(201);
        //     echo json_encode(["message" => "Le tweet a été ajouté"]);
        // }else{
        //     // Ici la création n'a pas fonctionné
        //     http_response_code(503);
        //     echo json_encode(["message" => "L'ajout n'a pas été effectué"]);         
        // }

        //Finalement occupons nous des tags
     
        foreach($input_tags as $input_tag){

            //On regarde si le tag existe dans notre BDD
            if(empty($tag->get_tag($input_tag->nom))){
                
                //Si il existe on l'ajoute
                $tag->nom = $input_tag->nom;

                //if($tag->create_tag()){
                //    // Ici la création a fonctionné
                //    http_response_code(201);
                //}else{
                //    // Ici la création n'a pas fonctionné
                //    http_response_code(503);
                //    echo json_encode(["message" => "L'ajout du tag n'a pas été effectué"]);         
                //}
                
            }
            
        }
        die;
    }else{
        //On gère le cas ou la requète comporte une erreur
        echo json_encode("Une ou plusieurs informations sont manquante!");
        http_response_code(409);
    }
}else{
   //On gère le cas ou la demande est faite avec un méthode non autorisée
   http_response_code(405);
   echo json_encode(array("message"=>"Méthode non autorisée!")); 
}


?>