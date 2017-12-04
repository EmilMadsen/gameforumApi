<?php

include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Repositories/DatabaseConnection.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/ResponseService.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/SanitizeService.php');

class CommentsRepository{

    public function getCommentsOfPost($token, $post_id, $amount, $offset){
        $commentsArray = [];
        try{
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("CALL game_forum.comment_get_from_post(:auth_token, :post_id, :amount, :offset)");
            $stmt->bindParam("auth_token", $token, PDO::PARAM_STR );
            $stmt->bindParam('post_id', $post_id, PDO::PARAM_INT);
            $stmt->bindParam('amount', $amount, PDO::PARAM_INT);
            $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($result)){
                foreach (@$result as $row){

                    $comment = new Comment();
                    $comment->construct(
                        $row['id'],
                        $row['user_id'],
                        'Dummy Username',
                        $row['post_id'],
                        SanitizeService::SanitizeString($row['content']),
                        $row['created_timestamp'],
                        $row['updated_timestamp'],
                        $row['deleted_timestamp']
                    );
                    array_push($commentsArray,$comment);
                }
            }
        }
        catch (PDOException $e){
            if ($e->getCode() == 45000) {
                ResponseService::ResponseBadRequest($e->errorInfo[2]);
            }else{
                ResponseService::ResponseInternalError();
            }
        }
        catch (Exception $e){
            ResponseService::ResponseInternalError();
        }

        return $commentsArray;
    }

    //---------------------------------------------------------------------

    public function createComment($token, $post_id, $content){

        try{
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("CALL game_forum.comment_create(:auth_token,:post_id, :content)");
            $stmt->bindParam('auth_token', $token, PDO::PARAM_STR );
            $stmt->bindParam('post_id', $post_id, PDO::PARAM_INT);
            $stmt->bindParam('content', $content, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e){
            var_dump($e);
            if ($e->getCode() == 45000) {
                ResponseService::ResponseBadRequest($e->errorInfo[2]);
            }elseif ($e->getCode() == 23000) {
                ResponseService::ResponseBadRequest("Invalid Post");
            }else{
                ResponseService::ResponseInternalError();
            }
        }
        catch (Exception $e){
            ResponseService::ResponseInternalError();
        }
        return  $result;
    }


    static function voteComment($token, $id,$bool){
        try {
            $connection = DatabaseConnection::getConnection();
            $stmt = $connection->prepare("CALL game_forum.comment_vote(:auth_token, :comment_id, :vote_value)");
            $stmt->bindParam('auth_token', $token, PDO::PARAM_STR);
            $stmt->bindParam('comment_id', $id, PDO::PARAM_INT);
            $stmt->bindParam('vote_value', $bool, PDO::PARAM_BOOL);
            $stmt->execute();

        } catch (PDOException $e) {
            if ($e->getCode() == 45000) {ResponseService::ResponseBadRequest($e->errorInfo[2]);}
            else {ResponseService::ResponseInternalError();}
        } catch (Exception $e) {ResponseService::ResponseInternalError();}
    }

    private function getDatabaseConnection(){
        return DatabaseConnection::getConnection();
    }
    //---------------------------------------------------------------------
    //---------------------------------------------------------------------
    //---------------------------------------------------------------------
}

?>