<?php
$image = "/images/bookIcon.png";
$info = "<b>Выберите интересующею вас аудиокнигу и она откроется в этом плеере</b>";
$infoAttr = "";
$book = "";
$src = '';
if (isset($_SESSION["user"])) {
    $query=$link->query('SELECT b.book_id, u.user_lastbook, b.book_name, b.book_url,us.time FROM users u, books b, users_saves us
                        WHERE u.user_lastbook = b.book_id AND us.id_user=u.user_id AND us.id_book=b.book_id AND user_id='.$_SESSION["user"]);
    if($query->num_rows == 1) {
        $row = mysqli_fetch_assoc($query);
        $time = $row["time"];
    }
}
else if($_COOKIE['idBook']){
    $query=$link->query('SELECT book_id,book_name,book_url FROM books WHERE book_id = "'.$_COOKIE['idBook'].'"');
    if ($query->num_rows ==1){
        $row=mysqli_fetch_assoc($query);
        $time = $_COOKIE['time'];
    }
}
else if (isset($_REQUEST['book'])){
    $query = $link->query('SELECT book_id, book_name, book_url FROM books WHERE book_url = "'.$_REQUEST['book'].'"');
    if ($query->num_rows == 1){
        $row = mysqli_fetch_assoc($query);
        $time = 0;
    }
}
if (!empty($row)){
    $queryAuthor = $link->query('SElECT a.author_name,a.author_url FROM books b, authors a, books_authors ba WHERE ba.book_id = b.book_id AND ba.author_id = a.author_id AND b.book_id =' . $row["book_id"]);
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
