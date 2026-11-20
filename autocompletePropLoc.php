<?php	                                       					                                       			
$link = mysql_connect("localhost", "root", "Securel0gi!12aa");
mysql_select_db("planitlo_crm");
$sql = "SELECT DISTINCT property_id FROM property WHERE property_id LIKE '".$_REQUEST['term']."%'";
$result = mysql_query($sql);
$return = "[ ";
while($row = mysql_fetch_array($result)){
	$return .= '{ "id": "'.$row["property_id"].'", "label": "'.$row["property_id"].'", "value": "'.$row["property_id"].'" }, ';
}
$return = substr($return,0,-2);
$return .= " ]";
echo $return;
mysql_close($link);
?>