<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
	mysql_select_db("planitlo_crm");

	
	$sql = "SELECT * FROM `test_pdf_deal_memo` ORDER BY id DESC";
	$dealMemoItems = $db->query($sql) or die($sql);

	while($lrow = $db->fetchArray($result)){
		
	}
	
?>