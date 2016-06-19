<?php
$url = "http://proxyserver.com/radius.php";

function array_value_recursive($key, array $arr){
    $val = array();
    array_walk_recursive($arr, function($v, $k) use($key, &$val){
        if($k == $key) array_push($val, $v);
    });
    return count($val) > 1 ? $val : array_pop($val);
} 

function searchuser($username) {
	global $url;
	$xml = new SimpleXMLElement(file_get_contents($url."?username=".$username."&com=ex"));
	$res = $xml->risultato;
	return $res;
}

function modifyuser($username, $pass) {
	global $url;
	$xml = new SimpleXMLElement(file_get_contents($url."?username=".$username."&password=".$pass."&com=mod"));
	$res = $xml->risultato;
	return $res;
}
function newuser($username, $pass) {
	global $url;
	$xml = new SimpleXMLElement(file_get_contents($url."?username=".$username."&password=".$pass."&com=new"));
	$res = $xml->risultato;
	return $res;
}
?>
