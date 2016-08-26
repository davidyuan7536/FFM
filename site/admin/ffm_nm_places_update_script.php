<?php
require_once "base.php";
require_once "Db/DbGeotags.php";
require_once "Db/DbLabelReleases.php";

$dbGeoTags = new DbGeotags();
$dbLabelReleases = new DbLabelReleases();

$geotags = DbGeotags::getGeoTags();
foreach ($geotags as $geotag) {
    $place = array(
        \db\DbPlace::NAME => $geotag['name'],
        \db\DbPlace::ABOUT => $geotag['wiki'],
        \db\DbPlace::LAT => $geotag['lat'],
        \db\DbPlace::LNG => $geotag['lng'],
        \db\DbPlace::USER_ID => 5
    );

    $NMPlace = \db\DbPlace::getByLatAndLng($geotag['lat'], $geotag['lng']);
    if (count($NMPlace) > 0) {
        $placeDTO = \db\DbPlace::updateById($NMPlace['place_id'], $place);
    } else {
        $placeDTO = \db\DbPlace::create($place);
    }

    $releases = DbLabelReleases::getReleasesByGeotag($geotag['geo_tag_id']);
    foreach ($releases as $release) {
        $release['nm_place_id'] = $placeDTO['place_id'];

        DbLabelReleases::updateReleaseById($release['release_id'], $release);
    }
}
