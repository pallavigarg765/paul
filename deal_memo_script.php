<?php
include('include/security.php');
include('include/dbcon.php');
include('include/functions.php');
$db = new DBConn();

$sql_list_query = "SELECT * from pdf_deal_memo";
$sql_list_query .= " WHERE property_id IS NULL";

$result = $db->query($sql_list_query) or die(mysql_error()." ".$sql_list_query);

$numrows = $db->getNumRows($result);

while($lrow = $db->fetchArray($result)) {
    $address = explode(',', $lrow['address']);
    $address = isset($address[0]) && $address[0] !="" ? trim($address[0]) : null; 
    if (!is_null($address)) {
        $address = rtrim($address, '.');
        $sql_list_query2 = "SELECT distinct property.id, property.property_id, property.zipcode, property.property_id, property.company,property.city,contact.id as cid,
        contact.firstname, contact.lastname, state.name as statename
            FROM property 
            left join contact_to_property on contact_to_property.property_id=property.id 
            left join contact on  contact_to_property.contact_id=contact.id  
            left join state on state.id = property.state ";
        $sql_list_query2 .= " WHERE property.active=1 ";
        $sql_list_query2 .= " and property.address = '".addslashes($address)."' ";
        $sql_list_query2 .= "  GROUP BY property.id "; 

        $result2 = $db->query($sql_list_query2) or die(mysql_error()." ".$sql_list_query2);
        $numrows2 = $db->getNumRows($result2);
        echo $lrow['id'] .' -> '. $numrows2;
        echo '<br>';
        // if ($numrows2 == 1) {
        //     while($rrrow = $db->fetchArray($result2)) {
        //         echo $lrow['id'] .' -> '. $rrrow['id'];
        //         $sql = "UPDATE `pdf_deal_memo` SET `property_id` = '".addslashes($rrrow['id'])."'";
        //         $sql .= " WHERE id = '".$lrow['id']."'";
        //         $db->query($sql) or die($sql);
        //     }
        //     echo '<br>';
        // }
    }
    
}
exit;