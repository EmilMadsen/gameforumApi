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
        $this->failOnInvalidModel($this->title, $this->description);
    }

    public function getSpecificGame($token, $id)
    {

        $repo = new GameRepository();

        return $repo->getSpecificGame($token, $id);

    }

    public function getGameOverview($token)
    {
        $repo = new GameRepository();

        return $repo->getFrontpage($token);
    }

    public function favoriteSpecificGame($token, $id)
    {
        $repo = new GameRepository();

        return $repo->favoriteSpecificGame($token, $id);
    }

    public function unfavoriteSpecificGame($token, $id)
    {
        $repo = new GameRepository();

        return $repo->unfavoriteSpecificGame($token, $id);
    }


//    public function createPost($token){
//        $this->failOnInvalidModel();
//        $validation = new Validation();
//        $procedures = new PostsRepository();
//        $this->title = SanitizeService::SanitizeString($this->title);
//        $this->content = SanitizeService::SanitizeString($this->content);
//        if (!$validation->isValidToken($token)) ResponseService::ResponseBadRequest("Invalid Request-Body");
//        $this->id = $procedures->createPost($token,$this->title,$this->content);
//    }

//    public function getRecent($token,$amount,$offset){
//        $validation = new Validation();
//        $procedures = new GameRepository();
//
//        if (!$validation->isValidToken($token) ||
//        !is_numeric($amount) ||
//        !is_numeric($offset)) {
//            ResponseService::ResponseBadRequest("Invalid Request-Body");
//        }
//
//        return $procedures->getPosts($token,$amount,$offset);
//    }
//
//    public function getFromUser($token,$userId,$amount,$offset){
//        $validation = new Validation();
//        $procedures = new GameRepository();
//        if (!$validation->isValidToken($token) ||
//            !is_numeric($userId) ||
//            !is_numeric($amount) ||
//            !is_numeric($offset)){
//            ResponseService::ResponseBadRequest("Invalid Request-Body");
//        }
//        return $procedures->getPostsByUser($token,$userId,$amount,$offset);
//    }

}