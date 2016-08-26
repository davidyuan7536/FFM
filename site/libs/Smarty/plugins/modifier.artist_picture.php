<?php

function smarty_modifier_artist_picture($artist, $format = 'm') {
    return empty($artist['image']) ? '/i/decor/placeholder-artist_' . $format . '.png' : __FFM_PROFILE_FRONT__ . $artist['filename'] . '/a/' . $format . '.jpg?v=' . $artist['image'];
}

?>
