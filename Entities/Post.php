<?php
/**
 * Created by PhpStorm.
 * User: Kaempe
 * Date: 22-11-2017
 * Time: 13:38
 */

include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Repositories/PostsRepository.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/ResponseService.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Logic/Validation.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Entities/AbstractModel.php');


/**
 * Class Post_ My version of the Post Class, including functionality
 */
class Post extends AbstractModel {

    private $id;
    private $user_id;
    private $username;
    private $title;
    private $content;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;

    public function constructFromHashMap($json){
        $data = json_decode($json, true);
        if (empty($data)) ResponseService::ResponseBadRequest("Invalid Request-Body");
        foreach ($data AS $key => $value) $this->{$key} = $value;
        $this->failOnInvalidModel($this->title, $this->content);
    }

    public function construct($id, $userId, $username, $title, $content, $createdAt, $updatedAt, $deletedAt){
        $this->id = $id;
        $this->user_id = $userId;
        $this->username = $username;
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }


    public function getSpecificPost($token, $id)
    {
        return (new PostsRepository())->getSpecificPost($token, $id);
    }

    public function createPost($token){
//        $this->failOnInvalidModel($this->title, $this->content);
//        $validation = new Validation();
//        $procedures = new PostsRepository();
//        $this->title = SanitizeService::SanitizeString($this->title);
//        $this->content = SanitizeService::SanitizeString($this->content);
//        if (!$validation->isValidToken($token)) ResponseService::ResponseBadRequest("Invalid Request-Body");
//        $this->id = $procedures->createPost($token,$this->title,$this->content);
    }

    public function getNewest($token)
    {
        return (new PostsRepository())->getNewest($token);
    }

    public function getTopVoted($token)
    {
        return (new PostsRepository())->getTopVoted($token);
    }

    public function favoritePost($token, $id)
    {
        return (new PostsRepository())->setFavoritePost($token, $id, true);
    }

    public function unfavoritePost($token, $id)
    {
        return (new PostsRepository())->setFavoritePost($token, $id, false);
    }


}