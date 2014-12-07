<?php
require_once(__DIR__ . "/../../status.api.php");

$id = $_GET["server"];
$cache_directory = __DIR__ . "/motd_cache";
$cache_expiry = 60 * 60 * 1;
$cache_file = $cache_directory . '/' . $id . '.png';
if(file_exists($cache_file) && filemtime($cache_file) > time() - $cache_expiry) {
    header("Cache-Control: public, max-age=1800");
    header("Content-type: image/png");
    echo file_get_contents($cache_file);
    die();
}

$collection = $mongoDB->selectCollection($colPrefix."servers");
$query = $collection->find(array('_id' => new MongoId($id)));

if($query->count() <= 0) {
    header("Cache-Control: public, max-age=1800");
    header("Content-type: image/png");
    echo "\x47\x49\x46\x38\x37\x61\x1\x0\x1\x0\x80\x0\x0\xfc\x6a\x6c\x0\x0\x0\x2c\x0\x0\x0\x0\x1\x0\x1\x0\x0\x2\x2\x44\x1\x0\x3b";
    die();
}

foreach($query as $row) {
    $ip = $row["connectionIP"];
    $port = 25565;
    $name = $row["name"];
}

header("Cache-Control: public, max-age=1800");
header("Content-type: image/png");
$name = urlencode($name);
$bannerPNG = file_get_contents("http://status.mclive.eu/$name/$ip/$port/banner.png");
file_put_contents($cache_file, $bannerPNG);
echo file_get_contents($cache_file);