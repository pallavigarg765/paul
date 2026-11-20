<? 
	include('include/security.php');
	if(($_SESSION['userid'] == 1) || ($_SESSION['userid'] == 15) || ($_SESSION['userid'] == 16) || ($_SESSION['userid'] == 5) || ($_SESSION['userid'] == 7)){
		/* User List Grid */
		include ("include/phpmydatagrid.class.php");
		
		$objGrid = new datagrid();
		
		$objGrid -> friendlyHTML();
	
		$objGrid -> pathtoimages("./images/");
	
		$objGrid -> closeTags(true);  
		
		$objGrid -> form('county', true);
		
		$objGrid -> methodForm("post"); 
		
		$objGrid -> searchby("name");
		
		$objGrid -> decimalDigits(2);
		
		$objGrid -> decimalPoint(",");
		
		$objGrid -> tabla ("county");
	
		$objGrid -> buttons(true,true,true,true);
		
		$objGrid -> keyfield("id");
	
		$objGrid -> datarows(15);
		
		$objGrid -> paginationmode('links');
	
		$objGrid -> orderby("name", "ASC");
	
		$objGrid -> noorderarrows();
		
		$objGrid -> FormatColumn("id", "ID", 5, 5, 1, "50", "center", "integer");
		$objGrid -> FormatColumn("name", "County Name", 30, 30, 0, "150", "left");
		$objGrid -> FormatColumn("active", "Active", 2, 2, 0,"50", "center", "check:No:Yes");
		
		$objGrid -> where ("active = '1'");
	
		$objGrid -> setHeader();
	
		$objGrid -> ajax("silent");

		$objGrid -> grid();
		
		$objGrid -> desconectar();

}?>