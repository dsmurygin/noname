<?php

require_once __DIR__ . '/classes/db.class.php';
$db = new db;
$sql = 'SELECT * FROM category';
$query = $db->query($sql);

foreach ($query as $row){
    echo $row['category_name'] . '<br>';
}