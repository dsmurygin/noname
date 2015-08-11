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
            $query = self::$link->query($sql);
        }
        else{
            $query = self::$link->prepare($sql);
            $query->execute($params);
        }

        return $query->fetchAll();
    }
}