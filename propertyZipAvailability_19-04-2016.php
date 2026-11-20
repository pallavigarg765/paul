<?php 
session_start();
mysql_connect("localhost", "root", "Securel0gi!12aa");
mysql_select_db("planitlo_crm");

$propertyID=$_POST['propertyID'];

$sql="SELECT property_id FROM `planitlo_crm`.`property` WHERE `id`='".$propertyID."'";
$execute=mysql_query($sql);
$rows=mysql_fetch_array($execute);


$zip=$rows['property_id']; 
$path="http://74.208.161.244/zip/";
$file_name=$zip.".zip";
$file=$path.$file_name;
$file_headers	= @get_headers($file);	
$str='';								
if($file_headers[0] == 'HTTP/1.1 200 OK')
{
	$str.='<a class="link" href="https://planitlocations.com?zip='.$rows['property_id'].'">https://planitlocations.com?zip='.$rows['property_id'].'</a>';
}
else 
{
	$str.='Not Availabled';
}
echo $str;
?>