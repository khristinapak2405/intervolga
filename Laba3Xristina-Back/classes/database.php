<?php
class Database
{
    private static $instance=null;
    private $connect;

    protected function __construct()
    {
        $this->connect=mysqli_connect('localhost','root','','laba3xristina');
        mysqli_set_charset($this->connect,'utf8');

    }

    protected function __clone()
    {
    }
    public function __wakeup() //must be protected, need to fix other things first
    {
        throw new \BadMethodCallException('Unable to deserialize database connection!');
    }
    public static function getInstance(): Database{
        if (self::$instance===null)
            self::$instance=new static();
        return self::$instance;
    }
    public static function connection():\mysqli{
        return static::getInstance()->connect;
    }
}