<?php
	include('include/config.php');	                                       					                                       			
	session_start();
	session_destroy();
	header("location: ".APP_WEB_ROOT.'login.php');
	exit();
?>