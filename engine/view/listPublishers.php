<?php

foreach ($data->content as $publisher):?>
    <a href="/publisher/<?= $publisher->publisher_url ?>" onclick="showContent('publisher','<?= $publisher->publisher_url ?>','');return false"><?= $publisher->publisher_name ?></a><br>
<?php endforeach ?>
<div class="cleaner"></div>
