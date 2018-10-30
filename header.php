<? include "admin/include_preset.php"; ?>
<!DOCTYPE HTML>
<html>
<head>
	<title> HanNam 수퍼 - 최초 밴쿠버 한인사회 대형마트 </title>
	<meta charset="utf-8">
	<meta name="Author" content="WESTVIEW">
	<meta name="Keywords" content="한남슈퍼마켓, 한남수퍼마켓, hannam supermarket, hannam, mart, mall, supermarket, hmart, h-mart, vanchosun, vancouver, Korean Supermarket, Korean Food, Kimchi">
	<meta name="Description" content="최초 밴쿠버 한인사회 대형마트, Hannam supermarket is Korean supermarket located in Burnaby, B.C and Surrey, B.C. ">

	<link href='http://fonts.googleapis.com/css?family=Ubuntu:400,500,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?=ABSOLUTE_PATH?>/CSS/reset.css" type="text/css"> 
	<link rel="stylesheet" href="<?=ABSOLUTE_PATH?>/CSS/right-arrow.css" type="text/css">
	<link rel="stylesheet" href="<?=ABSOLUTE_PATH2?>/CSS/right-arrow.css" type="text/css">
	<link rel="stylesheet" href="<?=ABSOLUTE_PATH?>/CSS/header.css" type="text/css">

	<link href='http://fonts.googleapis.com/css?family=Signika:700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Archivo+Narrow:400italic' rel='stylesheet' type='text/css'>
	<link rel="icon" type="image/png" href="<?=ABSOLUTE_PATH?>/favicon.ico">
</head>

<script>
function change_language(select) {
	var select_id = select.options[select.selectedIndex].value;
	language_setCookie('LANG', select_id, 1);
}

function language_setCookie(name, value, expiredays) {
	var todayDate = new Date(); 
	todayDate.setDate(todayDate.getDate() + expiredays); 
	document.cookie = name + '=' + escape(value) + '; path=/; expires=' + todayDate.toGMTString() + ';' 
	var curURL = location.href;
	if(value == "korean") {
		var fromURL = ["company-eng.php", "membership-eng.php", "giftcard-eng.php", "return-eng.php", "contact-eng.php", "career-eng.php"];
		var toURL = ["company.php", "membership.php", "giftcard.php", "return.php", "contact.php", "career.php"];
	}
	if(value == "english") {
		var fromURL = ["company.php", "membership.php", "giftcard.php", "return.php", "contact.php", "career.php"];
		var toURL = ["company-eng.php", "membership-eng.php", "giftcard-eng.php", "return-eng.php", "contact-eng.php", "career-eng.php"];
	}
	for(var i = 0; i < fromURL.length; i++) {
		var check_URL = curURL.indexOf(fromURL[i])
		if(check_URL != -1) {
			curURL = curURL.replace(fromURL[i], toURL[i]);
			break;
		}
	}

	location.replace(curURL);
}
</script>

<a name="top"></a>
<body>
  <title>This is the title</title>

<header>
<div id=header_wrapper>
	<div id=header_logo>
		<a href="<?=ABSOLUTE_PATH?>"><img src="<?=ABSOLUTE_PATH?>/img/hannam_logo.png" /></a>
	</div>

	<div id=header_login_wrapper>
		<div id=header_login1>
			<a href="<?=ABSOLUTE_PATH?>/weeklyflyer.php"><img src="<?=ABSOLUTE_PATH?>/img/weekly_flyer_icon.png"></a>
		</div>
		<div id=header_login2>
			<a href="<?=ABSOLUTE_PATH?>/<?=(($LANG == "korean") ? 'membership.php' : 'membership-eng.php' ); ?>">Join Membership Online</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
			<a href="<?=ABSOLUTE_PATH?>/<?=(($LANG == "korean") ? 'membership.php#points' : 'membership-eng.php#points' ); ?>" name="bottom">Check points</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
			<select name="select_language" onchange="change_language(this);">
				<option value="korean" <?=(($LANG == "korean") ? "selected=selected" : "" );?>>Korean</option>
				<option value="english" <?=(($LANG == "english") ? "selected=selected" : "" );?>>English</option>
			</select>
		</div>
	</div>
</div>
</header>


<div id=nav_bg>
	<div id=nav_wrapper>
		<nav id="main">
			<ul id="navmenu">
				<li>
					<a href="#" aria-haspopup="true">Weekly Flyer&nbsp;<i class="icon-angle-circled-right"></i></a>
					<ul class="sub1">
						<li><a href="<?=ABSOLUTE_PATH?>/weeklyflyer.php">Weekly Flyer</a></li>
						<li><a href="<?=ABSOLUTE_PATH?>/subscribe.php">Subscribe to E-Flyer</a></li>
						<li><a href="<?=ABSOLUTE_PATH?>/managerschoice.php">Manager's Choice</a></li>
					</ul>
				</li>

				<li>
					<a href="#">HANNAM Supermarket&nbsp;<i class="icon-angle-circled-right"></i></a>
					<ul class="sub1">
						<li>
							<? if($LANG == "korean") { ?><a href="<?=ABSOLUTE_PATH?>/company.php">Company Introduction</a>
							<? } else { ?><a href="<?=ABSOLUTE_PATH?>/company-eng.php">Company Introduction</a><? } ?>
						</li>
						<li><a href="<?=ABSOLUTE_PATH?>/location.php">Locations & Business Hours</a></li>
						<li><a href="<?=ABSOLUTE_PATH?>/brands.php">Brands</a></li>
						<li>
							<? if($LANG == "korean") { ?><a href="<?=ABSOLUTE_PATH?>/career.php">Career</a>
							<? } else { ?><a href="<?=ABSOLUTE_PATH?>/career-eng.php">Career</a><? } ?>
						</li>
						<li><a href="<?=ABSOLUTE_PATH?>/announcement.php">15th Anniversary Announcement</a></li>
					</ul>
				</li>

				<li>
					<a href="#">Customer Service&nbsp;<i class="icon-angle-circled-right"></i></a>
					<ul class="sub1">
						<li>
							<? if($LANG == "korean") { ?><a href="<?=ABSOLUTE_PATH?>/membership.php">Hannam Membership</a>
							<? } else { ?><a href="<?=ABSOLUTE_PATH?>/membership-eng.php">Hannam Membership</a><? } ?>
						</li>
						<li>
							<? if($LANG == "korean") { ?><a href="<?=ABSOLUTE_PATH?>/giftcard.php">Gift Card</a>
							<? } else { ?><a href="<?=ABSOLUTE_PATH?>/giftcard-eng.php">Gift Card</a><? } ?>
						</li>
						<li>
							<? if($LANG == "korean") { ?><a href="<?=ABSOLUTE_PATH?>/return.php">Return & A/S</a>
							<? } else { ?><a href="<?=ABSOLUTE_PATH?>/return-eng.php">Return & A/S</a><? } ?>
						</li>
						<li>
							<? if($LANG == "korean") { ?><a href="<?=ABSOLUTE_PATH?>/contact.php">Contact Us</a>
							<? } else { ?><a href="<?=ABSOLUTE_PATH?>/contact-eng.php">Contact Us</a><? } ?>
						</li>
					</ul>
				 </li> 

				<li>
					<a href="#">Community&nbsp;<i class="icon-angle-circled-right"></i></a>
					<ul class="sub1">
						<li><a href="<?=ABSOLUTE_PATH?>/magazine.php">HN Magazine</a></li>
						<li><a href="<?=ABSOLUTE_PATH?>/village.php">HN Village</a></li>
						<li><a href="<?=ABSOLUTE_PATH?>/news.php">News</a></li>
					</ul>
				</li>
			</ul>
		</nav>
	</div> <!-- nav_wrapper  -->
</div><!-- nav_bg -->

