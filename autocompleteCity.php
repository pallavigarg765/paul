<?php	                                       					                                       			
mysql_connect("localhost", "root", "Securel0gi!12aa");
mysql_select_db("planitlo_crm");
$sql = "SELECT DISTINCT city FROM zip_codes2010 WHERE city LIKE '".$_REQUEST['term']."%'";
$result = mysql_query($sql);
$return = "[ ";
while($row = mysql_fetch_array($result)){
	$return .= '{ "id": "'.ucwords(strtolower($row["city"])).'", "label": "'.ucwords(strtolower($row["city"])).'", "value": "'.ucwords(strtolower($row["city"])).'" }, ';
}
$return = substr($return,0,-2);
$return .= " ]";
echo $return;
?>