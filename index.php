<?php

require_once __DIR__ . '/classes/db.class.php';

$db = new db;
$rows = $db->query('SELECT * FROM category WHERE id = :id',array(':category_id'=>'1'));
var_dump($rows);

