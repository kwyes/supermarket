<?
session_start();

define("ABSOLUTE_PATH", "http://www.hannamsm.com");
define("ABSOLUTE_PATH2", "http://hannamsm.com");

// Configuring connection type
if (preg_match('/iPhone|iPod/i', $_SERVER['HTTP_USER_AGENT'])) 
    $_SESSION['browser'] = 'IPhone';
else if (preg_match('/Android/i', $_SERVER['HTTP_USER_AGENT'])) 
    $_SESSION['browser'] = 'Android';
else
    $_SESSION['browser'] = 'Others';

// Configuring Hit counter
$_SESSION['hit'] = ((!$_SESSION['hit']) ? "no" : $_SESSION['hit']);

// Configuring Language
if(!$_COOKIE['LANG'])	$LANG = "korean";
else					$LANG =	$_COOKIE['LANG'];

include_once "include_db.php";
include_once "common_function.php";
include_once "hit_counter.php";
?>