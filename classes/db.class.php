<?php
class db{
    private static $link;

    function __construct(){
        self::$link = new PDO('mysql:host=localhost;dbname=audiobooks;charset=utf8', 'web', 'Noname1990', array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT         => true
        ));
    }

    public static function query($sql, $params = null) {
        if (is_null($params)){
            echo '1';
            $query = self::$link->query($sql);
            $res = $query->fetchAll();
        }
        else{
            echo '1';
            $query = self::$link->prepare($sql);
            $query->execute($params);
            $res = $query->fetchAll();
        }
        return $res;
    }
}