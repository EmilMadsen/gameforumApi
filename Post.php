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

        RequestService::isNumericUrlParamDefined('id')? getSpecificPost($token, $_GET['id']) : ResponseService::ResponseBadRequest();

        break;

    case '/create':
        createPost($token,$reqBody);
        break;

    case '/upvote':
        $id = RequestService::isNumericUrlParamDefined('id')? $_GET['id'] : ResponseService::ResponseBadRequest();
        upVotePost($token, $id);
        break;

    case '/downvote':
        $id = RequestService::isNumericUrlParamDefined('id')? $_GET['id'] : ResponseService::ResponseBadRequest();
        downVotePost($token, $id);
        break;

    case '/favorite':
        RequestService::isNumericUrlParamDefined('id')? favoritePost($token, $_GET['id']) : ResponseService::ResponseBadRequest();
        break;

    case '/unfavorite':

        if(isset($_GET['id'])) unfavoritePost($token, $_GET['id']);
        else {}// TODO: Handle id not being set..
        break;

    case '/newest':

        getNewest($token);
        break;

    case '/topvoted':

        getTopVoted($token);
        break;

    default:
        var_dump($request);
        ResponseService::ResponseNotFound();
        break;
}

function getSpecificPost($token, $id)
{
    $post = new Post();
    $response = $post->getSpecificPost($token, $id);
    ResponseService::ResponseJSON($post->arrayToJson($response));
}

function createPost($token,$input){
    $post = new Post();
    $post->constructFromHashMap($input);
    $response = $post->createPost($token);
    ResponseService::ResponseJSON($post->arrayToJson($response));
}

function upVotePost($token, $id)
{
    Post::votePost($token,$id,true);
}

function downVotePost($token, $id)
{
    Post::votePost($token,$id,false);
}

function favoritePost($token, $id)
{
    $post = new Post();
    $response = $post->favoritePost($token, $id);
    ResponseService::ResponseJSON($post->arrayToJson($response));
}

function unfavoritePost($token, $id)
{
    $post = new Post();
    $response = $post->unfavoritePost($token, $id);
    ResponseService::ResponseJSON($post->arrayToJson($response));
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

