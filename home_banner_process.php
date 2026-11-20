<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	
	$db = new DBConn();
	mysql_select_db("planitlo_crm");

	if (isset($_POST['home_banner_all']) && ($_POST['home_banner_all'] == 'home_banner_all')) {
		
		$url = "https://planitlocations.com/search/searchid.php?id=" . $_POST['property_id_'] . "&id6=&id2=&id7=&id3=&id8=&id4=&id9=&id5=&id10=";

		$query = "INSERT INTO `planitlo_crm`.`home_banner_all` SET  banner_type='1	',property_id='" . $_POST['property_id_'] . "', banner1='" . $_POST['banner1'] . "', banner2='" . $_POST['banner2'] . "', banner3='" . $_POST['banner3'] . "', url='" . $url . "', priority='" . $_POST['sort_order'] . "', show_day='" . $_POST['show_day'] . "', date='" . date('Y-m-d') . "'";
		mysql_query($query) or die(mysql_error());
		header('location:home_banner.php');
	}
	else if (isset($_POST['home_banner_single']) && ($_POST['home_banner_single'] == 'home_banner_single')) {
		
		$url = "https://planitlocations.com/search/searchid.php?id=" . $_POST['property_id_'] . "&id6=&id2=&id7=&id3=&id8=&id4=&id9=&id5=&id10=";

		$query = "INSERT INTO `planitlo_crm`.`home_banner_all` SET  banner_type='2',property_id='" . $_POST['property_id_'] . "', banner1='" . $_POST['banner1'] . "', url='" . $url . "', priority='" . $_POST['sort_order'] . "', show_day='" . $_POST['show_day'] . "', date='" . date('Y-m-d') . "'";
		mysql_query($query) or die(mysql_error());
		header('location:home_banner.php');
	}
?>

