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
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Entities/Post.php');

$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['PATH_INFO'];
$reqBody = file_get_contents('php://input');

RequestService::enableCORS();
$ipAddress = RequestService::fetIP();

RequestService::TokenCheck();
$token = RequestService::GetToken();

$postRepository = new PostsRepository();

switch ($request){

    case '/specific':

        if(isset($_GET['id'])) getSpecificPost($token, $_GET['id']);
        else {}// TODO: Handle id not being set..
        break;

    case '/create':

        //TODO:
        break;

    case '/upvote':

        // TODO:
        break;

    case '/downvote':

        // TODO:
        break;

    case '/favorite':

        // TODO:
        break;

    case '/unfavorite':

        // TODO:
        break;

    case '/newest':

        getNewest($token);
        break;

    case '/topvoted':

        getTopVoted($token);
        break;

    default:
        ResponseService::ResponseNotFound();
        break;
}

function getSpecificPost($token, $id)
{
    $post = new Post();
    $response = $post->getSpecificPost($token, $id);
    ResponseService::ResponseJSON($post->arrayToJson($response));
}

function createPost($input,$token){
//    $post = new Post();
//    $post->constructFromHashMap($input);
//    $post->createPost($token); // TODO
//    ResponseService::ResponseJSON($post->idToJson());
}

function upvote($token, $id)
{

}

function downvote($token, $id)
{

}

function getNewest($token){
    $post = new Post();
    $response = $post->getNewest($token);
    ResponseService::ResponseJSON($post->arrayToJson($response));
}

function getTopVoted($token){
    $post = new Post();
    $response = $post->getTopVoted($token);
    ResponseService::ResponseJSON($post->arrayToJson($response));
}

