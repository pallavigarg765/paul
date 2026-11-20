<?php 
ini_set('error_reporting', E_ALL);

include('include/security.php');
include('include/dbcon.php');
include('include/config.php');
include('include/functions.php');

//mysql_select_db("planitlo_crm");    

$returnArr = [
    'success' => false,
    'data' => [],
];

$propertyID = $_REQUEST['propertyID'];
if (!$propertyID || $propertyID == '') {
    echo 1111; exit; 
}
echo 1111;
exit;
$sql = "SELECT property.*,state.name as statename, county_new.name as countyname, country.printable_name as countryname FROM property  
			left join state on property.state=state.id
			left join country on property.country=country.id
			left join county_new on property.county=county_new.id 
			WHERE property.property_id=".$propertyID;