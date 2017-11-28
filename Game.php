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
$request = $_SERVER['PATH_INFO'];
$reqBody = file_get_contents('php://input');

RequestService::enableCORS();
$ipAddress = RequestService::fetIP();

RequestService::TokenCheck();
$token = RequestService::GetToken();

//die($request);


switch ($request){

    case '/specific':

        if(isset($_GET['id'])) getSpecificGame($token, $_GET['id']);
        else {}// TODO: Handle id not being set..
        break;

    case '/frontpage':

        getGameOverview($token);
        break;

    case '/favorite':

        if (isset($_GET['id'])) favoriteSpecificGame($token, $_GET['id']);
        else {}// TODO: Handle id not being set..
        break;

    case '/unfavorite':

        if (isset($_GET['id'])) unfavoriteSpecificGame($token, $_GET['id']);
        else{} // TODO: Handle id not being set..
        break;

    default:
        ResponseService::ResponseNotFound();
        break;
}

// Get a specific game, along with its posts.
function getSpecificGame($token, $id)
{
    $game = new Game();

    $specificGame = $game->getSpecificGame($token, $id);

    ResponseService::ResponseJSON($game->arrayToJson($specificGame));
}

function getGameOverview($token)
{
    $game = new Game();

    $games = $game->getGameOverview($token);

    ResponseService::ResponseJSON($game->arrayToJson($games));
}

function favoriteSpecificGame($token, $id)
{
    $game = new Game();

    $response = $game->favoriteSpecificGame($token, $id);

    ResponseService::ResponseOk($response);

}

function unfavoriteSpecificGame($token, $id)
{
    $game = new Game();

    $response = $game->unfavoriteSpecificGame($token, $id);

    ResponseService::ResponseOk($response);
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

