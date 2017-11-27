<?php

include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Repositories/DatabaseConnection.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/SanitizeService.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/ResponseService.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Entities/Game.php');


class GameRepository
{
    // Get games for frontpage
    //--------------------------------------------------------------------------
    public function getFrontpage($authToken, $batch_size = 50, $batch = 1)
    {
        $gameArray = [];

        try {
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("CALL game_forum.games_get_frontpage(:auth_token ,:batch_size, :batch)");
            $stmt->bindParam('auth_token', $authToken, PDO::PARAM_STR);
            $stmt->bindParam('batch_size', $batch_size, PDO::PARAM_INT);
            $stmt->bindParam('batch', $batch, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($result)) {
                $gameArray = $this->makeGamesFromResultSet($result);
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 45000) {
                ResponseService::ResponseBadRequest($e->errorInfo[2]);
            } else {
                ResponseService::ResponseInternalError();
            }
        } catch (Exception $e) {
            ResponseService::ResponseInternalError();
        }

        return $gameArray;
    }

    // Get specific game with its posts and info.
    //--------------------------------------------------------------------------
    public function getSpecificGame($authToken, $batch_size, $batch, $id)
    {
        $gameArray = [];

        try {
            $connection = $this->getDatabaseConnection();
            $stmt = $connection->prepare("CALL game_forum.games_get_specific(:auth_token ,:batch_size, :batch, :id)");
            $stmt->bindParam('auth_token', $authToken, PDO::PARAM_STR);
            $stmt->bindParam('batch_size', $batch_size, PDO::PARAM_INT);
            $stmt->bindParam('batch', $batch, PDO::PARAM_INT);
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($result)) {
                $gameArray = $this->makeGamesFromResultSet($result);
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 45000) {
                ResponseService::ResponseBadRequest($e->errorInfo[2]);
            } else {
                ResponseService::ResponseInternalError();
            }
        } catch (Exception $e) {
            ResponseService::ResponseInternalError();
        }

        return $gameArray;
    }

    // Get DB connection
    //--------------------------------------------------------------------------
    private function getDatabaseConnection(){
        return DatabaseConnection::getConnection();
    }

    // Transform resultset into array of games.
    //--------------------------------------------------------------------------
    private function makeGamesFromResultSet($result){

        $gamesArray = [];

        foreach (@$result as $row){

            //TODO correct the column names with the ones we're going to make on DB.
            $game = new Game(
                $row['id'],
                $row['title'],
                $row['description'],
                $row['releaseDate'],
                $row['rating'],
                $row['developerCompanyCode'],
                $row['publisherCompanyCode'],
                $row['pictureFilePath'],
                $row['created_timestamp'],
                $row['updated_timestamp'],
                $row['deleted_timestamp']
            );
            array_push($gamesArray, $game);
        }

        return $gamesArray;
    }
}
