<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once __DIR__ . '/engine/classes/db.class.php';
require_once __DIR__ . '/engine/classes/category.class.php';
require_once __DIR__ . '/engine/classes/post.class.php';


$cat = new category;
$category = $cat->getAll();

$db = new db();
$db->className = 'post';
$smt = $db->query('SELECT * FROM books b, publishers p
    WHERE b.publisher_id = p.publisher_id
    ORDER BY b.book_priority DESC LIMIT 0,10');
var_dump($smt);


