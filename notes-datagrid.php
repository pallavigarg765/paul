<? 
		include('include/security.php');
		/* User List Grid */
		include ("include/phpmydatagrid.class.php");
		
		$type = $_REQUEST['type'];
		$property_id = $_REQUEST['property_id'];
				
		$objGrid = new datagrid();
	
		$objGrid -> friendlyHTML();
	
		$objGrid -> pathtoimages("./images/");
	
		$objGrid -> closeTags(true);  
		
		$objGrid -> form('notes', true);
		
		$objGrid -> methodForm("post"); 
		
		$objGrid -> searchby("text");
		
		$objGrid -> decimalDigits(2);
		
		$objGrid -> decimalPoint(",");
		
		$objGrid -> tabla ("notes");
	
		$objGrid -> buttons(true,true,true,true);
		
		$objGrid -> keyfield("id");
	
		$objGrid -> datarows(15);
		
		$objGrid -> paginationmode('links');
	
		$objGrid -> orderby("id", "DESC");
	
		$objGrid -> noorderarrows();
		
		$objGrid -> FormatColumn("text", "Notes", 30, 30, 0, "150", "left");
		
		$objGrid -> where ("type =".$type." and property_id=".$property_id);
	
		$objGrid -> setHeader();
	
		$objGrid -> ajax("silent");

		$objGrid -> grid();
		
		$objGrid -> desconectar();

?>