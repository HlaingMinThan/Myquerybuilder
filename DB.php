<?php

class DB
{
    protected static $pdo;
    protected static $sql;
    protected static $datas;
    protected static $res;
    protected static $count;
    public function __construct()
    {
        self::$pdo=new PDO("mysql:host=localhost;dbname=pdoCrud", "admin", 123456);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //pdo error show mode on
    }
    public function query()
    {
        self::$res=self::$pdo->prepare(self::$sql);
        self::$res->execute();
    }
    public static function table($table)
    {
        $db=new DB();
        $mysql="select * from $table";
        self::$sql=$mysql;
        $db->query();
        return $db;
    }

    public function get()
    {
        self::$datas=self::$res->fetchAll(PDO::FETCH_OBJ);
        return self::$datas;
    }

    public function count()
    {
        self::$count=self::$res->rowCount();
        return self::$count;
    }
}

$number_of_users=DB::table("users")->count();
var_dump($number_of_users);
