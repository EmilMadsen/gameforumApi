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
        return [

            'standard' => [
                '0' => [
                    'id' => 123,
                    'title' => 'General',
                    'postCount' => 123,
                    'description' => 'Its cool',
                    'src' => 'general.jpg'
                ],
                '1' => [
                    'id' => 123,
                    'title' => 'Random',
                    'postCount' => 123,
                    'description' => 'Its cool',
                    'src' => 'random.jpg'
                ],
                '2' => [
                    'id' => 123,
                    'title' => 'News',
                    'postCount' => 123,
                    'description' => 'Its cool',
                    'src' => 'news.jpg'
                ],
                '3' => [
                    'id' => 123,
                    'title' => 'Hot',
                    'postCount' => 123,
                    'description' => 'Its cool',
                    'src' => 'hot.jpg'
                ],
                '4' => [
                    'id' => 123,
                    'title' => 'Rising',
                    'postCount' => 123,
                    'description' => 'Its cool',
                    'src' => 'rising.jpg'
                ],
                '5' => [
                    'id' => 123,
                    'title' => 'Top Voted',
                    'postCount' => 123,
                    'description' => 'Its cool',
                    'src' => 'topvoted.jpg'
                ]

            ],
            'games' => [
                '0' => [
                    'id' => 321,
                    'title' => 'game titlez',
                    'postCount' => 123,
                    'description' => 'Its cool',
                    'src' => 'Hearthstone-285x380.jpg'

                ],
                '1' => [
                    'id' => 321,
                    'title' => 'game titlez',
                    'postCount' => 123,
                    'description' => 'Its cool',
//                    'src' => 'Hearthstone-285x380.jpg'
                ]
            ]
        ];

//        $gameArray = [];
//
//        try {
//            $connection = $this->getDatabaseConnection();
//            $stmt = $connection->prepare("CALL game_forum.games_get_frontpage(:auth_token ,:batch_size, :batch)");
//            $stmt->bindParam('auth_token', $authToken, PDO::PARAM_STR);
//            $stmt->bindParam('batch_size', $batch_size, PDO::PARAM_INT);
//            $stmt->bindParam('batch', $batch, PDO::PARAM_INT);
//            $stmt->execute();
//            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//            if (!empty($result)) {
//                $gameArray = $this->makeGamesFromResultSet($result);
//            }
//        } catch (PDOException $e) {
//            if ($e->getCode() == 45000) {
//                ResponseService::ResponseBadRequest($e->errorInfo[2]);
//            } else {
//                ResponseService::ResponseInternalError();
//            }
//        } catch (Exception $e) {
//            ResponseService::ResponseInternalError();
//        }
//
//        return $gameArray;
    }

    // Get specific game with its posts and info.
    //--------------------------------------------------------------------------
    public function getSpecificGame($authToken, $id)
//  public function getSpecificGame($authToken, $batch_size=100, $batch=1, $id) TODO: Use batch, and batch_size?
    {
        return [

            'game' => [
                'id' => 123,
                'title' => 'Game Title!',
                'postCount' => 123,
                'description' => 'Its cool LOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOONG description',
                'src' => 'Hearthstone-285x380.jpg',
                'favorite' => true

            ],
            'posts' => [
                '0' => [
                    'id' => 321,
                    'title' => 'game titlez',
                    'commentCount' => 123,
                    'description' => 'Its cool',

                ],
                '1' => [
                    'id' => 321,
                    'title' => 'game titlez',
                    'commentCount' => 123,
                    'description' => 'Its cool',
                ]
            ]
        ];
//        $gameArray = [];
//
//        try {
//            $connection = $this->getDatabaseConnection();
//            $stmt = $connection->prepare("CALL game_forum.games_get_specific(:auth_token ,:batch_size, :batch, :id)");
//            $stmt->bindParam('auth_token', $authToken, PDO::PARAM_STR);
//            $stmt->bindParam('batch_size', $batch_size, PDO::PARAM_INT);
//            $stmt->bindParam('batch', $batch, PDO::PARAM_INT);
//            $stmt->bindParam('id', $id, PDO::PARAM_INT);
//            $stmt->execute();
//            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//            if (!empty($result)) {
//                $gameArray = $this->makeGamesFromResultSet($result);
//            }
//        } catch (PDOException $e) {
//            if ($e->getCode() == 45000) {
//                ResponseService::ResponseBadRequest($e->errorInfo[2]);
//            } else {
//                ResponseService::ResponseInternalError();
//            }
//        } catch (Exception $e) {
//            ResponseService::ResponseInternalError();
//        }
//
//        return $gameArray;
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
