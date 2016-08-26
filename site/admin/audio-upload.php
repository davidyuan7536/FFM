<?php

ini_set("html_errors", "0");

function file_upload_error_message($error_code) {
    switch ($error_code) {
        case UPLOAD_ERR_INI_SIZE:
            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        case UPLOAD_ERR_FORM_SIZE:
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
        case UPLOAD_ERR_PARTIAL:
            return 'The uploaded file was only partially uploaded';
        case UPLOAD_ERR_NO_FILE:
            return 'No file was uploaded';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Missing a temporary folder';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Failed to write file to disk';
        case UPLOAD_ERR_EXTENSION:
            return 'File upload stopped by extension';
        default:
            return 'Unknown upload error';
    }
}

$FILE_DATA = 'Filedata';

if (!isset($_FILES[$FILE_DATA])) {
//    echo "ini_get('post_max_size'): ".ini_get('post_max_size').'<br/>';
//    echo "ini_get('upload_max_filesize'): ".ini_get('upload_max_filesize').'<br/>';
    echo getErrorJSON('Black Hole 1');
    exit(0);
}

if ($_FILES[$FILE_DATA]['error'] !== UPLOAD_ERR_OK) {
    $error_message = file_upload_error_message($_FILES[$FILE_DATA]['error']);
    echo getErrorJSON($error_message);
    exit(0);
}

if (!is_uploaded_file($_FILES[$FILE_DATA]['tmp_name'])) {
    print_r($_FILES);
    echo getErrorJSON('Black Hole 2');
    exit(0);
}

require_once "../global.php";
require_once('../getid3/getid3.php');

$getID3 = new getID3;
$getID3->setOption(array('encoding' => 'UTF-8'));

$fileName = $_FILES[$FILE_DATA]['name'];
$fileName1251 = getid3_lib::iconv_fallback("UTF-8", "WINDOWS-1251", $fileName);
$uploadFile = __FFM_AUDIO__ . $fileName1251;

if (file_exists($uploadFile)) {
    echo getErrorJSON("The file {$fileName} exists");
    exit(0);
}

if (!move_uploaded_file($_FILES[$FILE_DATA]['tmp_name'], $uploadFile)) {
    echo getErrorJSON("Invalid upload {$fileName}");
    exit(0);
}

require_once "Db/DbAudio.php";
$dbAudio = new DbAudio();

$data = array(
    'audio_filename' => $fileName
);

$audioId = $dbAudio->newAudio($data);

$info = $getID3->analyze($uploadFile);
getid3_lib::CopyTagsToComments($info);

if (count($info['comments']['artist']) > 0) {
    require_once "Db/DbArtists.php";
    $dbArtists = new DbArtists();
    $artists = $dbArtists->getArtistsBySearch($info['comments']['artist'][0]);
    $artist = count($artists) > 0 ? $artists[0] : '';
} else {
    $artist = '';
}

$result = array(
    'status' => 'OK',
    'id' => $audioId,
    'fileName' => $fileName,
    'tags' => $info['comments'],
    'artist' => $artist
);

echo json_encode($result);


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

