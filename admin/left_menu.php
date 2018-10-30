<nav id="side">
	<h3>ADMIN</h3>

	<? if(!isset($_SESSION['memberId'])) { ?>
		<a href="admin.php" id="membership" class="nav_border_b">Login</a>
	<? } else { ?>
		<a href="admin.php?menu=main" class="<?=(($menu == "main" || $menu == "") ? "nav_border_b" : "nav_border" ); ?>">Main Page</a>
		<a href="admin.php?menu=menu1" class="<?=(($menu == "menu1") ? "nav_border_b" : "nav_border" ); ?>">Weekly Flyer</a>
		<a href="admin.php?menu=menu2" class="<?=(($menu == "menu2") ? "nav_border_b" : "nav_border" ); ?>">HANNAM Supermarket</a>
		<a href="admin.php?menu=menu3" class="<?=(($menu == "menu3") ? "nav_border_b" : "nav_border" ); ?>">Customer Service</a>
		<a href="admin.php?menu=menu4" class="<?=(($menu == "menu4") ? "nav_border_b" : "nav_border" ); ?>">Community</a>
		<? if($_SESSION['memberLevel'] == "admin") { ?><a href="admin.php?menu=menu5" class="<?=(($menu == "menu5") ? "nav_border_b" : "nav_border" ); ?>">Site Info</a><? } ?>
		<a href="admin.php?menu=logout" class="<?=(($menu == "logout") ? "nav_border_b" : "nav_border" ); ?>">Logout</a>
	<? } ?>
</nav>