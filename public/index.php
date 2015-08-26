<?php

$start = microtime(true);

/*
 * Composer libraries
 */

require __DIR__ . "/../vendor/autoload.php";

/*
 * App classes loader
 */

require_once "../app/Autoload/classAutoLoader.php";

new app\Autoload\classAutoLoader();

/*
 * Init application
 */

new app\Core\Run();

$stop = microtime(true) - $start;

//echo "<br><div style='color: #009900; font-size: 30px;'> Script execute time:<b style='color: red;'>". round($stop, 2) ."</b> </div><br>";