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
		<div id="chart">
			<!-- Need to place a spinner here -->
			<p class="text-muted text-center">Not loading? Try <a href="/trending">refreshing</a></p>
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
		<img class="img-center" src="//static.pvp.kiwi/img/spinning-ajax.gif">
	</div>
	<?php } ?>
</div>
<?php foot();
	if(!isset($trending) && !isset($fourOh)) { ?>
<script type="text/javascript"> var page = <?php echo $page; ?>;</script>
<script type="text/javascript" src="/app.js"></script>
<?php } else if(isset($trending)) {
	include_once(__DIR__ . "/../inc/api/trends.php"); ?>
	<!--<script type="text/javascript" src="/lib/canvas.js"></script>-->
	<script type="text/javascript" src="//code.highcharts.com/highcharts.js"></script>
	<script type="text/javascript">
		/* var chart = new CanvasJS.Chart("chart", echo graphdata(60 * 60 * 12););
		chart.render();
		$(".canvasjs-chart-credit").remove(); */

        // -------------------------------
        //
        // This doesn't work properly yet
        //
        // -------------------------------
		var chart;
            $(document).ready(function() {
                var options = {
                    chart: {
                        renderTo: 'chart',
                        defaultSeriesType: 'line',
                        marginRight: 130,
                        marginBottom: 25
                    },
                    title: {
                        text: 'Hourly Visits',
                        x: -20 //center
                    },
                    subtitle: {
                        text: '',
                        x: -20
                    },
                    xAxis: {
                        type: 'datetime',
                        tickInterval: 3600 * 1000, // one hour
                        tickWidth: 0,
                        gridLineWidth: 1,
                        labels: {
                            align: 'center',
                            x: -3,
                            y: 20,
                            formatter: function() {
                                return Highcharts.dateFormat('%l%p', this.value);
                            }
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Visits'
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    tooltip: {
                        formatter: function() {
                                return Highcharts.dateFormat('%l%p', this.x-(1000*3600)) +'-'+ Highcharts.dateFormat('%l%p', this.x) +': <b>'+ this.y + '</b>';
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'top',
                        x: -10,
                        y: 100,
                        borderWidth: 0
                    },
                    series: [{
                        name: 'Count'
                    }]
                }
                // Load data asynchronously using jQuery. On success, add the data
                // to the options and initiate the chart.
                // This data is obtained by exporting a GA custom report to TSV.
                // http://api.jquery.com/jQuery.get/
                jQuery.get('http://status.mc-api.net/api/trends', null, function(tsv) {
                    var lines = [];
                    traffic = [];
                    try {
                        // split the data return into lines and parse them
                        tsv = tsv.split(/\n/g);
                        jQuery.each(tsv, function(i, line) {
                            line = line.split(/\t/);
                            date = Date.parse(line[0] +' UTC');
                            traffic.push([
                                date,
                                parseInt(line[1].replace(',', ''), 10)
                            ]);
                        });
                    } catch (e) { console.log(e); }
                    options.series[0].data = traffic;
                    chart = new Highcharts.Chart(options);
                });
            });
	</script>
	<?php
} ?>
</body>
</html>