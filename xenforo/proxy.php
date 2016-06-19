<?php
include"proxyfuntion.php";
$startTime = microtime(true);
$fileDir = '/var/www/clients/client1/web1/web/forum';
//require($fileDir . '/library/XenForo/Autoloader.php');
//XenForo_Autoloader::getInstance()->setupAutoloader($fileDir . '/library');
//XenForo_Application::initialize($fileDir . '/library', $fileDir);
//XenForo_Application::set('page_start_time', $startTime);
//XenForo_Session::startPublicSession();
$visitor = XenForo_Visitor::getInstance()->toArray();
$username = $visitor['username'];
$id = $visitor['user_id'];
$groupa = explode(',', $visitor['secondary_group_ids']);
$advanced = false;
for($x = 0; isset($groupa[$x]); $x++){
	if ($groupa[$x] == 6) {
		$advanced = true;
		break;
	}
}
if (isset($_GET['list'])) {
	$criteria = array('secondary_group_ids' => array(6));
	$options = array('join' => XenForo_Model_User::FETCH_USER_FULL, 'order' => 'username');
    $users = $controller->getModelFromCache('XenForo_Model_User')->getUsers($criteria, $options);
//	$key = array_search(40489, array_column($users, 'username'));	
	$usernm = array_value_recursive('username', $users);
	echo file_get_contents("http://proxy.reboot.ms/redis.php?check=".base64_encode(serialize($usernm)));
    exit();
}
      

if ($id == 0){		
	echo "<center>Prima di creare un account proxy devi registrarti al <a href=\"https://www.reboot.ms/forum/articles\">forum</a>!<br>";
	echo "<form action=\"https://www.reboot.ms/forum/login/login\" method=\"post\"><br>Nome utente:<input type=\"text\" name=\"login\" required><br>Password: <input type=\"password\" name=\"password\" required><br><button type=\"submit\" name=\"redirect\" value=\"/forum/pages/ProxyLogin\">Invia</button></form></center>";
} else {
	if ($advanced) {
		echo "<script type=\"text/javascript\" src=\"https://www.reboot.ms/js/utf8_encode.js\"></script>";
		echo "<script type=\"text/javascript\" src=\"https://www.reboot.ms/js/md5.js\"></script>";
		echo "<script type=\"text/javascript\">";
		echo "function pwd_handler(form) {";
		echo "if (form.password1.value != '') {";
		echo "form.md5passorwd1.value = md5(form.password1.value);";
		echo "form.password1.value = ''; }";
		echo "if (form.password2.value != '') {";
		echo "form.md5password2.value = md5(form.password2.value);";
		echo "form.password2.value = ''; } }";
		echo "</script>";
		$user = searchuser($username);
		if ($user == "true") {
			echo "<br><center>Utente ".$username." esiste vuoi cambiare la password?</center>";
		} else {
			echo "<br><center>Utente ".$username." non esiste inserisci la nuova password</center>";
		}
		echo "<br><center><form action=\"\" method=\"get\" onsubmit=\"pwd_handler(this);\">Nuova Password:<input type=\"password\" name=\"password1\" minlength=\"6\" required><input type=\"hidden\" name=\"md5password1\" value=\"\" /><br>Ridigita password:<input type=\"password\" name=\"password2\" minlength=\"6\" required><input type=\"hidden\" name=\"md5password2\" value=\"\" /><br><button style=\"color:white;background-color:#00979C;border:2px solid #E5E2DE;padding:3px;width:15em\" type=\"submit\">Invia</button></form></center>";
		if (isset($_GET['md5password1']) && isset($_GET['md5password2'])) {
			if ($_GET['md5password1'] != null && $_GET['md5password2'] != null) {
				if ($_GET['md5password1'] == $_GET['md5password2']) {
					if ($user == "true") {
						if (modifyuser($username, $_GET['md5password1']) == "true") {
							echo "<center>Dati modificati con successo</center>";
						} else {
							echo "<center>Errore riprova più tardi</center>";
						}
					} else {
						if (newuser($username, $_GET['md5password1']) == "true") {
							echo "<center>Dati modificati con successo</center>";
						} else {
							echo "<center>Errore riprova più tardi</center>";
						}
					}
				}
			}
		}
	} else {
		echo "<br><center>Servizio riservato solo per gli <font color=\"green\">Advanced User</font></color></center>";
		echo "<center><a href=\"https://www.reboot.ms/forum/account/upgrades\">Iscriviti!!!</a></center>";
	}
}
echo "<center><br>Server Proxy: proxy.reboot.ms <-> Porta: 3128<br><br>Script creato da <a target=\"_blank\" href=\"https://www.i2cttl.com\">RyujiAndy</a></center>";
echo "<center><br>In caso di Malfunzionamento o problemi si prega di scrivere sul <a href=\"https://www.reboot.ms/forum\">Forum</a></center>";
?>
