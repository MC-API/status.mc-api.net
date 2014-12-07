<?php
require_once(__DIR__ . "/../status.api.php");
head();?>
<div class="container">
	<br>
	<div class="jumbotron" style="margin-bottom:10px">
		<h2>Server Tracking &sdot; About <span class="pull-right"><a class="btn btn-sm btn-info" href="/">&larr; Back</a></span></h2>
		<p>Interested in how this works?</p>
	</div>
	<p>There are a few tools behind the Server Tracker:</p>
	<ul>
		<li>CloudFlare (<a href="//cloudflare.com">cloudflare.com</a>) is a powerful service providing many features, including:
			<ul>
				<li>Global CDN</li>
				<li>Poweful Analytics</li>
				<li>Caching and other accelerations to improve performance</li>
				<li>Security Optimizations</li>
				<li>And <em>much much</em> more..</li>
			</ul>
		</li>

		<li>MC-API (<a href="//mc-api.net">mc-api.net</a>) is the backbone of the Server Tracker. It is a collection of minecraft related api's, including Server Queries. This allows quick and easy server pings, which are then stored in the database</li>
		<li>MongoDB (<a href="//mongodb.org">mongodb.org</a>) is a NoSQL database which stores all server info and data</li>
		<li>HighCharts (<a href="//highcharts.com">highcharts.com</a>) is used to show you history of servers in a pretty graph</li>
		<li>Bootstrap (<a href="//bootswatch.com">bootswatch.com</a> - <a href="//getbootstrap.com">getbootstrap.com</a>) is a nice CSS framework which is used</li>
		<li>MCLive Status Image (<a href="//status.mclive.eu">status.mclive.eu</a>) is used to obtain motd images, however images are cached for 12 hours</li>
	</ul>
	<p>All servers are pinged once per minute, server banner images are cached therefore they are outdated to the player count which is obtained every minute.</p>
</div>
<?php foot(); ?>
</body>
</html>