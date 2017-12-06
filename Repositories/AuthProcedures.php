<?php
/**
 * Created by PhpStorm.
 * User: Kaempe
 * Date: 5-11-2017
 * Time: 23:39
 */

include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Repositories/DatabaseConnection.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/ResponseService.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Entities/AuthToken.php');


/**
 * Class AuthProcedures contains methods for all stored procedures
 * used for User authentication and login.
 */
class AuthProcedures{

    /**
     * This method fetches the hashedPassword and salt for a specific user.
     * If no user is found, an empty user is returned.
     * @param $username, a string representing the username supplied by the user.
     * @return User, containing the hashedPassword and salt for the requested User.
     */
    public function fetchSalt($username){
        $salt = "dummy";

        try{
            $connection = DatabaseConnection::getConnection();
            $stmt = $connection->prepare("call game_forum.auth_fetch_salt(:username, @salt)");

            $stmt->bindParam(":username", $username);
            $stmt->execute();
            $stmt->closeCursor();

            $result = $connection->query("Select @salt")->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($result)){
                foreach ($result as $row){

                    $salt = $row['@salt'];
                }
            }

        }catch (Exception $e){
            $salt = "dummy";
        }finally{
            $connection = null;
            $stmt = null;
        }
        return $salt;
    }

    public function loginUser($username,$ipAddress,$hashedPassword){

//        die($username);
        try{
            $connection = $this->getDatabaseConnection();

            $stmt = $connection->prepare("CALL game_forum.auth_login_user(:username,:ip_address, :hashed_password,@token,@timeAlive)");
            $stmt->bindParam('username', $username, PDO::PARAM_STR );
            $stmt->bindParam('ip_address', $ipAddress, PDO::PARAM_STR);
            $stmt->bindParam('hashed_password', $hashedPassword, PDO::PARAM_STR);
            $stmt->execute();

            $stmt->closeCursor();
            $result = $connection->query("Select @token, @timeAlive")->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($result)){
                foreach (@$result as $row){
                    $authToken = new AuthToken($row['@token']);
                    $authToken->construct($row['@token'],$row['@timeAlive']);
                }
            }else{
                ResponseService::ResponseNotAuthorized();
            }
        }
        catch (PDOException $e){
            if ($e->getCode() == 45000) {
                ResponseService::ResponseBadRequest($e->errorInfo[2]);
            }else{
                die($e);
                ResponseService::ResponseBadRequest("PDO Exception in Login User");

//                ResponseService::ResponseInternalError();
            }
        }
        catch (Exception $e){

            ResponseService::ResponseBadRequest("Exception in Login User");

//            ResponseService::ResponseInternalError();
        }finally{
            $connection = null;
            $stmt = null;
        }
        return $authToken;
    }

    public function createUser($username, $hashedPassword, $salt){

        try{
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("Call game_forum.auth_create_user(:username, :hashed_password, :salt)");
            $stmt->bindParam('username', $username);
            $stmt->bindParam('hashed_password', $hashedPassword);
            $stmt->bindParam('salt', $salt);
            $stmt->execute();
        }
        catch (PDOException $e){
            if ($e->getCode() == 23000){
                ResponseService::ResponseBadRequest("Username already in use");
            }else{
                ResponseService::ResponseBadRequest("Something else");

//                ResponseService::ResponseInternalError();
            }

        }catch (Exception $e){
            ResponseService::ResponseBadRequest("Something bad..");

//            ResponseService::ResponseInternalError();
        }finally{
            $connection = null;
            $stmt = null;
        }
    }

    private function getDatabaseConnection(){
        return DatabaseConnection::getConnection();
    }

    public function getUserProfile($token)
    {
        $profile = [];
        $batch_size = 50;
        $off_set = 0;

        // Get basic user details..
        try {
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("CALL game_forum.user_profile_get_from_user(:auth_token)");
            $stmt->bindParam('auth_token', $token, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            if ($e->getCode() == 45000) {ResponseService::ResponseBadRequest($e->errorInfo[2]);}
            else {ResponseService::ResponseInternalError();}
        } catch (Exception $e) {
            ResponseService::ResponseInternalError();
        }finally{
            $connection = null;
            $stmt = null;
        }

        // Get favorite games for that user
        try {
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("CALL game_forum.game_get_favorite_from_user(:auth_token, :batch_size, :off_set)");
            $stmt->bindParam('auth_token', $token, PDO::PARAM_STR);
            $stmt->bindParam('batch_size', $batch_size, PDO::PARAM_INT);
            $stmt->bindParam('off_set', $off_set, PDO::PARAM_INT);
            $stmt->execute();
            $favorite_games = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            if ($e->getCode() == 45000) {ResponseService::ResponseBadRequest($e->errorInfo[2]);}
            else {die($e);ResponseService::ResponseInternalError();}
        } catch (Exception $e) {
            die($e);ResponseService::ResponseInternalError();
        }
        finally{
            $connection = null;
            $stmt = null;
        }

        // Get favorite posts for that user
        try {
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("CALL game_forum.post_get_favorite_from_user(:auth_token, :batch_size, :off_set)");
            $stmt->bindParam('auth_token', $token, PDO::PARAM_STR);
            $stmt->bindParam('batch_size', $batch_size, PDO::PARAM_INT);
            $stmt->bindParam('off_set', $off_set, PDO::PARAM_INT);
            $stmt->execute();
            $favorite_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            if ($e->getCode() == 45000) {ResponseService::ResponseBadRequest($e->errorInfo[2]);}
            else {ResponseService::ResponseInternalError();}
        } catch (Exception $e) {
            ResponseService::ResponseInternalError();
        }finally{
            $connection = null;
            $stmt = null;
        }

        // Get comments for that user
        try {
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("CALL game_forum.comment_get_from_user(:auth_token, :batch_size, :off_set)");
            $stmt->bindParam('auth_token', $token, PDO::PARAM_STR);
            $stmt->bindParam('batch_size', $batch_size, PDO::PARAM_INT);
            $stmt->bindParam('off_set', $off_set, PDO::PARAM_INT);
            $stmt->execute();
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            if ($e->getCode() == 45000) {ResponseService::ResponseBadRequest($e->errorInfo[2]);}
            else {ResponseService::ResponseInternalError();}
        } catch (Exception $e) {
            ResponseService::ResponseInternalError();
        }finally{
            $connection = null;
            $stmt = null;
        }

        $profile['user'] = $result[0];
        $profile['user']['favorite_games'] = $favorite_games;
        $profile['user']['favorite_posts'] = $favorite_posts;
        $profile['user']['comments'] = $comments;

        return $profile;
    }
}