<?php
function db_Link(){
    $pdo = new PDO('mysql:host=localhost;dbname=audiobooks;charset=utf8', 'web', 'Noname1990', array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_PERSISTENT         => true
    ));
    return $pdo;
}

function checkParam($url){
    return preg_match("/^[a-zA-Z0-9-_]{2,80}$/",$url);
}
