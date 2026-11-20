<? 
	include('include/security.php');
		/* User List Grid */
		include ("include/phpmydatagrid.class.php");
		
		$objGrid = new datagrid();
	
		$objGrid -> friendlyHTML();
	
		$objGrid -> pathtoimages("./images/");
	
		$objGrid -> closeTags(true);  
		
		$objGrid -> form('user', true);
		
		$objGrid -> methodForm("post"); 
		
		$objGrid -> searchby("username");
		
		$objGrid -> decimalDigits(2);
		
		$objGrid -> decimalPoint(",");
		
		$objGrid -> tabla ("user");
	
		$objGrid -> buttons(false,true,false,false);
		
		$objGrid -> keyfield("id");
	
		$objGrid -> datarows(15);
		
		$objGrid -> paginationmode('links');
	
		$objGrid -> orderby("username", "ASC");
	
		$objGrid -> noorderarrows();
		
		$objGrid -> FormatColumn("id", "ID", 5, 5, 1, "50", "center", "integer");
		$objGrid -> FormatColumn("username", "User Name", 30, 30, 0, "150", "left");
		$objGrid -> FormatColumn("password", "Password", 30, 30, 0, "150", "left");
		$objGrid -> FormatColumn("active", "Active", 2, 2, 0,"50", "center", "check:No:Yes");
		
		$objGrid -> where ("active = '1' and id =".$_SESSION['userid']);
	
		$objGrid -> setHeader();
	
		$objGrid -> ajax("silent");

		$objGrid -> grid();
		
		$objGrid -> desconectar();

?>