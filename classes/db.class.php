<?php
class db{
    private $link;

    function __construct(){
        $this->link = new PDO('mysql:host=localhost;dbname=audiobooks;charset=utf8', 'web', 'Noname1990', array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT         => true
        ));
    }

    private function checkParam($url){
        return preg_match("/^[a-zA-Z0-9-_]{2,80}$/",$url);
    }

    public function query($sql, $params = null) {
        if (is_null($params)){
            $query = $this->link->query($sql);
            $res = $query->fetchAll();
        }
        else{
            foreach ($params as $row){
                if ($this->checkParam($row)){
                    return false;
                }
            }

            $query = $this->link->prepare($sql);
            $query->execute($params);
            $res = $query->fetchAll();
        }
        return $res;
    }

    public function test(){
        $arr = array('category_id'=>'1');
        $sql = 'SELECT * FROM category';
        $sql .= ' WHERE';
        $bind = [];
        foreach ($arr as $key => $val){
            $sql .= ' ' . $key . ' = :' .$key;
            $bind[':' . $key] = $val;
        }

        echo $sql;
        $query = $this->link->prepare($sql);
        var_dump($bind);
        $query->execute($bind);
        $rows = $query->fetchAll();
        var_dump($rows);
    }
}