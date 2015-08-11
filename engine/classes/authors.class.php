<?php

class authors
{
    public function getForBook($book_id){
        $db = new db;
        $this->authors = $db->query('SElECT a.author_name,a.author_url FROM books b, authors a, books_authors ba
                                     WHERE ba.book_id = b.book_id AND ba.author_id = a.author_id AND b.book_id =' . $book_id);
    }
}