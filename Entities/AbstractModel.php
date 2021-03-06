<?php
/**
 * Created by IntelliJ IDEA.
 * User: Emilo
 * Date: 27-11-2017
 * Time: 20:48
 */

include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Logic/Validation.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Services/ResponseService.php');

abstract class AbstractModel
{
    public function arrayToJson($array)
    {
        $json = json_encode($array);

//        $result = "[";
//        if (!empty($array)){
//            foreach ($array as $item){
//                $result .= json_encode(get_object_vars($item)).', ';
//            }
//            $result = substr($result,0,strlen($result)-2);
//        }
//        $result .= "]";

        return $json;
    }


    public function idToJson($id)
    {
        return json_encode($id);
    }


}