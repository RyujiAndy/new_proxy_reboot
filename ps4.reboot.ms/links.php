<?PHP
function URLIsValid($url) {
	if (@file_get_contents($url,false,NULL,0,1)) {
        return true;
    }
    return false;
}

if (isset($_GET['URL'])) {
	if (substr($_GET['URL'], 0, 7) != "http://" || substr($_GET['URL'], 0 ,8) != "https://") $url = "http://".$_GET['URL'];
	if (URLIsValid($url)) {
		header("Location: ".$url);
	} else {
		header("Location: https://www.google.com/search?q=".$_GET['URL']);
	}
} else {
	echo "<form method=\"get\"><p style=\"text-align: center;\">Insert URL: <input name=\"URL\" type=\"text\" /></p><p style=\"text-align: center;\"><input type=\"reset\" /> <input type=\"submit\" /></p></form>";
}
?>
