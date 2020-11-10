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
    public function query($datasArr=[])
    {
        self::$res=self::$pdo->prepare(self::$sql);
        self::$res->execute($datasArr);
    }
    public static function table($table)
    {
        $db=new DB();
        $mysql="select * from $table";
        self::$sql=$mysql;
        
        return $db;
    }

    public function get()
    {
        $this->query();
        self::$datas=self::$res->fetchAll(PDO::FETCH_OBJ);
        return self::$datas;
    }

    public function count()
    {
        $this->query();
        self::$count=self::$res->rowCount();
        return self::$count;
    }

    public function orderBy($orderBy, $value="ASC")
    {
        $sql=" order by $orderBy $value";
        self::$sql.=$sql;
        
        return $this;
    }
    public function latest()
    {
        $this->orderBy("id", "desc");
        return $this;
    }
    public function first()
    {
        $this->query();
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
       
        return $this;
    }
    public function andWhere($col, $operator, $value='')
    {
        if (func_num_args()===2) {
            $sql=" and $col = '$operator'";
            self::$sql.=$sql;
        }
        if (func_num_args()===3) {
            $sql=" and $col $operator '$value'";
            self::$sql.=$sql;
        }
       
        return $this;
    }
    public function orWhere($col, $operator, $value='')
    {
        if (func_num_args()===2) {
            $sql=" or $col = '$operator'";
            self::$sql.=$sql;
        }
        if (func_num_args()===3) {
            $sql=" or $col $operator '$value'";
            self::$sql.=$sql;
        }
       
        return $this;
    }

    // create
    public static function create($table, $datasArr)
    {
        $getDatasKey=array_keys($datasArr);
        $cols=implode(",", $getDatasKey);
        $unknownDataPdoField="";
        foreach ($getDatasKey as $key) {
            // var_dump($key);
            $unknownDataPdoField.="?,";
        }
        $questionMark=rtrim($unknownDataPdoField, ",");
        $sql="insert into $table ($cols) values ($questionMark)";
        self::$sql=$sql;
        $db=new DB();
        $datas=array_values($datasArr);
        $db->query($datas);
        $inserted_id=self::$pdo->lastInsertId();
        $new_user=DB::table($table)->where("id", $inserted_id)->first();
        return $new_user;
    }
    public static function update($table, $datas, $id)
    {
        $cols=implode('=?, ', array_keys($datas));
        $cols.="=?";
        $sql="update $table set $cols where id=$id";
        self::$sql=$sql;
        $db=new DB();
        $dataValuesArr=array_values($datas);
        $db->query($dataValuesArr); //after execute
        $update_user =DB::table($table)->where("id", $id)->first() ;
        return $update_user;
    }
}

$new_user=DB::update("users", [
    "name"=>"mgmg",
    "image"=>"mgmg image",
    "location"=>"chaung thar",
], 12);
var_dump($new_user);
