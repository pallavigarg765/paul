<?php	                                       					                                       			 
	include('include/dbcon.php');
	$db = new DBConn();
	$postId = $_POST["id"];
	$checked = $_POST["checked"];
	$property_id = $_POST["property_id"];
	// $cb = substr($postId, 2, 1);
	// $id = substr($postId, 3);
	if($checked == "true") {
		$checked = "1";
	}else{
		$checked = "0";
	}

	if(trim($postId) !='')
	{
		$sql = "UPDATE property SET ".$postId." = '".$checked."', ".$postId."date = '".date('Y-m-d')."' WHERE id = '".$property_id."'";
	}
	
	// if($cb == "1"){
	// 	$sql = "UPDATE property SET cb1 = '".$checked."', cb1date = '".date('Y-m-d')."' WHERE id = '".$id."'";
	// }else if($cb == "2"){
	// 	$sql = "UPDATE property SET cb2 = '".$checked."', cb2date = '".date('Y-m-d')."' WHERE id = '".$id."'";
	// }else if($cb == "3"){
	// 	$sql = "UPDATE property SET cb3 = '".$checked."', cb3date = '".date('Y-m-d')."' WHERE id = '".$id."'";
	// }
	if($result = mysql_query($sql)){
		echo "ok";
	}else{ 
		echo mysql_error();
	}
	
	?>