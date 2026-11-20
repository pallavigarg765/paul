<?php 
session_start();
if(isset($_GET['id']))
{
	$_SESSION['property_id']=$_GET['id'];
	header("location:https://planitlocations.com/crm/login.php");
}
?>