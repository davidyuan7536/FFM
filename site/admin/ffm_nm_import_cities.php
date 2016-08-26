<?php
require_once "base.php";
require_once "Db/DbGeotags.php";
require_once "Db/DbLabelReleases.php";

$dbGeoTags = new DbGeotags();
$dbLabelReleases = new DbLabelReleases();

$geotags = DbGeotags::getGeoTags();
print_r($geotags);
foreach ($geotags as $geotag) {
    $place = array(
        \db\DbPlace::NAME => $geotag['name'],
        \db\DbPlace::ABOUT => $geotag['wiki'],
        \db\DbPlace::LAT => $geotag['lat'],
        \db\DbPlace::LNG => $geotag['lng'],
        \db\DbPlace::USER_ID => 5,
        'place_ffm_id' => $geotag['geo_tag_id'],
        'place_ffm_filename' => $geotag['filename']
    );

    print_r($place);

    $NMPlace = \db\DbPlace::getByLatAndLng($geotag['lat'], $geotag['lng']);
    if (count($NMPlace) > 0) {
        $placeDTO = \db\DbPlace::updateById($NMPlace['place_id'], $place);
    } else {
        $placeDTO = \db\DbPlace::create($place);
    }

    $geotag['nm_place_id'] = $placeDTO['place_id'];
    DbGeotags::updateGeoTag($geotag['geo_tag_id'], $geotag);
}
