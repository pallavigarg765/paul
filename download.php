<?php 
if($_POST['action']=='download')
{
	echo $_POST['propLoc'].".zip";
}
?>