<?php
$ip = "x.x.x.x";
$conn = new mysqli("localhost", "radius", "", "radius");
function array_value_recursive($key, array $arr){
    $val = array();
    array_walk_recursive($arr, function($v, $k) use($key, &$val){
        if($k == $key) array_push($val, $v);
    });
    return count($val) > 1 ? $val : array_pop($val);
} 

if (isset($_GET['check'])) {
	$array = unserialize(base64_decode($_GET['check']));
	$query = "SELECT * FROM `radcheck` WHERE 1";
	if (!$res = $conn->query($query)) {
		echo "errore lettura database";
		exit();
	}
	if($res->num_rows > 0) {
		for($x = 0; $x < $res->num_rows; $x++){
			$row = $res->fetch_array(MYSQLI_ASSOC);
			$val[$x] = array('id' => $row['id'], 'username' => $row['username'], 'attribute' => $row['attribute'], 'op' => $row['op'], 'value' => $row['value']);
		}
	} else {
		echo "Nessun user reggistrato";
		exit();
	}
	echo "check Advanced user<br><br>";
	for($a = 0; count($val) > $a; $a++) {
		$ck = false;
		echo $val[$a]['username']." => ";
		for($x = 0; count($array) > $x; $x++) {
			if ($val[$a]['username'] == $array[$x]) {
				echo "OK<br>";
				$ck = true;
				break;
			}
		}
		if ($ck == false) {
			echo "DELETE<br>";
			$query = "DELETE FROM `radcheck` WHERE `id` = '".$val[$a]['id']."'";
			if (!$res = $conn->query($query)) {
				echo "Errore della query: " . $conn->error . ".";
				exit();
			}
		}
	}
	echo "-------------------------------------------------------------------------------------------<br>";
	exit();
}
$rest = "false";
if ($_SERVER['REMOTE_ADDR'] == $ip) {
	if (isset($_GET['username'])) {
		$username = $_GET['username'];
	} else {
		$username = false;
	}
	if (isset($_GET['password'])) {
		$password = $_GET['password'];
	} else {
		$password = false;
	}
	if (isset($_GET['com'])) {
		$com = $_GET['com'];
		$rest = "false";
		if ($com == "ex") {
			$query = "SELECT `username`, `value` FROM `radcheck` WHERE `username` = '".$username."'";
			if (!$res = $conn->query($query)) {
				$rest = "Errore ricerca!";
			} else {
				$row = $res->fetch_array(MYSQLI_ASSOC);
				if (!isset($row['username'])) {
					$rest = "false";
				} else {
					$rest = "true";
				}
			}
		} else if ($com == "mod" && $username != (null || false) && $password != (null || false)) {
			$query = "UPDATE `radcheck` SET `value` = '".$password."' WHERE `username` = '".$username."'";
			if (!$conn->query($query)) {
				$rest = "Errore Modifica dati!";
			} else {
				$rest = "true";
			}
		} else if ($com == "new" && $username != (null || false) && $password != (null || false)) {
			$query = "INSERT INTO `radcheck` (`username`, `attribute`, `op`, `value`) VALUE ('$username', 'MD5-Password', ':=', '$password')";
			if (!$conn->query($query)) {
				$rest = "Errore Registrazione dati!";
			} else {
				$rest = "true";
			}
		}
	}
}
$xml = new SimpleXMLElement('<code/>');
$ex = $xml -> addChild('risultato', $rest);
Header('Content-type: text/xml');
echo $xml->asXML(); 
?>

