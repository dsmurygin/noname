<?php
class db{
    private static $link;
    public $className = 'stdClass';

    function __construct(){
        self::$link = new PDO('mysql:host=localhost;dbname=audiobooks;charset=utf8', 'web', 'Noname1990', array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS,
            PDO::ATTR_PERSISTENT         => true
        ));
    }

    public function query($sql, $params = []) {
        $query = self::$link->prepare($sql);
        $query->execute($params);

        return $query->fetchAll(PDO::FETCH_CLASS, $this->className);
    }
}