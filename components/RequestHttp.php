<?php

require_once ROOT . '/components/HeaderSender.php';

class RequestHttp
{

    public static function getData(){

        $data = file_get_contents('php://input'); //accept data in json form
        $data = json_decode($data, true); //turn into an associative array
        return $data;
    }
}