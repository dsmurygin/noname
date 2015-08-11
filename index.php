<?php
require_once __DIR__ . '/engine/classes/db.class.php';
require_once __DIR__ . '/engine/classes/post.class.php';

$db = new db || die;
if (!empty($_REQUEST['book'])){
    $post = new post(db::query('SELECT * FROM books b, publishers p WHERE b.publisher_id = p.publisher_id AND b.book_url = ?',array($_REQUEST['book'])));
}

echo '<pre>';
var_dump($post);

