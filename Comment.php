<?php

include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/RequestService.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/ResponseService.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Entities/Comment.php');
//include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/SanitizeService.php');
//include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Entities/CommentModel.php');
//include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Repositories/CommentsRepository.php');


//------------------------------------------------------------------------------
/*

    RequestService::TokenCheck() Checks if REQUEST contains:
        Authorization Header
        Valid Token

            if TokenCheck() failed -> 
            Service response to client with Not Authorized 401
            AND the below functions will not be executed
*/

RequestService::enableCORS();

//------------------------------------------------------------------------------

RequestService::TokenCheck();
// Get token value from REQUEST
$token = RequestService::GetToken();

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

$request = $_SERVER['PATH_INFO'];
$reqBody = file_get_contents('php://input');


// HANDLE REQUEST
switch ($request){

    case '/create':
        createComment($token,$reqBody);
        break;

    case '/upvote':
        $id = RequestService::isNumericUrlParamDefined('id')? $_GET['id'] : ResponseService::ResponseBadRequest();
        upVotePost($token, $id);
        break;

    case '/downvote':
        $id = RequestService::isNumericUrlParamDefined('id')? $_GET['id'] : ResponseService::ResponseBadRequest();
        downVotePost($token, $id);
        break;

    default:
        ResponseService::ResponseNotFound();
        break;
}


function createComment($token, $input){
    $comment = new Comment();
    $comment->constructFromHashMap($input);
    $response = $comment->createComment($token);
    ResponseService::ResponseJSON($comment->arrayToJson($response));

}

function upVotePost($token, $id)
{
    $response = Comment::voteComment($token,$id,true);
    ResponseService::ResponseJSON((new Comment())->arrayToJson($response));

}

function downVotePost($token, $id)
{
    $response = Comment::voteComment($token,$id,false);
    ResponseService::ResponseJSON((new Comment())->arrayToJson($response));
}
