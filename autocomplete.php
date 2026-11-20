<?php	                                       					                                       			
mysql_connect("localhost", "root", "Securel0gi!12aa");
mysql_select_db("planitlo_locations");

$arr = array(
	"EventArtGalleries", 
	"EventBeaches", 
	"EventCityViews", 
	"EventGardens", 
	"EventHalls", 
	"EventMansions", 
	"EventOceanViews", 
	"EventRoofTops", 
	"EventAthletic", 
	"EventConventionCenters", 
	"EventLofts", 
	"EventSchools");
$arrAll = array();
$sql = "SELECT DISTINCT category1 FROM sorting WHERE category1 LIKE '".$_REQUEST['term']."%'";
$result = mysql_query($sql);
$return = "[ ";
while($row = mysql_fetch_array($result)){
	array_push($arrAll, trim($row['category1']));
	$return .= '{ "id": "'.$row["category1"].'", "label": "'.$row["category1"].'", "value": "'.$row["category1"].'" }, ';
}

foreach($arr as $a){
	$pos = strpos($a, $_REQUEST['term']);
	if($pos === false){
		
	}else{
		if(!in_array($a, $arrAll)){
			$return .= '{ "id": "'.$a.'", "label": "'.$a.'", "value": "'.$a.'" }, ';
		}
	}
}

$return = substr($return,0,-2);
$return .= " ]";
echo $return;
?>