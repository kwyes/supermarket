<? include '../header.php'; ?>

<link rel="stylesheet" href="<?=ABSOLUTE_PATH?>/css/admin.css" type="text/css">
<link rel="stylesheet" href="<?=ABSOLUTE_PATH?>/css/footer.css" type="text/css">
<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<?
$menu = ($_GET['menu']) ? $_GET['menu'] : $_POST['menu'];
if($menu == "logout")	include_once "logout.php";
?>

<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper">
		<? include 'left_menu.php'; ?>
	
		<? include 'right_content.php'; ?>
	</div>
</div>

<div class="tothetop" style="background: #f5f5f5; clear: both;">
	<a href='#top'><img src="<?=ABSOLUTE_PATH?>/img/bottom/top_button.jpg" /></a>
</div>

<? include '../footer.php'; ?>