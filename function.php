<?php

function __autoload($class){
    if (file_exists(__DIR__ . '/engine/classes/' .$class . '.class.php')){
        require_once __DIR__ . '/engine/classes/' .$class . '.class.php';
    }
    elseif (file_exists(__DIR__ . '/engine/view/' .$class . '.php')){
        require_once __DIR__ . '/engine/view/' .$class . '.php';
    }
}

function getAllCategories(){
    $db = new db;
    return $db->query('SELECT category_name, category_url FROM category');
}

function getTop20(){
    $db = new db;
    $db->className = 'top20';
    return $db->query('SELECT book_name, book_url, book_votes, book_id FROM books ORDER by book_votes DESC LIMIT 0,20');
}