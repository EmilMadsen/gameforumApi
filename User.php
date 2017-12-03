<?php
/**
 * Created by PhpStorm.
 * User: Kaempe
 * Date: 17-10-2017
 * Time: 12:33
 */

include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Entities/AuthToken.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Entities/User.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/RequestService.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/ResponseService.php');


$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['PATH_INFO'];
$reqBody = file_get_contents('php://input');
$ipAddress = RequestService::fetIP();

RequestService::enableCORS();



switch ($request){

    case '/register':
        register($reqBody,$ipAddress);
        break;
    case '/login':
        tryLogin($reqBody,$ipAddress);
        break;
    case '/refreshToken':
        tryLogin($reqBody,$ipAddress);
        break;
    case '/logout':
        ResponseService::ResponseNotImplemented();
        break;
    case '/profile':

        $token = RequestService::GetToken();
        getUserProfile($token);
        break;
    default:
        ResponseService::ResponseNotFound();
        break;
}

function register($input,$ip){
    $User = new User();
    $User->constructFromHashMap($input);
    $authToken = $User->createUser($ip);
    ResponseService::ResponseJSON($authToken->toJson());
}

function tryLogin($input, $ip){
    $User = new User();
    $User->constructFromHashMap($input);
    $authToken = $User->tryLogin($ip);
    ResponseService::ResponseJSON($authToken->toJson());
}

function getUserProfile($token)
{
    $user = new User();
    $response = $user->getUserProfile($token);
    ResponseService::ResponseJSON($user->arrayToJson($response));
}

