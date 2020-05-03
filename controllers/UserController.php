<?php

class UserController
{
    public static function checkAuth()
    {
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
        } else {
            return true;
        }
        return false;
    }
}