<!DOCTYPE html>
<html lang = "ru">
<head>
    <meta charset = "utf-8">
    <title><?= $content['title']?></title>

    <meta name = "description" content = "<?= $content['description'] ?>">
    <meta name = "keywords" content = "<?=$content['keywords']?>">
    <meta name=viewport content = "width=device-width, initial-scale=1">

    <link rel = "stylesheet" href = "/style.css?215">
    <link rel = "apple-touch-icon-precomposed" href = "/images/apple-touch-icon.png">
</head>
<body>
<div class = "Container">
    <div id = "top_bar">
        <div id = "header">
            <img id = "siteLogo" title = "Аудиокниги онлайн" alt = "" src = "/images/siteLogo.png" onclick = "showContent('','','');return false">
            <a href = "/" onclick="showContent('','','');return false">
                <h1><b>Аудиокниги онлайн</b></h1>
                <span>На нашем сайте онлайн аудиокниг возможно <br>
                    автоматическое сохранение времени воспроизведения</span>
            </a>
        </div>
        <!--noindex-->
        <?php require_once __DIR__. '/../../login.php'; ?>
        <!--/noindex-->
        <div id = "menu">
            <div>
                <input type = "text" id = "searchInput" value = "Поиск по аудиокнигам" onfocus="clearText(this)" onblur="clearText(this)">
                <div id="search" class="icon-search" onclick="search();return false"></div>
            </div>
            <ul>
                <li><a href = "/" onclick="showContent('','','');return false">Главная</a></li>
                <li><a href = "/author/" onclick="showContent('author','','');return false">Авторы</a></li>
                <li><a href = "/voice/" onclick="showContent('voice','','');return false">Исполнители</a></li>
                <li><a href = "/publisher/" onclick="showContent('publisher','','');return false">Издательства</a></li>
            </ul>
        </div>
    </div>
    <div class = "cleaner"></div>

    <div id = "content">
        <div class = "categories">
            <div class = "box">
                <h3>Жанры</h3>
                <ul class = "side_menu">
                    <?php foreach ($categories as $cat): ?>
                        <li><a onclick="showContent('category','<?php echo $cat->category_url ?>','');return false" href = "/category/<?php echo $cat->category_url ?>"><?php echo $cat->category_name ?></a></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>

        <div class = "top20">
            <div class = "box">
                <h3>Топ 20 аудиокниг</h3>
                <!--noindex-->
                <ul class = "side_menu">
                    <?php $i = 1; foreach ($top20 as $top): ?>
                        <li><?php echo $i ?>. <a rel = "nofollow" href = "/book/<?php echo $top->book_url ?>" onclick="showContent('book','<?php echo $top->book_url ?>','');return false"><?php echo $top->book_name ?> -
                            <?php
                            $j = 0;
                            foreach ($top->authors as $author):
                                echo $author->author_name;
                                $j++;
                                if ($j !== count($author)){
                                    echo ', ';
                                }
                            endforeach ?>
                            </a> (<?php echo $top->book_votes ?>)</li>
                    <?php $i++; endforeach ?>
                </ul>
                <!--/noindex-->
            </div>
        </div>

        <div id = "content_column">
            <?php echo $content['content']; ?>
        </div>
        <div class = "cleaner"></div>
    </div>

    <?php require_once __DIR__. '/../../player.php'; ?>
    <div id = "footer">
        <p>Рады приветствовать вас на нашем сайте онлайн аудиокниг.
        У нас реализована функция автоматического сохранения времени воспроизведения аудиокниг.
        Также на сайте реализован рейтинг аудиокниг "TOP 20", основанный на оценках пользователей.
        Удобная навигация по аудиокнигам. В ближайшее время будет реализованы комментарии.
        Аудиокниги представлены на сайте в ознакомительных целях.
        По всем вопросам: admin@audioknigionline.ru</p>
    </div>
    <div id = "podPlayer"></div>
</div>

<script src = "/js/jquery.js"></script>
<script src = "/js/history.js"></script>
<script src = "/js/script.js?130"></script>
<script src = "/js/player.js?125"></script>
<?php
echo '<script>';
if ($content['eof']) echo 'eof = true;'; else echo 'eof = false;';
if ($src!='') echo ' idBook = '.$bookId.'; bookURL = "' . $src . '"; $time = '.$time.';';
echo '</script>';
?>
<script> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter29349035 = new Ya.Metrika({ id:29349035, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/29349035" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
</body>
</html>
