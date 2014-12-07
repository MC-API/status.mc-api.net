<?php
try {
    $mongo = new MongoClient("mongodb://localhost/tracker", array("username" => "tracker", "password" => "password123"));
    $mongoDB = $mongo->selectDB("tracker");
}catch(MongoException $ex) {
    die("Please check your configuration of mongo.");
}


$colPrefix = "tracker_";

function head(){?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Server Tracker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//cdnjs.cloudflare.com/ajax/libs/bootswatch/3.2.0+2/sandstone/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css">
    <link href="/app.css" rel="stylesheet">
</head>
<body>
<?php }

function foot() { ?>
<footer style="padding-top: 18px;text-align:center;">
    <div class="container text-muted"><p style="font-size:130%">&copy; <a target="_blank" href="//imnjb.me">njb_said</a> 2014 &middot; <b>Version:</b> alpha-1.3</p><p>Find out <a href="/about">how this works</a>..</div>
</footer>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<?php }