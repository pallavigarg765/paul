<?php 
mysql_connect("localhost", "root","Securel0gi!12aa");
mysql_select_db("planitlo_crm");
mysql_select_db("planitlo_locations");

if($_REQUEST['action']=='zip_issue')
{
	$is_zip_issue=$_REQUEST['is_zip_issue'];
	$propertyID =$_REQUEST['propertyID'];
	
	$sql="UPDATE `planitlo_crm`.`property` SET `is_zip_issue`='".$is_zip_issue."' WHERE `id`='".$propertyID."'";
	mysql_query($sql);
}	

?>