<?php

if (php_sapi_name() != 'cli') {
	die();
}

require_once(__DIR__ . "/../../status.api.php");
$collection = $mongoDB->selectCollection($colPrefix . "servers");
$insertCollection = $mongoDB->selectCollection($colPrefix . "server_pings");
$pinger = new pinger();
foreach($collection->find() as $row) {
	$ip = $row["ip"];
	$ping = $pinger->ping($row["ip"]);

	if($ping) {
		$status = "online";
		$players = $ping["players"];
		$maxPlayers = $ping["maxPlayers"];
		$version = $ping["version"];
		$motd = $ping["motd"];
		$motdRaw = $ping["motd_raw"];
		$ping = $ping["ping"];
		$proto = $ping["protocol"];
	} else {
		$ping = file_get_contents("http://mc-api.net/server/players?server=" . $ip);
		$ping = json_decode($ping, true);
		$status = $ping["status"];
		$players = $ping["players"]["online"];
		$maxPlayers = $ping["players"]["max"];
		$proto = "unknown";
		$version = "unknown";
		$motd = "";
		$motdRaw = "";
		$ping = -1;
	}
	
	$insert = array('server' => $row["_id"], 'proto' => $proto, 'version' => $version, 'motd' => $motd, 'motd_raw' => $motdRaw, 'players' => $players, 'maxPlayers' => $maxPlayers, 'time' => time(), 'ping' => $ping);
	$insertCollection->insert($insert);
	$update = array('$set' => array('online' => ($status == 'offline' ? false : true), "players" => $players, "maxPlayers" => $maxPlayers, "motd" => $motd));
	$query = $collection->update(array("_id" => $row["_id"]), $update);
	echo 'Pinged ' . $row["name"] . ': ' . $players . '/' . $maxPlayers . "\n";
}

class pinger {
	public function ping($host = '127.0.0.1', $version = '1.7*', $port = 25565) {
		if(strpos($host, ':') !== false) {
		    $parts = explode( ':', $host );
		    $host = $parts[0];
		    $port = (int) $parts[1];
	    }

		if (substr_count($host , '.') != 4) $host = gethostbyname($host);

	    $serverdata = array();
	    $serverdata['hostname'] = $host;
	    $serverdata['version'] = false;
	    $serverdata['protocol'] = false;
	    $serverdata['players'] = false;
	    $serverdata['maxPlayers'] = false;
	    $serverdata['motd'] = false;
	    $serverdata['motd_raw'] = false;
	    $serverdata['favicon'] = false;
	    $serverdata['ping'] = false;

	    $socket = $this->connect($host, $port);

	    if(!$socket) {
	        return false;
	    }

	    if(preg_match('/1.7|1.8/',$version)) {

	        $start = microtime(true);

	        $handshake = pack('cccca*', hexdec(strlen($host)), 0, 0x04, strlen($host), $host).pack('nc', $port, 0x01);

	        socket_send($socket, $handshake, strlen($handshake), 0); //give the server a high five
	        socket_send($socket, "\x01\x00", 2, 0);
	        socket_read( $socket, 1 );

	        $ping = round((microtime(true)-$start)*1000); //calculate the high five duration

	        $packetlength = $this->read_packet_length($socket);

	        if($packetlength < 10) {
	            return false;
	        }

	        socket_read($socket, 1);

	        $packetlength = $this->read_packet_length($socket);

	        $data = socket_read($socket, $packetlength, PHP_NORMAL_READ);

	        if(!$data) {
	            return false;
	        }

	        $data = json_decode($data);

	        $serverdata['version'] = $data->version->name;
	        $serverdata['protocol'] = $data->version->protocol;
	        $serverdata['players'] = $data->players->online;
	        $serverdata['maxPlayers'] = $data->players->max;

	        $motd = $data->description;
	        // $motd = str_replace("§1", "<font color='#0000AA'>", $motd);
	        // $motd = str_replace("§2", "<font color='#00AA00'>", $motd);
	        // $motd = str_replace("§3", "<font color='#00AAAA'>", $motd);
	        // $motd = str_replace("§4", "<font color='#AA0000'>", $motd);
	        // $motd = str_replace("§5", "<font color='#AA00AA'>", $motd);
	        // $motd = str_replace("§6", "<font color='#FFAA00'>", $motd);
	        // $motd = str_replace("§7", "<font color='#AAAAAA'>", $motd);
	        // $motd = str_replace("§8", "<font color='#555555'>", $motd);
	        // $motd = str_replace("§9", "<font color='#5555FF'>", $motd);
	        // $motd = str_replace("§a", "<font color='#55FF55'>", $motd);
	        // $motd = str_replace("§b", "<font color='#55FFFF'>", $motd);
	        // $motd = str_replace("§c", "<font color='#FF5555'>", $motd);
	        // $motd = str_replace("§d", "<font color='#FF55FF'>", $motd);
	        // $motd = str_replace("§e", "<font color='#FFFF55'>", $motd);
	        // $motd = str_replace("§f", "<font color='#FFFFFF'>", $motd);
	        // $motd = str_replace("§r", "<font color='#FFFFFF'>", $motd);
	        // $motd = str_replace("§o", "", $motd);
	        // $motd = str_replace("§l", "", $motd);
	        // $motd = str_replace("§m", "", $motd);
	        // $motd = str_replace("§n", "", $motd);
	        //$motd = preg_replace("/[^[:alnum:][:punct:] ]/", "", $motd);

	        $serverdata['motd'] = $motd;
	        $serverdata['motd_raw'] = $data->description;
	        $serverdata['favicon'] = $data->favicon;
	        $serverdata['ping'] = $ping;

	    } else {

	        $start = microtime(true);

	        socket_send($socket, "\xFE\x01", 2, 0);
	        $length = socket_recv($socket, $data, 512, 0);

	        $ping = round((microtime(true)-$start)*1000);
	        
	        if($length < 4 || $data[0] != "\xFF") {
	            return false;
	        }

	        $motd = "";
	        $motdraw = "";
	        $serverdata['version'] = $result[0];
	        $serverdata['players'] = $result[sizeof($result)-2];
	        $serverdata['maxplayers'] = $result[sizeof($result)-1];
	        $serverdata['motd'] = $motd;
	        $serverdata['motd_raw'] = $motdraw;
	        $serverdata['ping'] = $ping;

	    }

	    $this->disconnect($socket);

	    return $serverdata;
	}

    private function connect($host, $port) {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_connect($socket, $host, $port); 
        return $socket;
    }

    private function disconnect($socket) {
        if($socket != null) {
            socket_close($socket);
        }
    }

    private function read_packet_length($socket) {
        $a = 0;
        $b = 0;
        while(true) {
            $c = socket_read($socket, 1);
            if(!$c) {
                return 0;
            }
            $c = Ord($c);
            $a |= ($c & 0x7F) << $b++ * 7;
            if( $b > 5 ) {
                return false;
            }
            if(($c & 0x80) != 128) {
                break;
            }
        }
        return $a;
    }
}