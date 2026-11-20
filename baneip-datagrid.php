<? 
	include('include/security.php');
	if(($_SESSION['userid'] == 1) || ($_SESSION['userid'] == 15) || ($_SESSION['userid'] == 16) || ($_SESSION['userid'] == 5) || ($_SESSION['userid'] == 7)){
		/* User List Grid */
		include ("include/phpmydatagrid.class.php");
		
		$objGrid = new datagrid();
		
		$objGrid -> friendlyHTML();
	
		$objGrid -> pathtoimages("./images/");
	
		$objGrid -> closeTags(true);  
		
		$objGrid -> form('iplog', true);
		
		$objGrid -> methodForm("post"); 
		
		$objGrid -> searchby("ip");
		
		$objGrid -> decimalDigits(2);
		
		$objGrid -> decimalPoint(",");
		
		$objGrid -> tabla ("iplog");
	
		$objGrid -> buttons(true,true,true,true);
		
		$objGrid -> keyfield("id");
	
		$objGrid -> datarows(15);
		
		$objGrid -> paginationmode('links');
	
		$objGrid -> orderby("id", "DESC");
	
		$objGrid -> noorderarrows();
		
		$objGrid -> FormatColumn("id", "ID", 5, 5, 1, "50", "center", "integer");
		$objGrid -> FormatColumn("username", "User Name", 30, 30, 0, "150", "center");
		$objGrid -> FormatColumn("ip", "IP", 30, 30, 0, "150", "center");
		$objGrid -> FormatColumn("datentime", "Date & Time", 30, 30, 0, "150", "center");
		$objGrid -> FormatColumn("active", "Allowed", 2, 2, 0,"50", "center", "check:No:Yes");
		
		$objGrid -> where ("id !='' and username != 'tester' and username != 'paul'");
	
		$objGrid -> setHeader();
	
		$objGrid -> ajax("silent");

		$objGrid -> grid();
		
		$objGrid -> desconectar();

}?>