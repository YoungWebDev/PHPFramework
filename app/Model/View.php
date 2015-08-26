<?php

namespace app\Model;


class View {

    public static function give($view, $variables = false)
    {
        $dir = __DIR__ . "/../View/$view.php";

        ob_start();

        require $dir;

        $content = ob_get_contents();

        if ($variables !== false)
        {
            foreach ($variables as $var => $val)
            {
                $content = str_replace("{{ $$var }}", $val, $content);
            }
        }

        ob_end_clean();

        return $content;

    }


}