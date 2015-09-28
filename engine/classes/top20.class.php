<?php

class top20{
    public $book_id;
    public $authors = [];

    public function __construct(){
        $authors = new authors();
        $this->authors = $authors->getForBook($this->book_id);
    }
}