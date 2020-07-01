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
    
    //On vérifie qu'on récupère bien quelque chose et qu'il n'y a pas d'erreur dans les inputs
    if(isset($inputs)){
        $input_user = $inputs->user;
        $input_tag = $inputs->tag;
        $input_page = $inputs->page;
        $input_per_page = $inputs->per_page;

        //On affecte des valeurs par défaut si page et per_page ne sont pas fournis
        if(empty($input_page)){$input_page = 1;}
        if(empty($input_per_page)){$input_per_page = 25;}
        $limit = $input_page * $input_per_page;

        //On gère les différents cas en fonction des demandes
        if(!empty($input_user)){
            if(!empty($input_tag)){
                //Ici on récupère en fonction d'un utilisateur ET d'un tag précis !
                $donnees = $tweet->get_user_tag_tweet($input_user, $input_tag, $limit);
            }else{
                //Ici on récupère en fonction d'un utilisateur
                $donnees = $tweet->get_user_tweets($input_user, $limit);
            }
        }elseif(!empty($input_tag)){
            //Ici on récupère en fonction d'un tag. 
            $donnees = $tweet->get_tag_tweets($input_tag, $limit);
        }else{
            //Ici on retourne TOUT! #jaipasdoursindansmespoches
             $donnees = $tweet->get_tweets($limit);
        }
        //On construit le JSON
        //On vérifie qu'au moins un tweet existe
        if(count($donnees) > 0){
            // foreach($donnees as $donnee){
            //     $i = 0;
            //     $id = $donnee['id'];//id du tweet
            //     //On récupère les tags associés au tweet
            //     $tags = $tag->get_tweet_tags($id);
            //     $value = [
            //         'id' => $donnee['id'],
            //         'user' => $donnee['user'],
            //         'message' => $donnee['message'], 
            //         'tags' => $tags,
            //         'created_at' => $donnee['created_at']
            //     ];
            //     array_push($tweetsArray['tweets'], $value);
            // }
            $page = 1;
            $per_page = 1;
            for ($x=0; $x < count($donnees); $x++) { 

                $tweetsArray['tweets'][$page][$per_page] = [];

                $id = $donnees[$x]['id'];//id du tweet
                 //On récupère les tags associés au tweet
                 $tags = $tag->get_tweet_tags($id);
                 $value = [
                     'id' => $donnees[$x]['id'],
                     'user' => $donnees[$x]['user'],
                     'message' => $donnees[$x]['message'], 
                     'tags' => $tags,
                     'created_at' => $donnees[$x]['created_at']
                 ];
                 // On le range dans le tableau selon $page et $per_page 
                 if($per_page < $input_per_page){
                     array_push($tweetsArray['tweets'][$page][$per_page], $value);
                    $per_page ++;
                 }else{
                    array_push($tweetsArray['tweets'][$page][$per_page], $value);
                    $page++;
                    $per_page = 1;
                 }
            }
    
            echo json_encode($tweetsArray);
            http_response_code(200);
    
        }else{
            //On gère le cas où le résultat est vide
            echo json_encode("Aucun résultat trouvé");
        }
    }else{
        //On gère le cas où la requête comporte une erreur
        echo json_encode("Erreur dans la forme de la requète. Veuillez respecter le format définie :)");
        http_response_code(400);
    }
}else{
    //On gère le cas où la demande est faite avec une méthode non autorisée
    http_response_code(405);
    echo json_encode(array("message"=>"Méthode non autorisée!"));
}