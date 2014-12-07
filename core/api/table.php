<?php
require_once(__DIR__ . "/../../status.api.php");
$table = isset($_GET["table"]) ? $_GET["table"] : "";
$page = isset($_GET["page"]) ? $_GET["page"] : 1;
$perPage = 10;

if($table == 'tracking') {
$collection = $mongoDB->selectCollection($colPrefix . "servers");
$query = $collection->find()->skip(($page-1)*$perPage)->limit($perPage)->sort(array("players" => -1));
$pageCount = ceil($collection->count() / $perPage);
$invalidPage = $page > $pageCount || $page < 1;
?>
<table class="table table-bordered table-striped">
	<tr style="font-weight:bolder;">
		<td>Server</td>
		<td>IP Address</td>
		<td>Website</td>
		<td>MOTD</td>
		<td>Status</td>
	</tr>
	<?php
		foreach ($query as $row) {
			echo '<tr>';
			echo '<td>' . $row["name"] . '</td>';
			echo '<td>' . $row["connectionIP"] . '</td>';
			$site = $row["website"];
			$site = str_replace("https://", "", $site);
			$site = str_replace("http://", "", $site);
			echo '<td><a target="_blank" href="' . $row["website"] . '">' . $site . '</a> ' . (strpos($row["website"], "https") === false ? "" : "&nbsp;<i class='fa fa-lock'></i>") .'</td>';
			echo '<td><img src="/api/banner/cache?server=' . $row["_id"] . '"></td>';
			
			echo '<td>' . ($row["online"] ? "<i class='fa fa-check'></i> &middot; " . $row["players"] . "/" . $row["maxPlayers"] : "<i class='fa fa-times'></i> &middot; Ping failed or Offline") . '</td>';
			echo '</tr>';
		}
	?>
</table>
<?php
	if($invalidPage) {
		?>
		<h3 class="text-center">Page not found</h3>
		<?php
	} else if($pageCount > 1) {
		if($page + 1 > $pageCount) {
			echo '<button disabled class="pull-right btn btn-success btn-sm">Next Page</button>';
		} else {
			echo '<a class="pull-right btn btn-success btn-sm" href="/' .($page+1).'">Next Page</a>';
		}
		if($page - 1 > 0) {
			echo '<a class="btn btn-success btn-sm" href="/' .($page-1).'">Previous Page</a>';
		} else {
			echo '<button disabled class="btn btn-success btn-sm">Previous Page</button>';
		}
		echo '<p class="text-center text-muted">Showing page ' . $page . ' of ' . $pageCount . '</p>';
	}
	echo '<hr>';
} else {
	die("Unknown Table");
}
