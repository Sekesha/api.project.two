<?php


class Db
{
    public static function getConection()
    {
        $paramPath = ROOT . '/config/db_params.php';
        $params = include($paramPath);

        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        try {
            $db = new PDO($dsn, $params['user'], $params['password']);
        }catch (PDOException $e){
            die("Не могу подключиться к базе данных");
        }
        return $db;
    }

}