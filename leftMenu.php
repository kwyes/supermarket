<? if($menu == "menu1") { ?>
	<a href="weeklyflyer.php" id="weeklyflyer" class="nav_border">Weekly Flyer</a>
	<a href="subscribe.php" id="subscribe" class="nav_border">Subscribe to E-Flyer </a>
	<a href="managerschoice.php" id="managerschoice" class="nav_border">Manager's Choice</a>
<? } else if($menu == "menu2") { ?>
	<a href="<?=(($LANG == "korean") ? 'company.php' : 'company-eng.php' ); ?>" id="company" class="nav_border">Company Introduction</a>
	<a href="location.php" id="location" class="nav_border">Locations & Hours </a>
	<a href="brands.php" id="brands" class="nav_border">Brands</a>
	<a href="<?=(($LANG == "korean") ? 'career.php' : 'career-eng.php' ); ?>" id="career" class="nav_border">Career</a>
	<a href="announcement.php" id="announcement" class="nav_border">15th Anniversary Announcement</a>
<? } else if($menu == "menu3") { ?>
	<a href="<?=(($LANG == "korean") ? 'membership.php' : 'membership-eng.php' ); ?>" id="membership" class="nav_border">HANNAM Membership</a>
	<a href="<?=(($LANG == "korean") ? 'giftcard.php' : 'giftcard-eng.php' ); ?>" id="giftcard" class="nav_border">Gift Card</a>
	<a href="<?=(($LANG == "korean") ? 'return.php' : 'return-eng.php' ); ?>" id="return" class="nav_border">Return & A/S</a>
	<a href="<?=(($LANG == "korean") ? 'contact.php' : 'contact-eng.php' ); ?>" id="contact" class="nav_border">Contact Us</a>
<? } else if($menu == "menu4") { ?>
	<a href="magazine.php" id="magazine" class="nav_border">HN Magazine</a>
	<a href="village.php" id="village" class="nav_border">HN Village </a>
	<a href="news.php" id="news" class="nav_border">News</a>
<? } ?>
