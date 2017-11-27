<?php
/**
 * Created by PhpStorm.
 * User: Kaempe
 * Date: 22-11-2017
 * Time: 13:40
 */

include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/RequestService.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/ResponseService.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/SanitizeService.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Repositories/PostsRepository.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Entities/Game.php');

$method = $_SERVER['REQUEST_METHOD'];
$reqBody = file_get_contents('php://input');

RequestService::enableCORS();
$ipAddress = RequestService::fetIP();

//RequestService::TokenCheck();
//$token = RequestService::GetToken();

switch ($method)
{
    case 'GET':

        if(isset($_GET['id'])) return getSpecificGame();
        else return getGameOverview();

        break;

    case 'POST':

        // Create Game..
        ResponseService::ResponseNotFound();

        break;

    case 'PUT':

        // Favorite a game..
        ResponseService::ResponseNotFound();

        break;

    default:
        ResponseService::ResponseNotFound();
        break;
}

// Get a specific game, along with its posts.
function getSpecificGame()
{
    return "SPECIFIC";

}

function getGameOverview($token)
{
    $game = new Game();

    $games = $game->getGameOverview($token);

    ResponseService::ResponseJSON($game->arrayToJson($games));
}

//function getPosts($token,$defaultAmount,$defaultOffset){
//    $postAmount = RequestService::isNumericUrlParamDefined('amount') ? $_GET['amount'] : $defaultAmount;
//    $postOffset = RequestService::isNumericUrlParamDefined('offset') ? $_GET['offset'] : $defaultOffset;
//    $userId     = RequestService::isNumericUrlParamDefined('user_id')? $_GET['user_id'] : 0;
//
//    $post = new Post();
//
//    if ( $userId === 0){
//        $posts = $post->getRecent($token,$postAmount,$postOffset);
//    }else{
//        $posts = $post->getFromUser($token,$userId,$postAmount,$postOffset);
//    }
//    ResponseService::ResponseJSON($post->arrayToJson($posts));
//}
//
//function createPost($input,$token){
//    $post = new Post();
//    $post->constructFromHashMap($input);
//    $post->createPost($token); // TODO
//    ResponseService::ResponseJSON($post->idToJson());
//}

