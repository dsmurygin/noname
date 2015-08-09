<?php
class db{
    private $link;

    function __construct(){
        $this->link = new PDO('mysql:host=localhost;dbname=audiobooks;charset=utf8', 'test', 'test', array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT         => true
        ));
    }

    private function checkParam($url){
        return preg_match("/^[a-zA-Z0-9-_]{2,80}$/",$url);
    }

    public function query($sql, $nameParams = null, $params = null) {
        if (is_null($params) || is_null($nameParams)){
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
            $query->execute(array($params));
            $res = $query->fetchAll();
        }
        return $res;
    }
}