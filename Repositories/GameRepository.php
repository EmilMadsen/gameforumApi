<?php

include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Repositories/DatabaseConnection.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/SanitizeService.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/ResponseService.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Entities/Game.php');


class GameRepository
{
    // GAME_GET_ALL
    //--------------------------------------------------------------------------
    public function getFrontpage($authToken, $batch_size = 50, $off_set = 0)
    {
        try {
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("CALL game_forum.game_get_all(:auth_token ,:batch_size, :off_set)");
            $stmt->bindParam('auth_token', $authToken, PDO::PARAM_STR);
            $stmt->bindParam('batch_size', $batch_size, PDO::PARAM_INT);
            $stmt->bindParam('off_set', $off_set, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            if ($e->getCode() == 45000) {
                die($e);
                ResponseService::ResponseBadRequest($e->errorInfo[2]);
            } else {
                die($e);
                ResponseService::ResponseInternalError();
            }
        } catch (Exception $e) {
            die($e);
            ResponseService::ResponseInternalError();
        }

        return $result;
    }

    // GAME_GET_FROM_ID && POST_GET_FROM_GAME
    //--------------------------------------------------------------------------
  public function getSpecificGame($authToken, $id, $batch_size=100, $off_set=0)
    {
        $gameArray = [];

        // Get game details..
        try {
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("CALL game_forum.game_get_from_id(:auth_token, :id)");
            $stmt->bindParam('auth_token', $authToken, PDO::PARAM_STR);
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
        }

        // Get posts for that game
        try {
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("CALL game_forum.post_get_from_game(:auth_token, :game_id ,:batch_size, :off_set)");
            $stmt->bindParam('auth_token', $authToken, PDO::PARAM_STR);
            $stmt->bindParam('game_id', $id, PDO::PARAM_INT);
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
        }

        $gameArray['game'] = $result[0];
        $gameArray['posts'] = $postsResult;

        return $gameArray;
    }

    public function favoriteSpecificGame($token, $id)
    {
        //TODO: Call actual stored procedure..
        return 'success';
    }

    public function unfavoriteSpecificGame($token, $id)
    {
        //TODO: Call actual stored procedure..
        return 'success';
    }

    // Get DB connection
    //--------------------------------------------------------------------------
    private function getDatabaseConnection(){
        return DatabaseConnection::getConnection();
    }

//    // Transform resultset into array of games.
//    //--------------------------------------------------------------------------
//    private function makeGamesFromResultSet($result){
//
//        $gamesArray = [];
//
//        foreach (@$result as $row){
//
//            //TODO correct the column names with the ones we're going to make on DB.
//            $game = new Game(
//                $row['id'],
//                $row['title'],
//                $row['description'],
//                $row['releaseDate'],
//                $row['rating'],
//                $row['developerCompanyCode'],
//                $row['publisherCompanyCode'],
//                $row['pictureFilePath'],
//                $row['created_timestamp'],
//                $row['updated_timestamp'],
//                $row['deleted_timestamp']
//            );
//            array_push($gamesArray, $game);
//        }
//
//        return $gamesArray;
//    }
}
