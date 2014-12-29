<?php
require_once(__DIR__ . "/../../status.api.php");

$threshold = strtotime("today midnight");
$all = $mongoDB->selectCollection($colPrefix . "servers")->find()->sort(array('players' => -1))->limit(10);

header("Content-type: application/json");
$data = array();

foreach ($all as $server) {
    $serverData["name"] = $server["name"];
    $pings = array();
    $time = microtime(true);
    $query = $mongoDB->selectCollection($colPrefix . "server_pings")->find(array("server" => $server["_id"], 'time' => array('$gte' => $threshold)));
    foreach ($query as $ping) {
        $pings[date("Y-m-j H:i:00", $ping["time"])] = $ping["players"];
    }
    $serverData["data"] = $pings;
    $serverData["meta"] = array("took" => round(microtime(true) - $time, 1));
    $data[] = $serverData;
}

die(json_encode($data));