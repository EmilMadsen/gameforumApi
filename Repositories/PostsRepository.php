<?php

include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Repositories/DatabaseConnection.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/SanitizeService.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/ResponseService.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Entities/Post.php');



class PostsRepository{


    // POST_GET_FROM_ID && COMMENT_GET_FROM_POST
    //--------------------------------------------------------------------------
    public function getSpecificPost($token, $id, $batch_size = 50, $off_set = 0)
    {
        $postsArray = [];

        // Get post details..
        try {
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("CALL game_forum.post_get_from_id(:auth_token, :id)");
            $stmt->bindParam('auth_token', $token, PDO::PARAM_STR);
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            if ($e->getCode() == 45000) {
                ResponseService::ResponseBadRequest($e->errorInfo[2]);
            } else {
                ResponseService::ResponseInternalError();
            }
        } catch (Exception $e) {
            ResponseService::ResponseInternalError();
        }finally{
            $connection = null;
            $stmt = null;
        }

        // Get comments for that post
        try {
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("CALL game_forum.comment_get_from_post(:auth_token, :post_id ,:batch_size, :off_set)");
            $stmt->bindParam('auth_token', $token, PDO::PARAM_STR);
            $stmt->bindParam('post_id', $id, PDO::PARAM_INT);
            $stmt->bindParam('batch_size', $batch_size, PDO::PARAM_INT);
            $stmt->bindParam('off_set', $off_set, PDO::PARAM_INT);
            $stmt->execute();
            $postsResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            if ($e->getCode() == 45000) {
                ResponseService::ResponseBadRequest($e->errorInfo[2]);
            } else {
                ResponseService::ResponseInternalError();
            }
        } catch (Exception $e) {
            ResponseService::ResponseInternalError();
        }finally{
            $connection = null;
            $stmt = null;
        }

        $postsArray['post'] = $result[0];
        $postsArray['comments'] = $postsResult;

        return $postsArray;
    }

    public function createPost($token, $game_id, $title, $content)
    {
        try {
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("CALL game_forum.post_create(:auth_token, :game_id, :title, :content)");
            $stmt->bindParam('auth_token', $token, PDO::PARAM_STR);
            $stmt->bindParam('game_id', $game_id, PDO::PARAM_INT);
            $stmt->bindParam('title', $title, PDO::PARAM_STR);
            $stmt->bindParam('content', $content, PDO::PARAM_STR);


            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            if ($e->getCode() == 45000) {die($e);ResponseService::ResponseBadRequest($e->errorInfo[2]);}
            else {die($e);ResponseService::ResponseInternalError();}
        } catch (Exception $e) {
            die($e);ResponseService::ResponseInternalError();
        }finally{
            $connection = null;
            $stmt = null;
        }

        return $result;
    }

    public function getNewest($token, $batch_size = 50, $off_set = 0)
    {
        try {
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("CALL game_forum.post_get_newest(:auth_token, :batch_size, :off_set)");
            $stmt->bindParam('auth_token', $token, PDO::PARAM_STR);
            $stmt->bindParam('batch_size', $batch_size, PDO::PARAM_INT);
            $stmt->bindParam('off_set', $off_set, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            if ($e->getCode() == 45000) {
                ResponseService::ResponseBadRequest($e->errorInfo[2]);
            } else {
                ResponseService::ResponseInternalError();
            }
        } catch (Exception $e) {
            ResponseService::ResponseInternalError();
        }finally{
            $connection = null;
            $stmt = null;
        }

        return $result;
    }

    public function getTopVoted($token, $batch_size = 50, $off_set = 0)
    {
        try {
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("CALL game_forum.post_get_top_voted(:auth_token, :batch_size, :off_set)");
            $stmt->bindParam('auth_token', $token, PDO::PARAM_STR);
            $stmt->bindParam('batch_size', $batch_size, PDO::PARAM_INT);
            $stmt->bindParam('off_set', $off_set, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            if ($e->getCode() == 45000) {
                ResponseService::ResponseBadRequest($e->errorInfo[2]);
            } else {
                ResponseService::ResponseInternalError();
            }
        } catch (Exception $e) {
            ResponseService::ResponseInternalError();
        }finally{
            $connection = null;
            $stmt = null;
        }

        return $result;
    }

    public function setFavoritePost($token, $id, $bool)
    {
        // Set favorite bool on a game.
        try {
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("CALL game_forum.post_set_favorite(:auth_token, :post_id, :favorite_bool)");
            $stmt->bindParam('auth_token', $token, PDO::PARAM_STR);
            $stmt->bindParam('post_id', $id, PDO::PARAM_INT);
            $stmt->bindParam('favorite_bool', $bool, PDO::PARAM_BOOL);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            if ($e->getCode() == 45000) {die($e);ResponseService::ResponseBadRequest($e->errorInfo[2]);}
            else {die($e);ResponseService::ResponseInternalError();}
        } catch (Exception $e) {
            die($e);ResponseService::ResponseInternalError();
        }finally{
            $connection = null;
            $stmt = null;
        }

        return $result;
    }


    static function votePost($token, $id,$bool){
        try {
            $connection = DatabaseConnection::getConnection();
            $stmt = $connection->prepare("CALL game_forum.post_vote(:auth_token, :post_id, :vote_value)");
            $stmt->bindParam('auth_token', $token, PDO::PARAM_STR);
            $stmt->bindParam('post_id', $id, PDO::PARAM_INT);
            $stmt->bindParam('vote_value', $bool, PDO::PARAM_BOOL);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);


        } catch (PDOException $e) {
            if ($e->getCode() == 45000) {ResponseService::ResponseBadRequest($e->errorInfo[2]);}
            else {ResponseService::ResponseInternalError();}
        } catch (Exception $e) {
            ResponseService::ResponseInternalError();
        }finally{
            $connection = null;
            $stmt = null;
        }
    }

    //--------------------------------------------------------------------------
    //--------------------------------------------------------------------------

    private function getDatabaseConnection(){
        return DatabaseConnection::getConnection();
    }


}


//function makePostsFromResultSet($result){
//
//    $postsArray = [];
//
//     foreach (@$result as $row){
//
//         $post = new Post();
//         $post->construct(
//             $row['id'],
//             $row['user_id'],
//             'Dummy Username',
//             SanitizeService::SanitizeString($row['title']),
//             SanitizeService::SanitizeString($row['content']),
//             $row['created_timestamp'],
//             $row['updated_timestamp'],
//             $row['deleted_timestamp']
//         );
//         array_push($postsArray,$post);
//    }
//
//    return $postsArray;
//}