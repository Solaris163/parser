<?php

foreach ($content as $category => $matches):?>

    <div class="category-name"><?=$category?></div>

    <?foreach ($matches as $id => $match):?>

    <div class="match-name"><a href="/show/match/?id=<?=$id?>"><?=$match?></a></div>

    <?endforeach;?>

<?endforeach;?>

