<?php

class content
{
    protected $pageQuery;
    public $data = [];

/*if($_REQUEST['category']) {
$documentTitle = 'Аудиокниги по жанру '.$row['category_name'].' слушать онлайн';
$documentDescription .= '- аудиокниги по жанру '.$row['category_name'];
$documentKeywords .= ', жанр , '.$row['category_name'];
}
else if($_REQUEST['author']) {
    $documentTitle = 'Аудиокниги автора ' . $row['author_name'] . ' слушать онлайн';
    $documentDescription .= '- аудиокниги автора ' . $row['author_name'];
    $documentKeywords .= ', автор , ' . $row['author_name'];
}
else if($_REQUEST['publisher']){
    $documentTitle = 'Аудиокниги издательства ' . $row['publisher_name'] . ' слушать онлайн';
    $documentDescription .= '- аудиокниги издательства ' . $row['publisher_name'];
    $documentKeywords .= ', издательство , ' . $row['publisher_name'];
}
else if($_REQUEST['year']){
    $documentTitle = 'Аудиокниги '.$_REQUEST['year'].' года слушать онлайн';
    $documentDescription .= '- аудиокниги '.$_REQUEST['year'].' года';
    $documentKeywords .= ', '.$_REQUEST['year'].' года';
}
else if(isset($_REQUEST['voice'])){
    $documentTitle = 'Аудиокниги исполнителя ' . $row['voice_name'] . ' слушать онлайн';
    $documentDescription .= '- аудиокниги в исполнении ' . $row['voice_name'];
    $documentKeywords .= ', '.$row['voice_name'] . ', исполнитель , озвучил, озвучка';
}
else{
    $documentTitle = 'Аудиокниги слушать онлайн бесплатно';
    $documentDescription = 'Слушать аудиокниги онлайн. Огромная коллекция. Ежедневное обновление. Возможность сохранения времени прослушивания и бесплатного скачивания аудиокниг.';
}*/

    public function __construct()
    {
        if (isset($_REQUEST['page'])) {
            $this->pageQuery = ' LIMIT ' . (((int)$_REQUEST['page'] - 1) * 10) . ',10';
        } else {
            $this->pageQuery = ' LIMIT 0,15';
        }

        if (isset($_REQUEST['book'])){
            $db = new db;
            $db->className = 'post';
            $this->content = $db->query('SELECT * FROM books b, publishers p WHERE b.publisher_id = p.publisher_id AND b.book_url = :book',[':book' => $_REQUEST['book']]);
            $this->title = 'Аудиокнига ' . $this->content->book_name . ' - ' . $authors['authors'] . ' слушать онлайн';
            $documentDescription = 'Аудиокнига ' . $row['book_name'] . ' автора ' . $authors['authors'] . ' слушать онлайн бесплатно, без регистрации';
            $documentKeywords .= ', ' . $authors['authors'] . ' , ' . $row['book_name'];
        }

        else if(isset($_REQUEST['category'])){
            $db = new db;
            $db->className = 'post';
            $this->content = $db->query('SELECT * FROM books b, publishers p, category c, books_category bc
                        WHERE b.publisher_id = p.publisher_id AND bc.category_id = c.category_id AND bc.book_id = b.book_id AND c.category_url = :category
                        ORDER BY b.book_priority DESC' . $this->pageQuery, [':category' => $_REQUEST['category']]);
        }

        else if (isset($_REQUEST['author'])){
            $db = new db;
            if (empty($_REQUEST['author'])) {
                $this->list = true;
                $this->content = $db->query('SELECT * FROM authors ORDER BY author_name');
            }
            else{
                $db->className = 'post';
                $this->content = $db->query('SELECT * FROM books b, publishers p, authors a, books_authors ba
                                             WHERE b.publisher_id = p.publisher_id AND ba.book_id = b.book_id AND ba.author_id = a.author_id AND a.author_url = :author
                                             ORDER BY b.book_priority DESC' . $this->pageQuery,[':author'=> $_REQUEST['author']]);
            }

        }

        else if(isset($_REQUEST['publisher'])){
            $db = new db;
            if (!empty($_REQUEST['publisher'])){
                $db->className = 'post';
                $this->content = $db->query('SELECT * FROM books b, publishers p
                                             WHERE b.publisher_id = p.publisher_id  AND p.publisher_url= :publisher
                                             ORDER BY b.book_priority DESC' . $this->pageQuery,[':publisher' => $_REQUEST['publisher']]);
            }
            else{
                $this->list = true;
                $this->content = $db->query('SELECT * FROM publishers ORDER BY publisher_name');

            }
        }

        else if(isset($_REQUEST['voice'])) {
            $db = new db;
            if (empty($_REQUEST['voice'])){
                $this->list = true;
                $this->content = $db->query('SELECT * FROM voices ORDER BY voice_name');
            }
            else{
                $db->className = 'post';
                $this->content = $db->query('SELECT * FROM books b, publishers p, voices v, books_voices bv
                                             WHERE b.publisher_id = p.publisher_id AND bv.book_id = b.book_id AND bv.voice_id = v.voice_id AND v.voice_url = :voice
                                             ORDER BY b.book_priority DESC' . $this->pageQuery,[':voice' => $_REQUEST['voice']]);
            }
        }

        else{
            $db = new db;
            $db->className = 'post';
            $this->content = $db->query('SELECT * FROM books b, publishers p WHERE b.publisher_id = p.publisher_id ORDER BY b.book_priority DESC' . $this->pageQuery);
        }

        if (count($this->content)<10){
            $this->eof = true;
        }
        else{
            $this->eof = false;
        }

        if (count($this->content) == 0){
            $this->found = false;
        }

        else $this->found = true;
    }

    public function __set($name,$value){
        $this->data[$name] = $value;
    }

    public function  __get($name){
        return $this->data[$name];
    }

    public function listAuthors(){
        $i = 1;
        foreach ($this->content->authors as $author){
            $this->listAuthors = $author->author_name;
            if ($i !== count($this->content->authors)){
                $this->listAuthors .= ', ';
                $i++;
            }
        }
    }
}