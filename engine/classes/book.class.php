<?php
class book{
    public $id;
    public $cycle;
    public $rating;
    public $votes;
    public $schema = false;

    public function getAuthors(){
        global $pdo;
        $authors = '';
        $linkAuthors = '';
        $i = 0;
        $pdo = new db;
        $query = $pdo->query('SElECT a.author_name,a.author_url FROM books b, authors a, books_authors ba WHERE ba.book_id = b.book_id AND ba.author_id = a.author_id AND b.book_id =' . $this->id);
        foreach ($query as $item){
            $linkAuthors .= '<a href="/author/' . $item['author_url'] . '" onclick="showContent(\'author\',\'' . $item['author_url'] . '\',\'\');return false">';
            if ($this->schema){
                $linkAuthors .= '<span itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name">';
            }
            $linkAuthors .= $item['author_name'] . '</a>';
            if ($this->schema){
                $linkAuthors .= '</span></span>';
            }
            $authors .= $item["author_name"];
            $i++;
            if ($i !== count($row)){$authors .= ', '; $linkAuthors .= ', ';}
        }
        return array('authors'=>$authors,'linkAuthors'=>$linkAuthors);
    }

    public function getCategory(){
        global $pdo;
        $category ='';
        $i = 0;
        $query = $pdo->query('SElECT c.category_name, c.category_url FROM books b, category c, books_category bc WHERE bc.book_id = b.book_id AND bc.category_id = c.category_id AND b.book_id =' . $this->id);
        $row = $query->fetchAll();
        foreach ($row as $item){
            $category .= '<a href="/category/' . $item['category_url'] . '" onclick="showContent(\'category\',\'' . $item['category_url'] . '\',\'\');return false">';
            if ($this->schema){
                $category .= '<span itemprop="additionalType">';
            }
            $category .= $item['category_name'];
            if ($this->schema){
                $category .= '</span>';
            }
            $category .= '</a>';
            $i++;
            if ($i !==count($row)) $category .= ', ';
        }
        return $category;
    }

    public function getVoices(){
        global $pdo;
        $voices = ''; $i = 0;
        $query = $pdo->query('SElECT v.voice_name, v.voice_url FROM books b, voices v, books_voices bv WHERE bv.book_id = b.book_id AND bv.voice_id = v.voice_id AND b.book_id =' . $this->id);
        $row = $query->fetchAll();
        foreach ($row as $item){
            $voices .= '<a href="/voice/' . $item['voice_url'] . '" onclick="showContent(\'voice\',\'' . $item['voice_url'] . '\',\'\');return false">';
            if($this->schema){
                $voices .= '<span itemprop="bookEdition">';
            }
            $voices .= $item['voice_name'];
            if ($this->schema){
                $voices .= '</span>';
            }
            $voices .= '</a>';
            $i++;
            if ($i !== count($row)) $voices .= ', ';
        }
        return $voices;
    }

    public function getCycle(){
        global $pdo;
        $cycle = '';
        if ($this->cycle){
            $query = $pdo->query('SELECT * FROM book_cycle WHERE cycle_id = '.$this->cycle);
            $row = $query->fetch();
            $cycle = '<div class="cycle">
                        <span><b>Цикл \ серия: '.$row["cycle_name"].' </b></span>
                        <div class="cycleRows">';
            $query = $pdo->query('SELECT book_url, book_name FROM books WHERE book_cycle = '.$this->cycle.' ORDER BY book_priority');
            $i = 1;
            while ($row = $query->fetch()){
                $cycle .= $i.'. <a href="/book/' . $row['book_url'] . '" onclick="showContent(\'book\',\'' . $row['book_url'] . '\',\'\');return false">'.$row['book_name'].'</a><br>';
                $i++;
            }
            $cycle .= '</div></div>';
        }
        return $cycle;
    }

    public function calcRating(){
        $rating = 5;
        if ($this->votes!=0){
            $rating = round($this->rating/$this->votes,1);
        }
        return $rating;
    }
}