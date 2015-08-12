<?php
if (!isset($link)) include 'dbConnect.php';
require_once 'engine/classes/db.class.php';
require_once 'engine/classes/book.class.php';
$category='';
$content ='';
$book='';
$dopTable = '';
$pageQuery = '';
$search = false;
$documentTitle = '';
$documentDescription = 'Аудиокниги слушать онлайн бесплатно без регистрации ';
$documentKeywords = 'аудиокниги, слушать, онлайн, бесплатно';
$found = true;
$eof = false;
if (!empty($_REQUEST['page']))  $pageQuery = ' LIMIT '.(((int)$_REQUEST['page']-1)*10).',10'; else {$pageQuery = ' LIMIT 0,10'; $page = 1;}

function showCategory( $documentInfo = false){
    global $link,$category,$dopTable,$pageQuery,$page,$search,$content,$documentTitle,$documentDescription,$documentKeywords,$found,$eof;
    $query = $link->query('SELECT * FROM books b, publishers p '.$dopTable.'
    WHERE b.publisher_id = p.publisher_id '.$category.'
    ORDER BY b.book_priority DESC'.$pageQuery);
    if ($query->num_rows < 10)  $eof = true;  else $eof = false;
    if ($query->num_rows == 0) {
        if ($eof && $page==1&&!$search){
            $content .= '<b>Данной страницы несуществует </b>';
            $found = false;
        }
        return false;
    }
    else {
        while ($row = mysqli_fetch_assoc($query)) {
            $book = new book();
            $book->id = $row['book_id'];
            $authors = $book->getAuthors();
            //ЗОГОЛОВКИ
            if(!$documentInfo){
                if($_REQUEST['category']) {
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
                }
                $documentInfo = true;
            }
            //вывод списка книг
            $content .= '<div class="post_box post_box_border" id = '.$row["book_id"].'>
                <div class="post_title">
                    <h2><a href="/book/' . $row['book_url'] . '" onclick="showContent(\'book\',\'' . $row['book_url'] . '\',\'\',\''.$row["book_id"].'\');return false">'.$authors['authors'].' «'.$row['book_name'].'»</a></h2>
                </div>';
            if ($row['other_voice']){
                $content .= '<div class="bookPlay" onclick="showContent(\'book\',\'' . $row['book_url'] . '\',\'\');return false">';
            }
            else{
                $content .= '<div class="bookPlay" onclick="playBook(\'' . $row['book_id'] . '\',\'' . $row['book_url'] . '\',\'' .$authors['authors']. ' - ' . $row['book_name'] . '\')">
                <div class="bookControls">
                    <div class="bookPlayIcon"><img src="/images/bookPlayIcon.png" alt=""/></div>
                </div>';

            }
            $content .='<img src="/books/images/' . $row['book_url'] . '.' . $row['book_img'] . '" alt="Аудиокнига '.$authors['authors'].' - '.$row['book_name'].'" title="Аудиокнига '.$authors['authors'].' - '.$row['book_name'].'" />
            </div>
            <div class="info">
                <b>Жанр: </b>' .$book->getCategory().'<br>
                <b>Автор: </b>'.$authors['linkAuthors'].'<br>
                <b>Озвучил: </b>'.$book->getVoices().'<br>
                <b>Издательство: </b><a href="/publisher/' . $row['publisher_url'] . '" onclick="showContent(\'publisher\',\'' . $row['publisher_url'] . '\',\'\');return false">' . $row['publisher_name'] . '</a><br>
                <b>Год: </b><a href="/year/' . $row['book_year'] . '" onclick="showContent(\'year\',\'' . $row['book_year'] . '\',\'\');return false">' . $row['book_year'] . '</a><br>
                <b>Длительность: ' . $row['voice_time'] . '</b><br>';
                if (strlen($row['book_description'])>300){$description = mb_substr($row['book_description'], 0, 300, 'UTF-8').' . . .';}else $description =$row['book_description'];
            $content .= '
                <p ><b>Описание: </b>' . $description . '</p>
            </div>
        </div>';
        }
    }
    return true;
}

//ПОЛНАЯ ВЕРСИЯ ПОСТА БЕЗ разметки shema

function onPageLoad(){
    global $link, $content;
    $query = $link->query('SELECT * FROM books b, publishers p WHERE b.publisher_id = p.publisher_id AND b.book_url="'.$_REQUEST['book'].'"');    
    $row = mysqli_fetch_assoc($query);
    $book = new book();
    $book->id = $row['book_id'];
    $book->cycle = $row['book_cycle'];
    $book->rating = $row['book_rating'];
    $book->votes = $row['book_votes'];
    $authors = $book->getAuthors();
    $rating = $book->calcRating();
    $content .= '<h2 class = "post-title">'.$authors['authors'].' «'.$row['book_name'].'»</h2>
        <div class="rating">
            <div class="vote">';
            for ($i = 1; $i < 11; $i++){
                $content .= '<span onclick="vote('.$row['book_id'].',\''. $i .'\')">'. $i .'</span><br>';
            }
            $content .= '
                </div>
                    <b>' . $rating . '/10</b><br>
                    <small>(голосов: '.$row['book_votes'].')</small>
                </div>
                <div class="bookPlay">
                    <div class="bookControls" onclick="playBook(\'' . $row['book_id'] . '\',\'' . $row['book_url'] . '\',\'' .$authors['authors']. ' - ' . $row['book_name'] . '\')">
                        <div class="bookPlayIcon"><img src="/images/bookPlayIcon.png" alt=""/></div>
                            <img src="/books/images/' . $row['book_url'] . '.' . $row['book_img'] . '" alt="Аудиокнига '.$authors['authors'].' - '.$row['book_name'].'"/>
                            <a>Слушать онлайн</a>
                        </div>';
            if (file_exists('books/torrent/'.$row['book_url'].'.torrent')) $content .='<a href = "/books/torrent/'.$row['book_url'].'.torrent " download="'.$row['book_url'].'.torrent" rel="nofollow">Скачать .torrent</a>';
            $content .='</div>
            <div class="info">
                <b>Жанр: </b>'.$book->getCategory().'<br>
                <b>Автор: </b>'.$authors['linkAuthors'].'<br>
                <b>Озвучил: </b>'.$book->getVoices().'<br>
                <b>Издательство: </b><a href="/publisher/' . $row['publisher_url'] . '" onclick="showContent(\'publisher\',\'' . $row['publisher_url'] . '\',\'\');return false">' . $row['publisher_name'] . '</a><br>'
                . $book->getCycle().'
                <b>Год: </b><a href="/year/' . $row['book_year'] . '" onclick="showContent(\'year\',\'' . $row['book_year'] . '\',\'\');return false">'
                . $row['book_year'] . '</span></a><br>
                <b>Длительность: '.$row['voice_time'].'</b><br>
                <p ><b>Описание: </b>'.$row['book_description'].'</p>
            </div>';
}

if(!empty($_REQUEST['category'])) {
    $category = ' AND bc.category_id = c.category_id AND bc.book_id = b.book_id AND c.category_url="' . $_REQUEST["category"] . '"';
    $dopTable = ', category c, books_category bc';
    showCategory();
}

else if (isset($_POST['onPageLoad'])){
    $category = 'AND b.book_url="'.$_REQUEST['book'].'"';
    onPageLoad();
}

else if(isset($_REQUEST['author'])) {
    if ($_REQUEST["author"]==''){
        $eof = true;
        $query=$link->query('SELECT * FROM authors ORDER BY author_name');
        while ($row=mysqli_fetch_assoc($query)){
            $content .= '<a href="/author/'.$row['author_url'].'" onclick="showContent(\'author\',\''.$row['author_url'].'\',\'\');return false">'.$row['author_name'].'</a>
            <br>';
        }
        $documentTitle = 'Список авторов онлайн аудиокниг';
        $documentDescription .= '- список авторов';
        $documentKeywords .= ', список авторов, авторы';
    }
    else {
        $category = ' AND ba.book_id = b.book_id AND ba.author_id = a.author_id AND a.author_url = "'.$_REQUEST["author"].'"';
        $dopTable = ', authors a, books_authors ba';
        showCategory();
    }
}

else if(isset($_REQUEST['voice'])) {
    if ($_REQUEST['voice']==''){
        $eof = true;
        $query=$link->query('SELECT * FROM voices ORDER BY voice_name');
        while ($row=mysqli_fetch_assoc($query)){
            $content .= '<a href="/voice/'.$row['voice_url'].'" onclick="showContent(\'voice\',\''.$row['voice_url'].'\',\'\');return false">'.$row['voice_name'].'</a>
            <br>';
        }
        $documentTitle = 'Список исполнителей онлайн аудиокниг';
        $documentDescription .= '- список исполнителей аудиокниг';
        $documentKeywords .= ', список исполнителей, исполнители, озвучил, озвучка';
    }
    else{
        $category = 'AND bv.book_id = b.book_id AND bv.voice_id = v.voice_id AND v.voice_url="' . $_REQUEST["voice"] . '"';
        $dopTable = ', voices v, books_voices bv';
        showCategory();
    }
}

else if(isset($_REQUEST['publisher'])) {
    if ($_REQUEST['publisher']==''){
        $eof = true;
        $query=$link->query('SELECT * FROM publishers ORDER BY publisher_name');
        while ($row=mysqli_fetch_assoc($query)){
            $content .= '<a href="/publisher/'.$row['publisher_url'].'" onclick="showContent(\'publisher\',\''.$row['publisher_url'].'\',\'\');return false">'.$row['publisher_name'].'</a>
            <br>';
        }
        $documentTitle = 'Список издательств онлайн аудиокниг';
        $documentDescription .= '- список издательств';
        $documentKeywords .= ', список издательств, издательства';
    }
    else {
        $category = ' AND p.publisher_url="' . $_REQUEST["publisher"] . '"';
        showCategory();
    }
}

else if(!empty($_REQUEST['year'])) {
    $category = ' AND book_year="' . $_REQUEST['year'] . '"';
    showCategory();
}

else if(isset($_REQUEST['search'])) {
    $request=strip_tags(stripslashes(trim(htmlspecialchars($_REQUEST['search']))));
    if(strlen($request)>4 && strlen($request)<75){
        $category = ' AND b.book_name LIKE "%'.$request.'%"';
        $search=true;
        if (showCategory()==false){
            $category = ' AND ba.book_id = b.book_id AND ba.author_id = a.author_id AND a.author_name LIKE "%'.$request.'%"';
            $dopTable = ', authors a, books_authors ba';
            if(showCategory()==false && $page === 1){
                $content .= '<b>К сожалению ничего не найдено, поиск производится по названию аудиокниги ( без имени автора ), либо по автору, если при поиске по названию ничего не найдено,
                попробуйте сократить запрос и проветь на наличие ошибок</b>';
            }
        }
    }
    else{
        $content .= '<b>Слишком короткий или слишком длинный запрос</b>';
    }
}

else if (isset($_REQUEST['saves'])){
    if (isset($_SESSION['user'])){
        $dopTable = ',users u,users_saves us';
        $category = 'AND u.user_id = "'.$_SESSION['user'].'" AND u.user_id = us.id_user AND us.id_book = b.book_id';
        showCategory();
    }
    else $content .= 'Вы не авторизированны';
}

//ВЫВОД КНИГИ В ПОЛНОЙ ВЕРСИИ

else if(!empty($_REQUEST['book']) && empty($_POST['onPageLoad'])) {
    $eof = true;
    if (!checkParam($_REQUEST['book'])){
        $content .= 'url содержит недомустимые параметры, возможно адресс данной страницы был изменен';
        $found = false;
    } else{
        $query = $pdo->prepare('SELECT * FROM books b, publishers p WHERE b.publisher_id = p.publisher_id AND b.book_url = ?');
        $query->execute(array($_REQUEST['book']));
        $row = $query->fetchAll();
        if (count($row) !== 1 ) {
            $content .= 'Такой книги не существует';
            $found = false;
        } else {
            $row = $row[0];
            $book = new book();
            $book->id = $row['book_id'];
            $book->cycle = $row['book_cycle'];
            $book->schema = true;
            $authors = $book->getAuthors();
            $rating = $book->calcRating();
            $documentTitle = 'Аудиокнига ' . $row['book_name'] . ' - ' . $authors['authors'] . ' слушать онлайн';
            $documentDescription = 'Аудиокнига ' . $row['book_name'] . ' автора ' . $authors['authors'] . ' слушать онлайн бесплатно, без регистрации';
            $documentKeywords .= ', ' . $authors['authors'] . ' , ' . $row['book_name'];

            $content .= '<div itemscope itemtype="http://schema.org/Book" class="post_box">
                <div class="post_title">
                    <h2><a itemprop="url" href="/book/' . $row['book_url'] . '"></a>Аудиокнига <span itemprop="name">'.$authors['authors'].' - '.$row['book_name'].'</span></h2>
                </div>
                <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="rating">
                    <div class="vote">';
                    for ($i = 1; $i < 11; $i++){
                        $content .= '<span onclick="vote('.$row['book_id'].',\''. $i .'\')">'. $i .'</span><br>';
                    }
                    $content .='</div>';
                if ($row['book_votes']==0) $rating = 5; else $rating = round($row['book_rating']/$row['book_votes'],1);
                $content .='<b><span itemprop="ratingValue">' . $rating . '</span><span itemprop="bestRating">/10</span></b><br>
                    <small>(голосов: <span itemprop="ratingCount">'.$row['book_votes'].'</span>)</small>
                </div>';
                $content .= '<div class="bookPlay">
                <div class="bookControls" onclick="playBook(\'' . $row['book_id'] . '\',\'' . $row['book_url'] . '\',\'' .$authors['authors']. ' - ' . $row['book_name'] . '\')">
                    <div class="bookPlayIcon"><img src="/images/bookPlayIcon.png" alt=""/></div>
                    <img itemprop="image" src="/books/images/' . $row['book_url'] . '.' . $row['book_img'] . '" alt="Аудиокнига '.$authors['authors'].' - '.$row['book_name'].'" title="Аудиокнига '.$authors['authors'].' - '.$row['book_name'].'"/>
                    <a>Слушать онлайн</a>
                    </div>';
                    if (file_exists('books/torrent/'.$row['book_url'].'.torrent')){
                        $content .='<a href = "/books/torrent/'.$row['book_url'].'.torrent " download="'.$row['book_url'].'.torrent" rel="nofollow">Скачать .torrent</a>';
                    }
                $content .= '</div>
                <div class="info">
                    <b>Жанр: </b>'.$book->getCategory().'<br>
                    <b>Автор: </b>' . $authors['linkAuthors'] . '<br>
                    <b>Озвучил: </b>'. $book->getVoices(). '<br>
                    <b>Издательство: </b><a href="/publisher/' . $row['publisher_url'] . '" onclick="showContent(\'publisher\',\'' . $row['publisher_url'] . '\',\'\');return false">
                    <span itemprop="publisher" itemscope itemtype="http://schema.org/Organization"><span itemprop="name">' . $row['publisher_name'] . '</span></span></a><br>' .
                    $book->getCycle() .
                    '<b>Год: </b><a href="/year/' . $row['book_year'] . '" onclick="showContent(\'year\',\'' . $row['book_year'] . '\',\'\');return false">
                    <span itemprop="datePublished">' . $row['book_year'] . '</span></a><br>
                    <b>Длительность: ' . $row['voice_time'] . '</b><br>
                    <p ><b>Описание: </b><span itemprop="description">' . $row['book_description'] . '</span></p>
                    <hr><p>Аудиокнигу '.$authors['authors'].' - '.$row['book_name'].' вы можете прослушать онлайн бесплатно.</p>
                </div>';
        }
    }
}

else if (!isset($_REQUEST['category']) AND !isset($_REQUEST['book']) AND !isset($_REQUEST['author']) AND !isset($_REQUEST['publisher']) AND !isset($_REQUEST['voice']) AND !isset($_REQUEST['saves']) AND !isset($_REQUEST['year']) AND !isset($_REQUEST['search'])){
    showCategory();
}
else header( 'Location: /category/404' );
if(!$_POST['onPageLoad']) $content .= '<div class="cleaner" id="cleaner"></div>';
echo json_encode(array('content'=>$content,'title'=>$documentTitle,'description'=>$documentDescription,'keywords'=>$documentKeywords,'found'=>$found, 'eof'=>$eof));
?>
