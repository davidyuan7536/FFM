<?php

function smarty_block_content($params, $content, $smarty, &$repeat, $template) {
    if (is_null($content)) {
        return;
    }

    include_once "formatting.php";
    echo wpautop($content);
}

?>
