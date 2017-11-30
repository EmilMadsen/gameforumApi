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


// HANDLE REQUEST
switch ($request){

    case '/create':
        // TODO...
        echo 'This is not implemented..';
        break;

    case 'upvote':
        // TODO...
        break;

    case 'downvote':
        //TODO:..
        break;

    default:
        ResponseService::ResponseNotFound();
        break;
}

// ++ //
//function createComment($token, $input){
//    $comment = new Comment();
//    $comment->constructFromHashMap($input);
//    $comment->createComment($token);
//    ResponseService::ResponseJSON($comment->idToJson());
//
//}
