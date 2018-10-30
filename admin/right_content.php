<?
$list = ($_GET['list']) ? $_GET['list'] : $_POST['list'];

if(!isset($_SESSION['memberId'])) {
	include_once "login.php";

} else if(isset($_SESSION['memberId'])) {
	switch($menu) {
		default : {
			include_once "main.php";
			break;
		}
		case "main" : {
			switch($list) {
				default : {
					include_once "main.php";
					break;
				}
				case "slideAd" : {
					include_once "main_slideAd.php";
					break;
				}
				case "left" : {
					include_once "main_left.php";
					break;
				}
				case "middle" : {
					include_once "main_middle.php";
					break;
				}
				case "promoMedia" : {
					include_once "main_promoMedia.php";
					break;
				}
				case "popup" : {
					include_once "main_popup.php";
					break;
				}
			}
			break;
		}
		case "menu1" : {
			switch($list) {
				default : {
					include_once "menu1.php";
					break;
				}
				case "weeklyFlyer" : {
					include_once "menu1_weeklyFlyer.php";
					break;
				}
				case "weeklyFlyer_write" : {
					include_once "menu1_weeklyFlyer_write.php";
					break;
				}
				case "eFlyer_member" : {
					include_once "menu1_eFlyer_member.php";
					break;
				}
				case "eFlyer_reserve" : {
					include_once "menu1_eFlyer_reserve.php";
					break;
				}
				case "eFlyer_send" : {
					include_once "menu1_eFlyer_send.php";
					break;
				}
				case "managerChoice" : {
					include_once "menu1_managerChoice.php";
					break;
				}
				case "managerChoice_write" : {
					include_once "menu1_managerChoice_write.php";
					break;
				}
			}
			break;
		}
		case "menu2" : {
			switch($list) {
				default : {
					include_once "menu2.php";
					break;
				}
				case "career" : {
					include_once "menu2_career.php";
					break;
				}
				case "career_view" : {
					include_once "menu2_career_view.php";
					break;
				}
			}
			break;
		}
		case "menu3" : {
			switch($list) {
				default : {
					include_once "menu3.php";
					break;
				}
				case "membership_new" : {
					include_once "menu3_membership_new.php";
					break;
				}
				case "membership_all" : {
					include_once "menu3_membership_all.php";
					break;
				}
				case "membership_detail" : {
					include_once "menu3_membership_detail.php";
					break;
				}
				case "contactus" : {
					include_once "menu3_contactUs.php";
					break;
				}
				case "contactus_view" : {
					include_once "menu3_contactUs_view.php";
					break;
				}
			}
			break;
		}
		case "menu4" : {
			switch($list) {
				default : {
					include_once "menu4.php";
					break;
				}
				case "magazine" : {
					include_once "menu4_magazine.php";
					break;
				}
				case "magazine_write" : {
					include_once "menu4_magazine_write.php";
					break;
				}
				case "village" : {
					include_once "menu4_village.php";
					break;
				}
				case "village_write" : {
					include_once "menu4_village_write.php";
					break;
				}
				case "news" : {
					include_once "menu4_news.php";
					break;
				}
				case "news_write" : {
					include_once "menu4_news_write.php";
					break;
				}
			}
			break;
		}
		case "menu5" : {
			switch($list) {
				default : {
					include_once "menu5.php";
					break;
				}
				case "member_list" : {
					include_once "menu5_member_list.php";
					break;
				}
				case "member_detail" : {
					include_once "menu5_member_detail.php";
					break;
				}
				case "member_add" : {
					include_once "menu5_member_detail.php";
					break;
				}
				case "site_hit" : {
					include_once "menu5_hit_counter.php";
					break;
				}
				case "event" : {
					include_once "menu5_event.php";
					break;
				}
			}
			break;
		}
	}
	
	include_once 'include_db_disconnect.php';
}
?>

