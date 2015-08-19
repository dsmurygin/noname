<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__.'/function.php';

$image = "/images/bookIcon.png";
$info = "<b>Выберите интересующею вас аудиокнигу и она откроется в этом плеере</b>";
$infoAttr = "";
$book = "";
$src = '';
$db = new db;
if (isset($_SESSION["user"])) {
    $query = $db>query('SELECT b.book_id, u.user_lastbook, b.book_name, b.book_url,us.time FROM users u, books b, users_saves us
                        WHERE u.user_lastbook = b.book_id AND us.id_user=u.user_id AND us.id_book=b.book_id AND user_id = :id',[':id' =>$_SESSION["user"]]);
    if(count($query) == 1) {
        $time = $query->time;
    }
}
else if(isset($_COOKIE['idBook'])){
    $query = $db->query('SELECT book_id,book_name,book_url FROM books WHERE book_id = :id',[':id' => $_COOKIE['idBook']]);
    if (count($query) ==1){
        $time = $_COOKIE['time'];
    }
}
else if (isset($_REQUEST['book'])){
    $query = $db->query('SELECT book_id, book_name, book_url FROM books WHERE book_url = :id' , [':id' => $_REQUEST['book']]);
    var_dump($query);
    if (count($query) == 1){
        $time = 0;
    }
}
var_dump($query);
/*
if (!empty($row)){
    $Authors = new authors();

    if ($queryAuthor->num_rows == 1) {
        $authors = '';
        $rowAuthor = mysqli_fetch_assoc($queryAuthor);
        $authors = $rowAuthor["author_name"];
    } else {
        $authors = '';
        for ($i = 1; $i <= $queryAuthor->num_rows; $i++) {
            $rowAuthor = mysqli_fetch_assoc($queryAuthor);
            $authors .= $rowAuthor["author_name"];
            if ($i != $queryAuthor->num_rows) {$authors.=', ';$linkAuthors.=', ';};
        }
    }
    $infoAttr = 'href="/book/' . $row['book_url'] . '" onclick="showContent(\'book\',\'' . $row['book_url'] . '\',\'\');return false"';
    $info = $authors.' - ' . $row["book_name"];
    $src = "/books/audio/" . $row["book_url"] . ".mp3";
    if(isset($_COOKIE['idBook']) and (isset($_COOKIE['bookURL']))){
        $src = "/books/audio/" . $_COOKIE["bookURL"] . ".mp3";
    }
    $bookId = $row["book_id"];
    $image = '/books/images/'.$row["book_url"].".jpg";
}

?>
<audio id="audioPlayer" src="<?php echo $src;?>" preload="none"></audio>
<div id="player">
    <img id="bookIcon" src="<?php echo $image ?>" alt="Аудиокнига <?php echo $info ?>"/>
    <div id = "playPause"><span class="icon-play"></span></div>
    <?php
        echo '<a id="playerInfo" '.$infoAttr.'>'.$info.'</a>';
    ?>
    <img id="saveTime" class="infoIcon" src="/images/savetime.png" alt="" title="Добавить в прослушивание и автоматически сохранять время воспроизведения">
    <img id="addBookmark" class="infoIcon" src="/images/addBokmark.png" alt="" title="Добавить в закладки">
    <a id="playerDownload" rel="nofollow" href="<?php echo $src;?>" download="<?php echo $src;?>"><img class="infoIcon" src="/images/download.png" alt="" title="Скачать"></a>
    <br>
    <div id="controls">
        <div id="timeBar">
            <div id="progressBar"></div>
        </div>
        <span id="currentTime" class="time"></span><span id="Time" class="time"></span>
    </div>
</div>
*/