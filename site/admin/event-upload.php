<?php

ini_set("html_errors", "0");

// Check the upload
if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
    echo "ERROR:invalid upload";
    exit(0);
}

$newThumb = CroppedThumbnail($_FILES["Filedata"]["tmp_name"], 210, 130);
$bigThumb = CroppedThumbnail($_FILES["Filedata"]["tmp_name"], 405, 300);

require_once "../global.php";

require_once "Db/DbEvents.php";
$dbEvents = new DbEvents();

$event = $dbEvents->getEventById($_GET['id']);

if (imagejpeg($newThumb, "../../thumbnails/events/{$event['event_id']}.jpg", 90) &&
        imagejpeg($bigThumb, "../../thumbnails/events_big/{$event['event_id']}.jpg", 90)) {
    $dbEvents->setEventImageById($event['event_id'], $event['event_id']);
}

echo "OK:/thumbnails/events/{$event['event_id']}.jpg";


function CroppedThumbnail($imgSrc, $thumbnail_width, $thumbnail_height) {
    //getting the image dimensions
    list($width_orig, $height_orig) = getimagesize($imgSrc);
    $myImage = imagecreatefromjpeg($imgSrc);
    if (!$myImage) {
        return false;
    }
    $ratio_orig = $width_orig / $height_orig;

    if ($thumbnail_width / $thumbnail_height > $ratio_orig) {
        $new_height = $thumbnail_width / $ratio_orig;
        $new_width = $thumbnail_width;
    } else {
        $new_width = $thumbnail_height * $ratio_orig;
        $new_height = $thumbnail_height;
    }

    $x_mid = $new_width / 2; //horizontal middle
    $y_mid = $new_height / 2; //vertical middle

    $process = imagecreatetruecolor(round($new_width), round($new_height));

    imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
    $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
    imagecopyresampled($thumb, $process, 0, 0, ($x_mid - ($thumbnail_width / 2)), ($y_mid - ($thumbnail_height / 2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

    imagedestroy($process);
    imagedestroy($myImage);
    return $thumb;
}

?>