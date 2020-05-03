<?php


class NewsModel
{
    public static function readAll(){
        $db =  DB::getConection();

        $query = "SELECT * FROM news";
        $result = $db->prepare($query);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_OBJ);
    }

    public static function readOne($id){
        $db = Db::getConection();

        $query = "SELECT * FROM news WHERE id = :id";
        $result = $db->prepare($query);
        $result->bindParam(':id', $id,PDO::PARAM_INT);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public static function WritePostToDb($data)
    {
        $db = Db::getConection();

        $query = "INSERT INTO news (`id`, `title`, `content`, `author`, `creation_date`) VALUES (NULL, :title, :content, :author, :creation_date);";
        $result = $db->prepare($query);

        foreach ($data as $field => &$value){
            $value = trim(htmlspecialchars($value));
            $result->bindParam(':'.$field, $value, PDO::PARAM_STR);
        }

        $result->execute();

        $id = $db->lastInsertId();

        return $id;
    }

    public static function EditPost($id, $data)
    {
        $db = Db::getConection();
        $dataString = "";

        foreach ($data as $field => $value){
            $dataString .= "$field = :$field, ";
        }

        $dataString = substr($dataString, 0, -2);

        $query = "UPDATE news SET $dataString WHERE news.`id` = $id";

        $result = $db->prepare($query);
        foreach ($data as $field => &$value){
            $value = trim(htmlspecialchars($value));
            $result->bindParam(':'.$field, $value, PDO::PARAM_STR);
        }
        $result->execute();

        return $result->rowCount();
    }

    public static function DetelePost($id)
    {

        $db = Db::getConection();

        $query = "DELETE FROM news WHERE news.`id` = :id";
        $result = $db->prepare($query);
        $result->bindParam(':id', $id,PDO::PARAM_INT);
        $result->execute();
        return $result->rowCount();
    }




}