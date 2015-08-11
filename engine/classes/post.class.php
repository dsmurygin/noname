<?php

class post{
    public $book_id;
    public $authors = [];
    public $categories = [];
    public $voices = [];
    
    public function __construct(){
        $db = new db;
        $this->authors = $db->query('SElECT a.author_name,a.author_url FROM books b, authors a, books_authors ba
                                     WHERE ba.book_id = b.book_id AND ba.author_id = a.author_id AND b.book_id =' . $this->book_id);

        $this->categories = $db->query('SElECT c.category_name, c.category_url FROM books b, category c, books_category bc
                                        WHERE bc.book_id = b.book_id AND bc.category_id = c.category_id AND b.book_id =' . $this->book_id);

        $this->voices = $db->query('SElECT v.voice_name, v.voice_url FROM books b, voices v, books_voices bv
                                    WHERE bv.book_id = b.book_id AND bv.voice_id = v.voice_id AND b.book_id =' . $this->book_id);
    }

}