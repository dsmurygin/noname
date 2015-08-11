<?php
require_once __DIR__ . '/db.class.php';

class post{
    public $book_id;
    public $book_name;
    public $book_url;
    public $category = [];


    public function __construct($posts){
        foreach ($posts as $post){
            $this->book_id = $post['book_id'];
            $this->book_name = $post['book_name'];
            $this->book_url = $post['book_url'];
            $this->getCategory();
        }
    }
    public function getCategory(){
        $category = db::query('SElECT c.category_name, c.category_url FROM books b, category c, books_category bc WHERE bc.book_id = b.book_id AND bc.category_id = c.category_id AND b.book_id =' . $this->book_id);

        foreach ($category as $row){
            $this->category[] = ['name' => $row['category_name'], 'url' => $row['category_url']];
        }
    }
}