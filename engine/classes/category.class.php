<?php
class category
{
    public function getAll(){
        $db = new db;
        $db->className = 'category';
        return $db->query('SELECT * FROM category');
    }

    public function getForBook($id){
        $db = new db;
        $db->className = 'category';
        return $db->query('SElECT c.category_name, c.category_url FROM books b, category c, books_category bc
                           WHERE bc.book_id = b.book_id AND bc.category_id = c.category_id AND b.book_id = :id',[':id'=>$id]);
    }
}