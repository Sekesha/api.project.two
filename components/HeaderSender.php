<?php


class HeaderSender
{
    public static function Send($code, $massage = '')
    {
        $code = intval($code);
        http_response_code($code);      //sending http code

        //sending headers
        header("Access-Control-Allow-Methods: {$_SERVER['REQUEST_METHOD']}");
        header("Content-type: application/json");

        //sending data
        echo json_encode($massage, JSON_UNESCAPED_UNICODE);
        die();
    }
}