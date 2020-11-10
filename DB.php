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

    public function orderBy($orderBy, $value="ASC")
    {
        $sql=" order by $orderBy $value";
        self::$sql.=$sql;
        $this->query();
        return $this;
    }
}
// SELECT * FROM `users` order BY id DESC;
$users_order_desc=DB::table("users")->orderBy("name")->get();
var_dump($users_order_desc);
