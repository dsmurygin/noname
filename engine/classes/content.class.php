<?php

class content
{
    private $pageQuery;
    private $listAuthors = '';
    private $data = [];

    public function __construct()
    {
        $db = new db;
        $this->title = '';
        $this->description = 'Аудиокниги слушать онлайн бесплатно без регистрации ';
        $this->keywords = 'аудиокниги, слушать, онлайн, бесплатно';
        if (isset($_REQUEST['page'])) {
            $this->pageQuery = ' LIMIT ' . (((int)$_REQUEST['page']) * 10) . ',10';
        } else {
            $this->pageQuery = ' LIMIT 0,10';
        }

        if (isset($_REQUEST['book'])){
            $db->className = 'post';
            $this->content = $db->query('SELECT * FROM books b, publishers p WHERE b.publisher_id = p.publisher_id AND b.book_url = :book',[':book' => $_REQUEST['book']])[0];

            $this->eof();
            $this->listAuthors();
            $this->title = 'Аудиокнига ' . $this->content->book_name . ' - ' . $this->listAuthors . ' слушать онлайн';
            $this->description = 'Аудиокнига ' . $this->content->book_name . ' автора ' . $this->listAuthors . ' слушать онлайн бесплатно, без регистрации';
            $this->keywords .= ', ' . $this->listAuthors . ' , ' . $this->content->book_name;
        }

        else if(isset($_REQUEST['category'])){
            $db->className = 'post';
            $this->content = $db->query('SELECT * FROM books b, publishers p, category c, books_category bc
                        WHERE b.publisher_id = p.publisher_id AND bc.category_id = c.category_id AND bc.book_id = b.book_id AND c.category_url = :category
                        ORDER BY b.book_priority DESC' . $this->pageQuery, [':category' => $_REQUEST['category']]);

            $this->eof();
            $this->title = 'Аудиокниги по жанру ' . $this->content[0]->category_name . ' слушать онлайн';
            $this->description .= '- аудиокниги по жанру ' . $this->content[0]->category_name;
            $this->keywords .= ', жанр , ' . $this->content[0]->category_name;
        }

        else if (isset($_REQUEST['author'])){
            if (empty($_REQUEST['author'])) {
                $this->content = $db->query('SELECT author_name, author_url FROM authors ORDER BY author_name');
                $this->eof = true;
                $this->title = 'Список авторов онлайн аудиокниг';
                $this->description .= '- список авторов';
                $this->keywords .= ', список авторов, авторы';
            }
            else{
                $db->className = 'post';
                $this->content = $db->query('SELECT * FROM books b, publishers p, authors a, books_authors ba
                                             WHERE b.publisher_id = p.publisher_id AND ba.book_id = b.book_id AND ba.author_id = a.author_id AND a.author_url = :author
                                             ORDER BY b.book_priority DESC' . $this->pageQuery,[':author'=> $_REQUEST['author']]);

                $this->eof();
                $this->title = 'Аудиокниги автора ' . $this->content[0]->author_name . ' слушать онлайн';
                $this->description .= '- аудиокниги автора ' . $this->content[0]->author_name;
                $this->keywords .= ', автор , ' . $this->content[0]->author_name;
            }

        }

        else if(isset($_REQUEST['publisher'])){
            if (!empty($_REQUEST['publisher'])){
                $db->className = 'post';
                $this->content = $db->query('SELECT * FROM books b, publishers p
                                             WHERE b.publisher_id = p.publisher_id  AND p.publisher_url= :publisher
                                             ORDER BY b.book_priority DESC' . $this->pageQuery,[':publisher' => $_REQUEST['publisher']]);

                $this->eof();
                $this->title = 'Аудиокниги издательства ' . $this->content[0]->publisher_name . ' слушать онлайн';
                $this->description .= '- аудиокниги издательства ' . $this->content[0]->publisher_name;
                $this->keywords .= ', издательство , ' . $this->content[0]->publisher_name;
            }
            else{
                $this->eof = false;
                $this->content = $db->query('SELECT * FROM publishers ORDER BY publisher_name');
                $this->title = 'Список издательств онлайн аудиокниг';
                $this->description .= '- список издательств';
                $this->keywords .= ', список издательств, издательства';

            }
        }

        else if(isset($_REQUEST['voice'])) {
            if (empty($_REQUEST['voice'])){
                $this->content = $db->query('SELECT * FROM voices ORDER BY voice_name');
                $this->eof = false;
                $this->title = 'Список исполнителей онлайн аудиокниг';
                $this->description .= '- список исполнителей аудиокниг';
                $this->keywords .= ', список исполнителей, исполнители, озвучил, озвучка';
            }
            else{
                $db->className = 'post';
                $this->content = $db->query('SELECT * FROM books b, publishers p, voices v, books_voices bv
                                             WHERE b.publisher_id = p.publisher_id AND bv.book_id = b.book_id AND bv.voice_id = v.voice_id AND v.voice_url = :voice
                                             ORDER BY b.book_priority DESC' . $this->pageQuery,[':voice' => $_REQUEST['voice']]);

                $this->eof();
                $this->title = 'Аудиокниги исполнителя ' . $this->content[0]->voice_name . ' слушать онлайн';
                $this->description .= '- аудиокниги в исполнении ' . $this->content[0]->voice_name;
                $this->keywords .= ', '. $this->content[0]->voice_name . ', исполнитель , озвучил, озвучка';
            }
        }

        else{
            $db->className = 'post';
            $this->content = $db->query('SELECT * FROM books b, publishers p WHERE b.publisher_id = p.publisher_id ORDER BY b.book_priority DESC' . $this->pageQuery);
            $this->eof();
            $this->title = 'Аудиокниги слушать онлайн бесплатно';
            $this->description = 'Слушать аудиокниги онлайн. Огромная коллекция. Ежедневное обновление. Возможность сохранения времени прослушивания и бесплатного скачивания аудиокниг.';
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
        $this->listAuthors = '';
        foreach ($this->content->authors as $author){
            $this->listAuthors .= $author->author_name;
            if ($i !== count($this->content->authors)){
                $this->listAuthors .= ', ';
                $i++;
            }
        }
    }

    private function eof(){
        if (count($this->content)<10){
            $this->eof = true;
        }
        else{
            $this->eof = false;
        }
    }
}