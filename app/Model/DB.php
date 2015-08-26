<?php


namespace app\Model;


class DB {

    protected $settings = "/../Config/Database.php";

    protected $PDO;

    public function __construct()
    {
        $settings = require_once __DIR__.$this->settings;

        $host = $settings["mysql"]["host"];
        $user = $settings["mysql"]["user"];
        $pass = $settings["mysql"]["password"];
        $name = $settings["mysql"]["database"];
        $port = $settings["mysql"]["port"];

        try {

            $PDO = new \PDO(
                "mysql:host=$host;port=$port;dbname=$name",
                $user,
                $pass
            );
            $this->PDO = new \FluentPDO($PDO);

        } catch (\Exception $e)
        {
            $this->PDOException($e->getMessage());
        }
    }

    protected function PDOException($exception)
    {
        throw new \Exception($exception);
    }

    public function PDO()
    {
        return $this->PDO;
    }



}