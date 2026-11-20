<?php 
session_start();
mysql_connect("localhost", "root", "Securel0gi!12aa");
mysql_select_db("planitlo_crm");

$propertyID=$_POST['propertyID'];

$sql="SELECT property_id FROM `planitlo_crm`.`property` WHERE `id`='".$propertyID."'";
$execute=mysql_query($sql);
$rows=mysql_fetch_array($execute);


$zip=trim($rows['property_id']); 
$path="https://planitserver.com/zip/";
$file_name=$zip.".zip";
$url=$path.$file_name;

// Initialize cURL session
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_NOBODY, true); // Only check the header
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL verification (if needed)
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

// Execute cURL request
curl_exec($ch);

// Get the HTTP response code
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Close cURL session
curl_close($ch);

$str='';
// Check if the file exists
if ($httpCode == 200) {
    $str.='<a class="link" href="https://planitlocations.com?zip='.$rows['property_id'].'">https://planitlocations.com?zip='.$rows['property_id'].'</a>';
	$str.='<input id="select2" name="select2" type="text" value="https://planitlocations.com?zip='.$rows['property_id'].'" size="35" style="display:none;" />';										
} else {
	$str.='Not Available';
}

echo $str;
?>