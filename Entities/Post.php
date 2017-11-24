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

/**
 * Class Post_ My version of the Post Class, including functionality
 */
class Post{

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
        $this->failOnInvalidModel();
    }

    public function construct($id, $userId, $username, $title, $content,
                              $createdAt, $updatedAt, $deletedAt){
        $this->id = $id;
        $this->user_id = $userId;
        $this->username = $username;
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }

    public function createPost($token){
        $this->failOnInvalidModel();
        $validation = new Validation();
        $procedures = new PostsRepository();
        $this->title = SanitizeService::SanitizeString($this->title);
        $this->content = SanitizeService::SanitizeString($this->content);
        if (!$validation->isValidToken($token)) ResponseService::ResponseBadRequest("Invalid Request-Body");
        $this->id = $procedures->createPost($token,$this->title,$this->content);
    }

    public function getRecent($token,$amount,$offset){
        $validation = new Validation();
        $procedures = new PostsRepository();

        if (!$validation->isValidToken($token) ||
        !is_numeric($amount) ||
        !is_numeric($offset)) {
            ResponseService::ResponseBadRequest("Invalid Request-Body");
        }

        return $procedures->getPosts($token,$amount,$offset);
    }

    public function getFromUser($token,$userId,$amount,$offset){
        $validation = new Validation();
        $procedures = new PostsRepository();
        if (!$validation->isValidToken($token) ||
            !is_numeric($userId) ||
            !is_numeric($amount) ||
            !is_numeric($offset)){
            ResponseService::ResponseBadRequest("Invalid Request-Body");
        }
        return $procedures->getPostsByUser($token,$userId,$amount,$offset);
    }

    public function arrayToJson($posts){
        $result = "[";
        if (!empty($posts)){
            foreach ($posts as $post){
                $result .= json_encode(get_object_vars($post)).', ';
            }
            $result = substr($result,0,strlen($result)-2);
        }
        $result .= "]";
        return $result;
    }


    public function idToJson(){
        return json_encode($this->id);
    }

    private function failOnInvalidModel(){
        $validation = new Validation();

        if (!$validation->isValidTitle($this->title) ||
            !$validation->isValidContent($this->content)){
            ResponseService::ResponseBadRequest("Invalid Request-Body");
        }
     }

}