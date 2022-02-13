<?php

$remote = $_SERVER['REMOTE_ADDR'];
$server = $_SERVER['SERVER_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$referer = (isset($_SESSION['visitorUrl'])) ? $_SESSION['visitorUrl'] : "N/A";
// $count =
$date = date("j M Y g:i a");

$user = 'REDACTED';
$pass = 'REDACTED';
$db = 'REDACTED';
$table = 'users';

$mysqli = mysqli_connect("sql01.backboneservers.com", $user, $pass, $db);
if(!$mysqli)
{
    die('could not connect');
}

if($result = $mysqli->query("INSERT INTO hits
    (remoteAddr, serverAddr, userAgent, referrer, hits, date) VALUES
    ('$remote', '$server', '$user_agent', '$referer', 1, NOW()) ON DUPLICATE KEY UPDATE hits=hits+1"))
{
}
$mysqli->close();

?>
