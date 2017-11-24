<?php
/**
 * Created by PhpStorm.
 * User: Kaempe
 * Date: 22-11-2017
 * Time: 22:02
 */

include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Repositories/CommentsRepository.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/ResponseService.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Logic/Validation.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/SanitizeService.php');


class Comment{

    private $id;
    private $user_id;
    private $username;
    private $post_id;
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

    public function construct($id, $userId, $username, $postId, $content, $createdAt, $updatedAt, $deletedAt){
        $this->id = $id;
        $this->user_id = $userId;
        $this->username = $username;
        $this->post_id = $postId;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }
    public function createComment($token){
        $this->failOnInvalidModel();
        $validation = new Validation();
        $procedures = new CommentsRepository();
        $this->content = SanitizeService::SanitizeString($this->content);
        if (!$validation->isValidToken($token)) ResponseService::ResponseBadRequest("Invalid Request-Body");

        $this->id = $procedures->createComment($token,$this->post_id,$this->content);

    }

    public function getCommentsFromPost($token, $post_id, $amount, $offset){
        $validation = new Validation();
        $procedures = new CommentsRepository();

        if (!$validation->isValidToken($token) ||
            !is_numeric($post_id) ||
            !is_numeric($amount) ||
            !is_numeric($offset)){
            ResponseService::ResponseBadRequest("Invalid Request-Body");
        }
        return $procedures->getCommentsOfPost($token, $post_id, $amount, $offset);
    }

    public function arrayToJson($comments){
        $result = "[";
        if (!empty($comments) ){
            foreach ($comments as $comment){
                $result .= json_encode(get_object_vars($comment)).', ';
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

        if (!$validation->isValidContent($this->content)||
            !is_numeric($this->post_id)){
            ResponseService::ResponseBadRequest("Invalid Request-Body");
        }
    }


}