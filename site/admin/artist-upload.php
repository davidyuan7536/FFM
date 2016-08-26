<?php

ini_set("html_errors", "0");

require_once "../global.php";

require_once "Site/Utils.php";

require_once "Db/DbArtists.php";
$dbArtists = new DbArtists();

$artist = $dbArtists->getArtistById($_GET['id']);

$r = Utils::createImage(array(
    array(
        'filename' => __FFM_PROFILE__ . $artist['filename'] . '/a/s.jpg',
        'width' => 50,
        'height' => 50
    ),
    array(
        'filename' => __FFM_PROFILE__ . $artist['filename'] . '/a/m.jpg',
        'width' => 130,
        'height' => 130
    ),
    array(
        'filename' => __FFM_PROFILE__ . $artist['filename'] . '/a/b.jpg',
        'width' => 180,
        'height' => 180
    ),
    array(
        'filename' => __FFM_PROFILE__ . $artist['filename'] . '/a/o.jpg',
        'width' => 0,
        'height' => 0
    )
));

if ($r) {
    $data = array(
        'image' => rand(1, 127)
    );
    $dbArtists->updateArtistById($artist['artist_id'], $data);

    echo "OK:" . __FFM_PROFILE_FRONT__ . $artist['filename'] . '/a/m.jpg?v=' . $data['image'];
} else {
    echo "ERROR:invalid upload";
}
