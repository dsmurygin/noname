<?php
$post = $data->content;
$categories = '';
$authors = '';
$voices = '';

$i = 1;
foreach ($post->categories as $category){
    $categories .= '<a href="/category/' . $category->category_url.'" onclick="showContent(\'category\',\''. $category->category_url .'\',\'\');return false">' . $category->category_name . '</a>';
    if ($i !==count($post->categories)){
        $categories .= ', ';
        $i++;
    }
}

$i = 1;
foreach ($post->authors as $author){
    $authors .= '<a href="/author/' . $author->author_url . '" onclick="showContent(\'author\',\'' . $author->author_url . '\',\'\');return false">' . $author->author_name . '</a>';
    if ($i !== count($post->authors)){
        $authors .= ', ';
        $i++;
    }
}

$i = 1;
foreach ($post->voices as $voice){
    $voices .= '<a href="/voice/' . $voice->voice_url . '" onclick="showContent(\'voice\',\'' . $voice->voice_url . '\',\'\');return false">' . $voice->voice_name . '</a>';
    if ($i !== count($post->voices)){
        $voices .= ', ';
        $i++;
    }
}

?>
<div class="post_title">
    <h2><?= $post->listAuthors ?> «<?= $post->book_name?>»</h2>
</div>
<div class="bookPlay">
    <div class="bookControls" onclick="playBook('<?= $post->book_id ?>','<?= $post->book_url ?>','<?= $post->listAuthors ?> - <?= $post->book_name?>')">
        <div class="bookPlayIcon">
            <img src="/images/bookPlayIcon.png" alt=""/>
        </div>
        <img src="/books/images/<?= $post->book_url ?>.jpg" alt="Аудиокнига <?= $post->listAuthors ?> - <?= $post->book_name?>"/>
        <a>Слушать онлайн</a>
    </div>
    <?php if (file_exists('books/torrent/'.$post->book_url.'.torrent')):?>
    <a href = "/books/torrent/<?= $post->book_url ?>.torrent " download="<?= $post->book_url ?>.torrent" rel="nofollow">Скачать .torrent</a>
    <?php endif ?>
</div>
<div class="info">
    <b>Жанр: </b><?= $categories ?><br>
    <b>Автор: </b><?= $authors ?><br>
    <b>Озвучил: </b><?= $voices ?><br>
    <b>Издательство: </b><a href="/publisher/<?= $post->publisher_url ?>" onclick="showContent('publisher','<?= $post->publisher_url ?>','');return false"><?= $post->publisher_name ?></a><br>
    <b>Год: </b><a href="/year/<?= $post->book_year ?>" onclick="showContent('year','<?= $post->book_year ?>','');return false"><?= $post->book_year ?></a><br>
    <b>Длительность: <?= $post->voice_time ?></b><br>
    <p ><b>Описание: </b><?= $post->book_description ?></p>
</div>