<footer>
<div class="footer_wrapper">
	<div class="footer_sitemap">
		<div class="sitemap_weeklyflyer">
			<h5>Weekly Flyer</h5>

			<ul class="footer_list">
				<li><a href="<?=ABSOLUTE_PATH?>/weeklyflyer.php">Weekly Flyer</a></li>
				<li><a href="<?=ABSOLUTE_PATH?>/subscribe.php">Subscribe to E-Flyer</a></li>
				<li><a href="<?=ABSOLUTE_PATH?>/managerschoice.php">Manager's Choice</a></li>
			</ul>
		</div>

		<div class="sitemap_hannamsupermarket">
			<h5>HANNAM Supermarket</h5>

			<ul class="footer_list">
				<li><a href="<?=ABSOLUTE_PATH?>/<?=(($LANG == "korean") ? 'company.php' : 'company-eng.php' ); ?>">Company Introduction</a></li>
				<li><a href="<?=ABSOLUTE_PATH?>/location.php">Locations & Business Hours</a></li>
				<li><a href="<?=ABSOLUTE_PATH?>/brands.php">Brands</a></li>
				<li><a href="<?=ABSOLUTE_PATH?>/career.php">Career</a></li>
				<li><a href="<?=ABSOLUTE_PATH?>/announcement.php">15th Anniversary Anouncement</a></li>
			</ul>
		</div>

		<div class="sitemap_customerservice">
			<h5>Customer Service</h5>

			<ul class="footer_list">
				<li><a href="<?=ABSOLUTE_PATH?>/<?=(($LANG == "korean") ? 'membership.php' : 'membership-eng.php' ); ?>">Hannam Membership</a></li>
				<li><a href="<?=ABSOLUTE_PATH?>/<?=(($LANG == "korean") ? 'giftcard.php' : 'giftcard-eng.php' ); ?>">Gift Card</a></li>
				<li><a href="<?=ABSOLUTE_PATH?>/<?=(($LANG == "korean") ? 'return.php' : 'return-eng.php' ); ?>">Return & A/S</a></li>
				<li><a href="<?=ABSOLUTE_PATH?>/<?=(($LANG == "korean") ? 'contact.php' : 'contact-eng.php' ); ?>">Contact Us</a></li>
			</ul>
		</div>

		<div class="sitemap_community">
			<h5>Community</h5>

			<ul class="footer_list">
				<li><a href="<?=ABSOLUTE_PATH?>/magazine.php">HN Magazine</a></li>
				<li><a href="<?=ABSOLUTE_PATH?>/village.php">HN Village</a></li>
				<li><a href="<?=ABSOLUTE_PATH?>/news.php">News</a></li>
			</ul>
		</div>
	</div>

	<div class="hannam_address">
		<h6>HANNAM Supermarket, Burnaby</h6>

		<ul class="footer_list_address">
			<li><a href="https://www.google.ca/maps/place/Hannam+Supermarket+Burnaby/@49.2446785,-122.8911245,15z/data=!4m2!3m1!1s0x5486783d3afa65b1:0xbb3a662147ce5ba8?hl=ko/"><i class="icon-location-circled"></i> #106-4501 North Rd, Burnaby V3N 4R7</a></li>
			<li><a href="tel:604-420-8856"><i class="icon-phone-circled"></i> 604-420-8856</a></li>
			<li><a href="#"><i class="icon-clock-circled"></i> 08:30am~10:00pm Everyday</a></li>
		</ul>

		<h6>HANNAM Supermarket, Surrey</h6>

		<ul class="footer_list_address">
			<li><a href="https://www.google.ca/maps/place/Hannam+Supermarket+Inc/@49.1958134,-122.7959611,16z/data=!4m2!3m1!1s0x5485d7122c4b321f:0x20b64b487fc3ff4b?hl=ko/"><i class="icon-location-circled"></i> #1-15357 104 Ave. Surrey V3R 1N5</a></li>
			<li><a href="tel:604-580-3433"><i class="icon-phone-circled"></i> 604-580-3433</a></li>
			<li><a href="#"><i class="icon-clock-circled"></i> 08:30am~10:00pm Everyday</a></li>
		</ul>
	</div>
</div><!-- footer_wrapper  -->
</footer>

<div class="footer_extra">
	<div class="footer_extra_wrapper">
		<div class="extra1"><img src="<?=ABSOLUTE_PATH?>/img/bottom/bottom_logo.png" /></div>
		<div class="extra2">Copyright 2015 © Hannam Supermarket.  All Rights Reserved.</div>
		<div class="extra3"><a href="<?=ABSOLUTE_PATH?>/<?=(($LANG == "korean") ? 'contact.php' : 'contact-eng.php' ); ?>"><img src="<?=ABSOLUTE_PATH?>/img/bottom/mail-circled.svg" /></a></div>
	</div>
</div><!-- footer_extra  -->

</body>
</html>

<? include_once 'admin/include_db_disconnect.php'; ?>