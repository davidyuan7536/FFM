<?php

ini_set("html_errors", "0");

if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
    echo getErrorJSON('Invalid upload');
    exit(0);
}

require_once "../global.php";

$tempFile = $_FILES["Filedata"]["tmp_name"];
$info = pathinfo($_FILES['Filedata']['name']);
$filename = basename($_FILES['Filedata']['name'], '.' . $info['extension']);

$size = getimagesize($tempFile);
list($imageWidth, $imageHeight, $IMG_TYPE) = $size;
switch ($IMG_TYPE) {
    case IMG_GIF:
        $image = imagecreatefromgif($tempFile);
        break;
    case IMG_JPG:
        $image = imagecreatefromjpeg($tempFile);
        break;
    case IMG_PNG:
        $image = imagecreatefrompng($tempFile);
        break;
    default:
        echo getErrorJSON('Unexpected file format');
        exit(0);
}

require_once "Db/DbArticles.php";
$dbArticles = new DbArticles();
$article = $dbArticles->getArticleById($_GET['id']);

$time = strtotime($article['date']);
$year = date('Y', $time);
$month = date('m', $time);

require_once "Db/DbPictures.php";
$dbPictures = new DbPictures();
$picture = $dbPictures->getPictureByName($year, $month, $filename);
if (!empty($picture)) {
    echo getErrorJSON("The file {$filename} exists");
    exit(0);
}

$dir_year = __FFM_PICTURES__ . "{$year}";
$dir = $dir_year . "/{$month}";

if (!is_dir($dir_year)) {
    mkdir($dir_year);
}

if (!is_dir($dir)) {
    mkdir($dir);
}

$squareThumbnail = mega_crop($filename, $image, $size, 150, 150, $dir, 90);
$mediumImage = image_resize($filename, $image, $size, 300, 300, false, true, $dir, 95);
$originalImage = image_resize($filename, $image, $size, 400, 500, false, false, $dir, 95);

if (!$originalImage) {
    if ($IMG_TYPE == IMG_JPG) {
        move_uploaded_file($_FILES['Filedata']['tmp_name'], "{$dir}/{$filename}.jpg");
        $originalImage = array(
            'width' => $imageWidth,
            'height' => $imageHeight,
            'filename' => "{$filename}.jpg"
        );
    } else {
        $originalImage = save_image("{$dir}/{$filename}.jpg", $image, $imageWidth, $imageHeight, 95);
    }
}

imagedestroy($image);

$data = array(
    'picture_filename' => $filename,
    'picture_type' => 'image/jpeg',
    'picture_year' => $year,
    'picture_month' => $month,
    'o_filename' => $originalImage['filename'],
    'o_width' => $originalImage['width'],
    'o_height' => $originalImage['height'],
    'm_filename' => '',
    's_filename' => $squareThumbnail['filename'],
    'article_id' => $article['article_id']
);

if ($mediumImage) {
    $data['m_filename'] = $mediumImage['filename'];
    $data['m_width'] = $mediumImage['width'];
    $data['m_height'] = $mediumImage['height'];
}

$pictureId = $dbPictures->newPicture($data);

echo json_encode(array(
    'status' => 'OK'
));


/**
 * @param  $destfilename
 * @param  $image
 * @param  $dst_w
 * @param  $dst_h
 * @param  $jpeg_quality
 * @return
 */
function save_image($destfilename, &$image, $dst_w, $dst_h, $jpeg_quality) {

    imagejpeg($image, $destfilename, $jpeg_quality);

    // Set correct file permissions
    $stat = stat(dirname($destfilename));
    $perms = $stat['mode'] & 0000666; //same permissions as parent folder, strip off the executable bits
    @ chmod($destfilename, $perms);

    return array(
        'width' => $dst_w,
        'height' => $dst_h,
        'filename' => basename($destfilename)
    );
}

/**
 * @param  $name
 * @param  $image
 * @param  $size
 * @param  $dest_w
 * @param  $dest_h
 * @param  $dir
 * @param int $jpeg_quality
 * @return
 */
function mega_crop($name, &$image, $size, $dest_w, $dest_h, $dir, $jpeg_quality = 90) {
    list($orig_w, $orig_h, $orig_type) = $size;

    $ratio_orig = $orig_w / $orig_h;

    if ($dest_w / $dest_h > $ratio_orig) {
        $dst_h = $dest_w / $ratio_orig;
        $dst_w = $dest_w;
    } else {
        $dst_w = $dest_h * $ratio_orig;
        $dst_h = $dest_h;
    }

    $src_x = floor(($dst_w - $dest_w) / 2);
    $src_y = floor(($dst_h - $dest_h) / 2);

    $process = wp_imagecreatetruecolor($dst_w, $dst_h);
    imagecopyresampled($process, $image, 0, 0, 0, 0, $dst_w, $dst_h, $orig_w, $orig_h);
    $newimage = wp_imagecreatetruecolor($dest_w, $dest_h);
    imagecopyresampled($newimage, $process, 0, 0, $src_x, $src_y, $dest_w, $dest_h, $dest_w, $dest_h);

    imagedestroy($process);

    // convert from full colors to index colors, like original PNG.
    if (IMAGETYPE_PNG == $orig_type && !imageistruecolor($image))
        imagetruecolortopalette($newimage, false, imagecolorstotal($image));

    $destfilename = "{$dir}/{$name}-{$dest_w}x{$dest_h}.jpg";

    return save_image($destfilename, $newimage, $dest_w, $dest_h, $jpeg_quality);
}

/**
 * @param  $name
 * @param  $image
 * @param  $size
 * @param  $max_w
 * @param  $max_h
 * @param bool $crop
 * @param  $is_suffix
 * @param  $dir
 * @param int $jpeg_quality
 * @return bool
 */
function image_resize($name, &$image, $size, $max_w, $max_h, $crop = false, $is_suffix, $dir, $jpeg_quality = 90) {
    list($orig_w, $orig_h, $orig_type) = $size;

    $dims = image_resize_dimensions($orig_w, $orig_h, $max_w, $max_h, $crop);
    if (!$dims)
        return $dims;
    list($dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) = $dims;

    $newimage = wp_imagecreatetruecolor($dst_w, $dst_h);

    imagecopyresampled($newimage, $image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

    // convert from full colors to index colors, like original PNG.
    if (IMAGETYPE_PNG == $orig_type && !imageistruecolor($image))
        imagetruecolortopalette($newimage, false, imagecolorstotal($image));

    // $suffix will be appended to the destination filename, just before the extension
    if ($is_suffix)
        $suffix = "-{$dst_w}x{$dst_h}";

    $destfilename = "{$dir}/{$name}{$suffix}.jpg";

    return save_image($destfilename, $newimage, $dst_w, $dst_h, $jpeg_quality);
}

/**
 * @param  $orig_w
 * @param  $orig_h
 * @param  $dest_w
 * @param  $dest_h
 * @param bool $crop
 * @return bool
 */
function image_resize_dimensions($orig_w, $orig_h, $dest_w, $dest_h, $crop = false) {

    if ($orig_w <= 0 || $orig_h <= 0)
        return false;
    // at least one of dest_w or dest_h must be specific
    if ($dest_w <= 0 && $dest_h <= 0)
        return false;

    if ($crop) {
        // crop the largest possible portion of the original image that we can size to $dest_w x $dest_h
        $aspect_ratio = $orig_w / $orig_h;
        $new_w = min($dest_w, $orig_w);
        $new_h = min($dest_h, $orig_h);

        if (!$new_w) {
            $new_w = intval($new_h * $aspect_ratio);
        }

        if (!$new_h) {
            $new_h = intval($new_w / $aspect_ratio);
        }

        $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

        $crop_w = round($new_w / $size_ratio);
        $crop_h = round($new_h / $size_ratio);

        $s_x = floor(($orig_w - $crop_w) / 2);
        $s_y = floor(($orig_h - $crop_h) / 2);
    } else {
        // don't crop, just resize using $dest_w x $dest_h as a maximum bounding box
        $crop_w = $orig_w;
        $crop_h = $orig_h;

        $s_x = 0;
        $s_y = 0;

        list($new_w, $new_h) = wp_constrain_dimensions($orig_w, $orig_h, $dest_w, $dest_h);
    }

    // if the resulting image would be the same size or larger we don't want to resize it
    if ($new_w >= $orig_w && $new_h >= $orig_h)
        return false;

    // the return array matches the parameters to imagecopyresampled()
    // int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
    return array(0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h);
}

/**
 * @param  $current_width
 * @param  $current_height
 * @param int $max_width
 * @param int $max_height
 * @return
 */
function wp_constrain_dimensions($current_width, $current_height, $max_width = 0, $max_height = 0) {
    if (!$max_width and !$max_height)
        return array($current_width, $current_height);

    $width_ratio = $height_ratio = 1.0;

    if ($max_width > 0 && $current_width > 0 && $current_width > $max_width)
        $width_ratio = $max_width / $current_width;

    if ($max_height > 0 && $current_height > 0 && $current_height > $max_height)
        $height_ratio = $max_height / $current_height;

    // the smaller ratio is the one we need to fit it to the constraining box
    $ratio = min($width_ratio, $height_ratio);

    return array(intval($current_width * $ratio), intval($current_height * $ratio));
}

/**
 * @param  $width
 * @param  $height
 * @return resource
 */
function wp_imagecreatetruecolor($width, $height) {
    $img = imagecreatetruecolor($width, $height);
    if (is_resource($img) && function_exists('imagealphablending') && function_exists('imagesavealpha')) {
        imagealphablending($img, false);
        imagesavealpha($img, true);
    }
    return $img;
}


/**
 * @param  $message
 * @return string
 */
function getErrorJSON($message) {
    $result = array(
        'status' => 'error',
        'message' => $message
    );
    return json_encode($result);
}

?>