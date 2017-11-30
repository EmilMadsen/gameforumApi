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

//$POSTS_DEFAULT_AMOUNT = 50;
//$POSTS_DEFAULT_OFFSET = 0;

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

    default:
        ResponseService::ResponseNotFound();
        break;
}

function getSpecificPost($token, $id){
//    $postAmount = RequestService::isNumericUrlParamDefined('amount') ? $_GET['amount'] : $defaultAmount;
//    $postOffset = RequestService::isNumericUrlParamDefined('offset') ? $_GET['offset'] : $defaultOffset;
//    $userId     = RequestService::isNumericUrlParamDefined('user_id')? $_GET['user_id'] : 0;

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

