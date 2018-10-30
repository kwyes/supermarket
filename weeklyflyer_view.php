<?
include_once "admin/include_preset.php";
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);

//$seq = $_GET['seq'];
$type = $_GET['type'];
$flyer = $_GET['flyer'];
$path = ABSOLUTE_PATH."/upload/weekly_flyer/";

// Updating click_counter_email
if($type == "Korean")	$db_type = 1;
if($type == "Chinese")	$db_type = 4;
$query = "UPDATE new_regularUpdate SET ".
		 "click_counter_email = (SELECT click_counter_email FROM new_regularUpdate WHERE type = $db_type AND link = '$flyer') + 1 ".
		 "WHERE type = $db_type AND link = '$flyer'";
mssql_query($query, $conn_hannam);

include_once 'admin/include_db_disconnect.php';

Header("Location:upload/weekly_flyer/".$type."/".$flyer); 
?>

<!--
<html>
<body style="margin:0;">
	<embed width="100%" height="100%" src="<?=$path.$type.'/'.$flyer ?>" type="application/pdf">
</body>
</html>
-->