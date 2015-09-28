<?php
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
    if (count($query) == 1){
        $time = 0;
    }
}

if (isset($query)){
    $query = $query[0];
    $authors = new authors();
    $authors = $authors->getlistAuthors($query->book_id);

    $infoAttr = 'href="/book/' . $query->book_url . '" onclick="showContent(\'book\',\'' . $query->book_url . '\',\'\');return false"';
    $info = $authors.' - ' . $query->book_name;
    $src = "/books/audio/" . $query->book_url . ".mp3";
    if(isset($_COOKIE['idBook']) and (isset($_COOKIE['bookURL']))){
        $src = "/books/audio/" . $_COOKIE["bookURL"] . ".mp3";
    }
    $bookId = $query->book_id;
    $image = '/books/images/'.$query->book_url . '.jpg';
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
