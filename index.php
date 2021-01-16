<?php

include_once("config.php");
include_once("functions.php");

header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


$url = "https://ncore.cc/login.php";
$ref_link = "https://ncore.cc/hitnrun.php";
$params = "nev=".NAME."&pass=".PASSWORD."&submitted=1&set_lang=hu";
$cookie = getcwd()."cookies.txt";
$html = cURL($url,true,$cookie,$params);
$html = cURL($url,true,$cookie,$params);
$html = cURL($ref_link,null,$cookie,null);

$hnrAll = html_to_array($html);
$email_body = torrent_array_to_email_body($hnrAll);

if(strlen($email_body)>0){
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$ret =mail('aztamindenitnekivazze@gmail.com', 'ncore értesítés', $email_body, $headers, '-f info@eventshare.hu');
	 
	 echo "<br/>";
	 print_r($ret);
	 echo "<br/>";
	 
}
echo $email_body;

?>