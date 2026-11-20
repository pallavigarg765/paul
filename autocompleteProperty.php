<?php	                                       					                                       			
mysql_connect("localhost", "root", "Securel0gi!12aa");
mysql_select_db("planitlo_locations");
$sql = "SELECT DISTINCT prop_name FROM prop_info WHERE prop_name LIKE '".$_REQUEST['term']."%'";
$result = mysql_query($sql);
$return = "[ ";
while($row = mysql_fetch_array($result)){
	$return .= '{ "id": "'.$row["prop_name"].'", "label": "'.$row["prop_name"].'", "value": "'.$row["prop_name"].'" }, ';
}
$return = substr($return,0,-2);
$return .= " ]";
echo $return;
?>