<?php
include('include/security.php');
include('include/dbcon.php');
include('include/functions.php');
$db = new DBConn();

$sql_list_query = "SELECT * FROM copy_property where (neighborhood ='' or neighborhood IS NULL) and lat !='' and lng !='' and property_id IN (SELECT prop_loc from planitlo_locations.sorting WHERE category1 NOT LIKE '%-z' AND category1 NOT LIKE '%-prv' AND category1 NOT LIKE '%-b' AND category1 NOT LIKE '%-c' AND category1 NOT LIKE '%-d') order by id asc";

$result = $db->query($sql_list_query) or die(mysql_error()." ".$sql_list_query);

$numrows = $db->getNumRows($result);

while($lrow = $db->fetchArray($result)) {
    $id = $lrow['id'];
    $lat = $lrow['lat'];
    $lng = $lrow['lng'];

    if ($lat!="" && $lng !="") {
        $neighborhood = getNeighborhoodFromGoogle($lat, $lng);
       // Update MySQL table
        $updateQuery = "UPDATE copy_property SET neighborhood = '" . $neighborhood . "' WHERE id = " . $id;
        $db->query($updateQuery) or die(mysql_error()." ".$updateQuery);

        echo "Updated ID $id with neighborhood: $neighborhood\n";
        sleep(1); // Prevent API rate limit issues
    }
}

function getNeighborhoodFromGoogle($lat, $lng) {
    $apiKey = "AIzaSyAFdZnHYzzmdWtQtvqBi5KobK2HzmpyrOQ";
    $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&key=$apiKey";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Disable SSL verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");

    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        return 'Not Found';
    }

    $data = json_decode($response, true);

    // Extract neighborhood from response
    if (isset($data['results'])) {
        foreach ($data['results'] as $result) {
            foreach ($result['address_components'] as $component) {
                if (in_array("neighborhood", $component['types'])) {
                    return $component['long_name'];
                }
            }
        }
    }

    return 'Not Found';
}


function getExactNeighborhood2($lat, $lng) {
    $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$lat&lon=$lng&zoom=14&addressdetails=1";


	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	$response = curl_exec($ch);
	$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
	curl_close($ch);

    if ($curlError) {
        echo "cURL Error: $curlError\n";
        exit;
    }

	if ($responseCode == 200) {
		print_r($response);
	} else {
		die("File Not Found");
	}
    exit;
    $data = json_decode($response, true);
    return isset($data["address"]["neighbourhood"]) ? $data["address"]["neighbourhood"] : "Unknown";
}