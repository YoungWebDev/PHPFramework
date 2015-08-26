<?php

namespace app\Core;


class App {

    public static function redirect($code)
    {
        if ( $code === 404 )
        {
            return "Error 404 - Page not found";
        }

        header("Location: $code");

    }

    public static function get($key)
    {
        $preValue = trim($_POST[$key]);

        $value = $preValue === "system" ? "*" : $preValue;

        return htmlentities($value);
    }

}