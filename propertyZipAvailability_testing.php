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
	$url='https://planitlocations.com?zip='.$rows['property_id'];
	$str.=$url;
	//$str.='<a class="link" href="https://planitlocations.com?zip='.$rows['property_id'].'" style="display:none;" id="generate_zip_url">https://planitlocations.com?zip='.$rows['property_id'].'</a>';
	//$str.='<input id="select2" name="select2" type="text" value="https://planitlocations.com?zip='.$rows['property_id'].'" size="35" style="display:none;" />';										
	//$str.="<a class=\"button nwSmbtn\" id=\"zip_copy_url\"  onclick=\"document.getElementById('select2').style.display='block'; var a =  fieldtoclipboard.copyfield(event, 'select2'); document.getElementById('select2').style.display='none'; return a;\">Copy URL</a>";										
}
else 
{
	$str.='Not Available';
}
echo $str;
?>
