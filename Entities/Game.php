<?php
/**
 * Created by PhpStorm.
 * User: Kaempe
 * Date: 22-11-2017
 * Time: 13:38
 */

include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Repositories/GameRepository.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/ResponseService.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Logic/Validation.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Entities/AbstractModel.php');


/**
 * Class Game
 */
class Game extends AbstractModel {

    private $id;
    private $title;
    private $description;
    private $releaseDate;
    private $rating;
    private $developerCompanyCode;
    private $publisherCompanyCode;
    private $pictureFilePath;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;

    /**
     * Game constructor.
     * @param $id
     * @param $title
     * @param $description
     * @param $releaseDate
     * @param $rating
     * @param $developerCompanyCode
     * @param $publisherCompanyCode
     * @param $pictureFilePath
     * @param $createdAt
     * @param $updatedAt
     * @param $deletedAt
     */
    public function __construct($id = null, $title = null, $description = null, $releaseDate = null, $rating = null,
                                $developerCompanyCode = null, $publisherCompanyCode = null, $pictureFilePath = null,
                                $createdAt = null, $updatedAt = null, $deletedAt = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->releaseDate = $releaseDate;
        $this->rating = $rating;
        $this->developerCompanyCode = $developerCompanyCode;
        $this->publisherCompanyCode = $publisherCompanyCode;
        $this->pictureFilePath = $pictureFilePath;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }

    public function constructFromHashMap($json){
        $data = json_decode($json, true);
        if (empty($data)) ResponseService::ResponseBadRequest("Invalid Request-Body");
        foreach ($data AS $key => $value) $this->{$key} = $value;
        $this->failOnInvalidModel();
    }

    public function getSpecificGame($token, $id)
    {
        return (new GameRepository())->getSpecificGame($token, $id);
    }

    public function getGameOverview($token)
    {
        return (new GameRepository())->getFrontpage($token);
    }

    public static function GetFromTag($token,$tagName,$amount,$offset){
        return GameRepository::GetFromTag($token,$tagName,$amount,$offset);
    }

    public function favoriteSpecificGame($token, $id)
    {
        return (new GameRepository())->setFavoriteGame($token, $id, true);
    }

    public function unfavoriteSpecificGame($token, $id)
    {
        return (new GameRepository())->setFavoriteGame($token, $id, false);
    }

    public static function VoteGame($token, $id,$bool){
        GameRepository::VoteGame($token, $id,$bool);
    }

    public function createGame($token){

        return (new GameRepository())->createGame($token,$this->title,$this->description,
                            $this->releaseDate, $this->publisherCompanyCode,
                            $this->developerCompanyCode,$this->pictureFilePath);
    }

    public function failOnInvalidModel()
    {
        if (!Validation::isValidTitle($this->title) ||
            !Validation::isValidContent($this->description) ||
            !Validation::isNumeric($this->publisherCompanyCode)  ||
            !Validation::isNumeric($this->developerCompanyCode) ||
            empty($this->releaseDate)
        ){
            ResponseService::ResponseBadRequest("Invalid Request-Body");
        }
    }
}