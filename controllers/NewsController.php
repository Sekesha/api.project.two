<?php

require_once ROOT . '/components/HelperFunc.php';
require_once ROOT . '/model-api/NewsModel.php';
require_once ROOT . '/components/RequestHttp.php';
require_once ROOT . '/components/HeaderSender.php';


class NewsController
{
    private $id;//post Id
    private $requiredFields = array('title', 'content', 'author', 'creation_date'); //allowed fields

    public function MainAction($parameters)
    {
        $method = ucfirst(strtolower($_SERVER['REQUEST_METHOD'])) . 'Action';

        if (method_exists(NewsController::class, $method)) {
            $this->$method($parameters);
        } else {
            HeaderSender::Send(405, "Method Not Allowed");
        }
        return true;
    }

    private function GetAction($param)
    {
        $data = [];
        if (self::checkParam($param)) {
            $data = NewsModel::readOne($this->id);
        } else {
            $data = NewsModel::readAll();
        }
        if ($data){
            HeaderSender::Send(200, $data);
        }else{
            HeaderSender::Send(404, "Post not found");
        }

    }

    private function PostAction($param)
    {
        $write_id = '';
        if (!self::checkParam($param)) {
            $data = RequestHttp::getData();
            if ($this->checkPost($data)) {
                $write_id = NewsModel::WritePostToDb($data);
            }
        } else {
            HeaderSender::Send(400, "Id is not needed");
        }
        if ($write_id) {
            HeaderSender::Send(201, "Post created. Id => $write_id");
        } else {
            HeaderSender::Send(406, "Data entry error");
        }
    }

    private function PutAction($param)
    {
        $result = 0;
        if (self::checkParam($param)){
            $data = RequestHttp::getData();
            if ($this->checkPut($data)){
                $result =  NewsModel::EditPost($this->id, $data);
            }
        }else{
            HeaderSender::Send(400, "Need Id");
        }

        if($result){
            HeaderSender::Send(202, "Post is changed");
        }else{
            HeaderSender::Send(406, "Something went wrong");
        }

    }

    private function DeleteAction($param)
    {
        if (self::checkParam($param)){
            $result = NewsModel::DetelePost($this->id);
        }else{
            HeaderSender::Send(400, "Need Id");
        }

        if($result){
            HeaderSender::Send(410, "Post deleted");
        }else{
            HeaderSender::Send(406, "Something went wrong");
        }
    }

    private function checkParam($param)
    {
        if (!empty($param)) {
            if (ctype_digit($param[0])) {
                $this->id = $param[0];
                return true;
            } else {
                HeaderSender::Send(400, "Bad request");
            }
        }
        return false;
    }

    private function checkPost($data)
    {
        if (!is_array($data)) {
            HeaderSender::Send(204, "Invalid array format");
        } else {
            if (count($data) !== 4) {
                HeaderSender::Send(203, "Not enough array elements");
            }
        }
        $controlArray = array_fill_keys($this->requiredFields, 'null');
        $result = array_intersect_key($controlArray, $data);

        if (count($result) === 4) {
            return true;
        } else {
            HeaderSender::Send(203, "Array keys are not valid");
        }
        return false;
    }

    private function checkPut($data){
        if(is_array($data)){
            $controlArray = array_fill_keys($this->requiredFields, 'null');
            $result = array_intersect_key($controlArray, $data);
            if ($result){
                return true;
            }else{
                HeaderSender::Send(203, "Array keys are not valid");
            }
        }
        return false;
    }

}