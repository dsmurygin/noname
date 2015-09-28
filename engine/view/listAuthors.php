<?php

foreach ($data->content as $author):?>
    <a href="/author/<?= $author->author_url ?>" onclick="showContent('author','<?= $author->author_url ?>','');return false"><?= $author->author_name ?></a><br>
<?php endforeach ?>
<div class="cleaner"></div>