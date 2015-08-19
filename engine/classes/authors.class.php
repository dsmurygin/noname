<?php

class authors
{
    public $authors;
    public $listAuthors = '';

    public function getForBook($book_id){
        $db = new db;
        return $db->query('SElECT a.author_name,a.author_url FROM books b, authors a, books_authors ba
                                     WHERE ba.book_id = b.book_id AND ba.author_id = a.author_id AND b.book_id =' . $book_id);
    }

    public function getlistAuthors($book_id){
        $this->authors = $this->getForBook($book_id);
        $i = 1;
        foreach ($this->authors as $author){
            $this->listAuthors .= $author->author_name;
            if ($i !== count($this->authors)){
                $this->listAuthors .= ', ';
                $i++;
            }
        }
        return $this->listAuthors;
    }
}