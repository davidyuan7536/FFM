<?php

function smarty_modifier_links($string, $wrap) {
    $replace = '<a href="http$3://$4$5" target="_blank">http$3://$4$5</a>';
    
    if ($wrap) {
        $replace = str_replace('$?', $replace, $wrap);
    }

    $result = preg_replace('/(?<!S)((http(s?):\/\/)|(www.))+([\w.1-9\&=#?!\-\+~%;\/]+)/', $replace, $string);

    return $result;
}

?>