<?php	                                       					                                       			
$link = mysql_connect("localhost", "root", "Securel0gi!12aa");
mysql_select_db("planitlo_crm");
$sql = "SELECT DISTINCT name FROM county_new WHERE name LIKE '".$_REQUEST['term']."%'";
$result = mysql_query($sql);
$return = "[ ";
while($row = mysql_fetch_array($result)){
	$return .= '{ "id": "'.$row["name"].'", "label": "'.$row["name"].'", "value": "'.$row["name"].'" }, ';
}
$return = substr($return,0,-2);
$return .= " ]";
echo $return;
mysql_close($link);
?>