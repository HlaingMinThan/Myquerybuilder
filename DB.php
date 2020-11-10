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
    public function latest()
    {
        $this->orderBy("id", "desc");
        return $this;
    }
    public function first()
    {
        self::$datas=self::$res->fetch(PDO::FETCH_OBJ);
        return self::$datas;
    }
    public function where($col, $operator, $value='')
    {
        if (func_num_args()===2) {
            $sql=" where $col = '$operator'";
            self::$sql.=$sql;
        }
        if (func_num_args()===3) {
            $sql=" where $col $operator '$value'";
            self::$sql.=$sql;
        }
        $this->query();
        return $this;
    }
}


$users=DB::table("users")->where("name", "god")->get();//get all users who name is god


$user=DB::table("users")->where("name", "god")->first();//get first users who name is god


$users=DB::table("users")->where("name", "god")->latest()->get();//get all users who name is god by descending
