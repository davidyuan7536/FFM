<?php

function smarty_modifier_promoter_picture($promoter, $format = 'm') {
    return empty($promoter['promoter_image']) ? '/i/decor/placeholder-promoter_' . $format . '.png' : __FFM_PROMOTER_FRONT__ . $promoter['promoter_filename'] . '/a/' . $format . '.jpg?v=' . $promoter['promoter_image'];
}

?>
