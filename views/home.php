<?php
require_once(__DIR__ . "/../status.api.php");

if (isset($_GET["404"])) {
	header("HTTP/1.0 404 Not Found");
	$fourOh = true;
}
if (isset($_GET["trends"])) {
	header("HTTP/1.0 404 Not Found");
	$trending = true;
}
if(!isset($_GET["viewPage"]) && !isset($fourOh) && !isset($trending)) {
	// redirect("/1");
	header("Location: /1");
	die();
}

$page = isset($_GET["viewPage"]) ? $_GET["viewPage"] : 1;
if($page < 1) {
	header("Location: /");
	die();
}

head();?>
<div class="container">
	<br>
	<div class="jumbotron" style="margin-bottom:10px">
		<h2>Server Tracking</h2>
		<p>What server is trending? <small class="text-muted">Want a server listed, <a target="_blank" href="//twitter.com/njb_said">Tweet me</a></small></p>
	</div>
	<?php
	if(isset($trending)) { ?>
		<div id="chart-1">
			<img class="img-center" src="//static.blogr.co/img/spinning-ajax.gif"></img>
			<p class="text-muted text-center">Calculating and crunching data.. <br> This can take a couple of minutes.</p>
		</div>
		<hr>
	<?php } else if(isset($fourOh)) { ?>
		<h2>Page not found <small><?php echo $_SERVER["REQUEST_URI"]; ?></small></h2>
		<hr>
		<p>The page you requested <strong>could not be found</strong> or you <strong>don't have permission</strong> to view it!</p>
		<hr>
	<?php } else { ?>
    <p class="text-muted">Server Banners are cached therefore they may be slightly outdated.</p>
	<div id="content" style="height: 525px;">
		<img class="img-center" src="//static.blogr.co/img/spinning-ajax.gif">
	</div>
	<?php } ?>
</div>
<?php foot();
	if(!isset($trending) && !isset($fourOh)) { ?>
<script type="text/javascript"> var page = <?php echo $page; ?>;</script>
<script type="text/javascript" src="/app.js"></script>
<?php } else if(isset($trending)) { ?>
    <script type="text/javascript" src="//cdn.imnjb.me/libs/highcharts/4.0.3/highcharts.js"></script>
    <script type="text/javascript" src="//cdn.imnjb.me/libs/chartkick.js/js/chartkick.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            new Chartkick.LineChart("chart-1", "/trending/rawdata", {"adapter": "highcharts"});
        });
    </script>
	<?php
} ?>
</body>
</html>
