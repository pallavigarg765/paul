<?php
include('include/security.php');
include('include/dbcon.php');
include('include/config.php');
include('include/functions.php');
$db = new DBConn();
ini_set('error_reporting',E_ALL);
error_reporting(1);

$query2 = "SELECT *,(SELECT name from state where id=prop_temp.state) as state_name FROM prop_temp WHERE lat IS NULL AND lng IS NULL LIMIT 100";
echo '<pre>';
if($resStates = $db->query($query2)){
    while($rowStates = $db->fetchArray($resStates)){
        
        $add_array = array();
        $add_array[] = $rowStates['address'];
        $add_array[] = $rowStates['city'];
        $add_array[] = $rowStates['state_name'];
        $add_array[] = $rowStates['zipcode'];
        $add_array[] = 'USA';
        
        $address = implode(', ',array_filter($add_array));
        
        $curl = curl_init('http://us1.locationiq.com/v1/search.php?key=d1a64ff0c1dff2&q='.$address.'&format=json');
        curl_setopt_array($curl, array(
          CURLOPT_RETURNTRANSFER    =>  true,
          CURLOPT_FOLLOWLOCATION    =>  true,
          CURLOPT_MAXREDIRS         =>  10,
          CURLOPT_TIMEOUT           =>  30,
          CURLOPT_CUSTOMREQUEST     =>  'GET',
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
          echo 'Not added ->'.$rowStates['id'];
        } else {
          $res = json_decode($response,true);
          if(isset($res[0]['lat']) && $res[0]['lat']!='')
          {
                echo $sql = "UPDATE prop_temp SET lat='".$res[0]['lat']."',lng='".$res[0]['lon']."'  WHERE id='".$rowStates['id']."';";
                echo '<br>';
                
            }else
            {
                echo 'Not added ->'.$rowStates['id'];
                echo '<br>';
            }
          
        }



    }
}





