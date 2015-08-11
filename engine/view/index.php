<!DOCTYPE html>
<html lang="ru">
<head>
    <?php
    echo '<meta charset="utf-8">';
    echo'
    <title>'.$content->title.'</title>
    <meta name = "description" content="'.$content->description.'">
    <meta name = "keywords" content="'.$content->keywords.'">';
    ?>
    <meta name=viewport content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/style.css?215">
    <link rel="apple-touch-icon-precomposed" href="/images/apple-touch-icon.png">
</head>
<body>
<div class="Container">
    <div id="top_bar">
        <div id="header">
            <img id="siteLogo" title="Аудиокниги онлайн" alt="" src="/images/siteLogo.png" onclick="showContent('','','');return false">
            <a href="/" onclick="showContent('','','');return false"><h1><b>Аудиокниги онлайн</b></h1><span>На нашем сайте онлайн аудиокниг возможно <br>автоматическое сохранение времени воспроизведения</span></a>
        </div>
        <!--noindex-->
        <?php
        include 'login.php';
        ?>
        <!--/noindex-->
        <div id="menu">
            <div><input type="text" id="searchInput" value="Поиск по аудиокнигам" onfocus="clearText(this)" onblur="clearText(this)"><div id="search" class="icon-search" onclick="search();return false"></div></div>
            <ul>
                <li><a href="/" onclick="showContent('','','');return false">Главная</a></li>
                <li><a href="/author/" onclick="showContent('author','','');return false">Авторы</a></li>
                <li><a href="/voice/" onclick="showContent('voice','','');return false">Исполнители</a></li>
                <li><a href="/publisher/" onclick="showContent('publisher','','');return false">Издательства</a></li>
            </ul>
        </div>
    </div>

    <div class="cleaner"></div>
    <div id="content">
        <div class="categories">
            <div class="box">
                <h3>Жанры</h3>
                <ul class="side_menu">
                    <?php foreach ($categories as $cat): ?>
                        <li><a onclick="showContent("category","<?php echo $cat->category_url ?>","");return false" href="/category/<?php echo $cat->category_url ?>"><?php echo $cat->category_name ?></a></li>
                    <?php endforeach?>
                </ul>
            </div>
        </div>
        <div class="top20">
            <div class="box">
                <h3>Топ 20 аудиокниг</h3>
                <!--noindex-->
                <ul class="side_menu">
                    <?php
                    $query=$pdo->query('SELECT book_name, book_url, book_votes, book_id FROM books ORDER by book_votes DESC LIMIT 0,20');
                    $i=1;
                    while ($row=$query->fetch()){
                        $book = new book();
                        $book->id = $row['book_id'];
                        echo '<li>'.$i.'. <a rel="nofollow" href="/book/'.$row['book_url'].'" onclick="showContent(\'book\',\''.$row['book_url'].'\',\'\');return false">'.$row['book_name'].' - '.$book->getAuthors()['authors'].'</a> ('.$row['book_votes'].')</li>';
                        $i++;
                    }
                    ?>
                </ul>
                <!--/noindex-->
            </div>
        </div>
        <div id="content_column">
            <?php echo $content->content; ?>
        </div>
        <div class="cleaner"></div>
    </div>

    <?php
    include 'player.php';
    ?>
    <div id="footer"><p>Рады приветствовать вас на нашем сайте онлайн аудиокниг.
            У нас реализована функция автоматического сохранения времени воспроизведения аудиокниг.
            Также на сайте реализован рейтинг аудиокниг "TOP 20", основанный на оценках пользователей.
            Удобная навигация по аудиокнигам. В ближайшее время будет реализованы комментарии.
            Аудиокниги представлены на сайте в ознакомительных целях.
            По всем вопросам: admin@audioknigionline.ru</p>
    </div>
    <div id="podPlayer"></div>
</div>
<script src="/js/jquery.js"></script>
<script src="/js/history.js"></script>
<script src="/js/script.js?130"></script>
<script src="/js/player.js?125"></script>
<?php
echo '<script>';
if ($content->eof) echo 'eof = true;'; else echo 'eof = false;';
if ($src!='') echo ' idBook = '.$bookId.'; bookURL = "'.$_COOKIE['bookURL'].'"; $time = '.$time.';';
echo '</script>';
?>
<script> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter29349035 = new Ya.Metrika({ id:29349035, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/29349035" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
</body>
</html>
