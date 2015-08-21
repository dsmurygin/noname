<?php

foreach ($data->content as $voice):?>
    <a href="/voice/<?= $voice->voice_url ?>" onclick="showContent('voice','<?= $voice->voice_url ?>','');return false"><?= $voice->voice_name ?></a><br>
<?php endforeach ?>
<div class="cleaner"></div>