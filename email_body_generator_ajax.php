<?php
include('include/security.php');
include('include/dbcon.php');
include('include/functions.php');
$db = new DBConn();

function getAddress($contactRow)
{
    $contactArr = array();

    if ($contactRow['address']!="" && $contactRow['address']!='0') {
        $contactArr[]=$contactRow['address'];
    }
    if ($contactRow['address2']!="" && $contactRow['address2']!='0') {
        $contactArr[]=$contactRow['address2'];
    }
    if ($contactRow['city']!="" && $contactRow['city']!='0') {
        $contactArr[]=$contactRow['city'];
    }
    if ($contactRow['statename']!="" && $contactRow['statename']!='0') {
        $contactArr[]=$contactRow['statename'];
    }
    if ($contactRow['countyname']!="" && $contactRow['countyname']!='0') {
        $contactArr[]=$contactRow['countyname'];
    }
    
    $address = '';
    if (!empty($contactArr)) {
        $address = implode(', ', $contactArr);
        if ($contactRow['zipcode']!="" && $contactRow['zipcode']!='0') {
            $address .= ' '.$contactRow['zipcode'];
        }
    }
    return $address;
}

function getPhoneNumbers($propId)
{
    $db = new DBConn();
    $returnArr = array();
    $contactIds = array();
    $sqlContactToProp = "SELECT * FROM contact_to_property WHERE property_id=".$propId;
    $result = $db->query($sqlContactToProp) or die(mysql_error()." ".$sqlContactToProp);
    if($result){
        while($rowContactToProp = $db->fetchArray($result)){
            $contactIds[] = $rowContactToProp['contact_id'];
        }
    }
   
    if (!empty ($contactIds)) {
        $contactIds = implode(',', $contactIds);
        $sqlContact = "SELECT * FROM phone WHERE contact_id IN (".$contactIds.")";
        $resContact = $db->query($sqlContact) or die(mysql_error()." ".$sqlContact);
        if($resContact){
            while($rowContact = $db->fetchArray($resContact)){
                if (strlen($rowContact['number']) > 2) {
                    $returnArr[] = $rowContact['number'];
                }
            }
        }   
    }

    return implode(', ', $returnArr);
}

function getEmails($propId)
{
    $db = new DBConn();
    $returnArr = array();
    $contactIds = array();
    $sqlContactToProp = "SELECT * FROM contact_to_property WHERE property_id=".$propId;
    $result = $db->query($sqlContactToProp) or die(mysql_error()." ".$sqlContactToProp);
    if($result){
        while($rowContactToProp = $db->fetchArray($result)){
            $contactIds[] = $rowContactToProp['contact_id'];
        }
    }
   
    if (!empty ($contactIds)) {
        $contactIds = implode(',', $contactIds);
        $sqlContact = "SELECT * FROM contact WHERE id IN (".$contactIds.")";
        $resContact = $db->query($sqlContact) or die(mysql_error()." ".$sqlContact);
        if($resContact){
            while($rowContact = $db->fetchArray($resContact)){
                if (trim($rowContact['email']) != "") {
                    $returnArr[] = $rowContact['email'];
                }
            }
        }   
    }

    return implode(', ', $returnArr);
}

function getThumbs($propLoc)
{
    // mysql_select_db("planitlo_locations");
    $returnImage = '';
    $sqlThumbs = "SELECT * FROM prop_info WHERE prop_loc = '".$propLoc."'";
    if($resultThumbs = mysql_query($sqlThumbs)){
        $rowThumbs = mysql_fetch_array($resultThumbs);
        $domain = ($rowThumbs['tn_domain_loc1'] != "") ? $rowThumbs['tn_domain_loc1'] : $rowThumbs['tn_domain_loc'];
        $thumb1 = ($rowThumbs['thumb04'] != "") ? $rowThumbs['thumb04'] : $rowThumbs['thumb01'];
        $thumb2 = ($rowThumbs['thumb05'] != "") ? $rowThumbs['thumb05'] : $rowThumbs['thumb02'];
        $thumb3 = ($rowThumbs['thumb06'] != "") ? $rowThumbs['thumb06'] : $rowThumbs['thumb03'];
        $url = $rowThumbs['prop_domain_loc'];
        $prop_loc = $rowThumbs['prop_loc'];
        if(!empty($domain)){
            if(!empty($thumb1)){
                $returnImage .= '<img width="150" style="border-style: none;" src="'.$domain.$thumb1.'" alt="" />&nbsp;&nbsp;';	
            }
            if(!empty($thumb2)){
                $returnImage .= '<img width="150" style="border-style: none;" src="'.$domain.$thumb2.'" alt="" />&nbsp;&nbsp;';
            }
            if(!empty($thumb3)){
                $returnImage .= '<img width="150" style="border-style: none;" src="'.$domain.$thumb3.'" alt="" />&nbsp;&nbsp;';
            }
        }
    }
    return $returnImage;
}

$textareaInput = isset($_POST['pidTextarea']) ? $_POST['pidTextarea'] : '';
$pidArray = isset($_POST['pid']) ? $_POST['pid'] : array();
$textareaOutput = array_filter(array_map('trim', explode("\n", $textareaInput)));
$propertyIds = array_merge($textareaOutput, $pidArray);
$showOptions = isset($_POST['show_options']) ? $_POST['show_options'] : array();

if (empty($propertyIds)) {
    return false;
}

foreach ($propertyIds as $key => $propertyId) {
    $propertyIds[$key] = "'" . trim($propertyId) . "'";
}

$propertyIdsString = implode(',', $propertyIds);

$sql_list_query = "SELECT distinct property.*, contact.id as cid,
                    contact.firstname, contact.lastname, state.name as statename, county_new.name as countyname, country.printable_name as countryname
                    FROM property 
                        left join country on property.country=country.id
                    left join county_new on property.county=county_new.id 
                    left join contact_to_property on contact_to_property.property_id=property.id 
                    left join contact on  contact_to_property.contact_id=contact.id  
                    left join state on state.id = property.state";
$sql_list_query .= " WHERE property.active=1 ";
$sql_list_query .= " and property.property_id IN ($propertyIdsString)";
$sql_list_query .= " GROUP BY property.id";

$result = $db->query($sql_list_query) or die(mysql_error()." ".$sql_list_query);

$numrows = $db->getNumRows($result);

$returnHtml = '';

while($lrow = $db->fetchArray($result)) {
    $address = getAddress($lrow);
    $ownerName = $lrow['firstname'].' '.$lrow['lastname'];
    $phoneNumbers = getPhoneNumbers($lrow['id']);
    $emails = getEmails($lrow['id']);
    
    $cnx = mysql_connect('localhost', 'root', 'Securel0gi!12aa');
    mysql_select_db('planitlo_locations',$cnx);

    $thumbnailsHtml = getThumbs($lrow['property_id']);

    $linkToWeb = '';
    $linkToZip = 'https://planitlocations.com?zip='.$lrow['property_id'];
    $linkToCrm = 'https://planitlocations.com/crm/propertydetail.php?id='.$lrow['id'];
    $rs2= mysql_query("select * from prop_info where prop_loc='".$lrow['property_id']."'");
    if(mysql_num_rows($rs2)>0) {
        $rowPropInfo=mysql_fetch_array($rs2);
        $linkToWeb = $rowPropInfo["prop_domain_loc"].$rowPropInfo["prop_loc"]."?resultpage=1&amp;id=".$lrow['property_id'];
    }

    
    $propertyIdHtml = '';
    if (isset($showOptions['show_property_id'])) {
        $propertyIdHtml .= '<strong>';
        $propertyIdHtml .= $lrow['property_id'];
        $propertyIdHtml .= '</strong>';
    }

    if (isset($showOptions['show_property_id']) && isset($showOptions['show_city'])) {
        $propertyIdHtml .= '<strong>';
        $propertyIdHtml .= ' - ';
        $propertyIdHtml .= '</strong>';
    }

    if (isset($showOptions['show_city'])) {
        $propertyIdHtml .= '<strong>';
        $propertyIdHtml .= $lrow['city'];
        if (isset ($lrow['zipcode']) && $lrow['zipcode']!="") {
            $propertyIdHtml .= ' ('.$lrow['zipcode'].')';
        }
        $propertyIdHtml .= '</strong>';
    }
    
    if (isset($showOptions['show_link_on_property']) && ((isset($showOptions['show_property_id']) || isset($showOptions['show_city'])))) {
        if ($linkToWeb != '') {
            $propertyIdHtml = '<a target="_blank" href="'.$linkToWeb.'">'.$propertyIdHtml.'</a>';
        }
    }
    $copyAllIds = array();
    if ($propertyIdHtml != '') {
        $copyAllIds[] = 'prop_name_div'.$lrow['property_id'];
        $returnHtml .= '<div class="flexclass">';
        $returnHtml .= '<div id="prop_name_div'.$lrow['property_id'].'">';
        $returnHtml .= $propertyIdHtml;
        $returnHtml .= '</div>';
        $returnHtml .= '  <button class="copyatt" data-copyid="prop_name_div'.$lrow['property_id'].'">copy</button>';
        $returnHtml .= '</div>';
    }

    if (isset($showOptions['show_link_to_property'])) {
        if ($linkToWeb != '') {
            
            $returnHtml .= '<div class="flexclass">';
            $returnHtml .= '<div id="link_to_prop_'.$lrow['property_id'].'">';
            $returnHtml .= '<a  target="_blank" href="'.$linkToWeb.'">Plan-it Locations Web Gallery - '.$lrow['property_id'].'</a>';
            $returnHtml .= '</div>';
            $returnHtml .= '  <button class="copyatt" data-copyid="link_to_prop_'.$lrow['property_id'].'">copy</button>';
            $returnHtml .= '</div>';
            $copyAllIds[] = 'link_to_prop_'.$lrow['property_id'];
        }
    }

    if (isset($showOptions['show_link_to_zip'])) {
        if ($linkToZip != '') {
            $returnHtml .= '<div class="flexclass">';
            $returnHtml .= '<div id="link_to_zip_'.$lrow['property_id'].'">';
            $returnHtml .= '<a id="link_to_zip_'.$lrow['property_id'].'" target="_blank" href="'.$linkToZip.'">Plan-it Locations Zip File - '.$lrow['property_id'].'</a>';
            $returnHtml .= '</div>';
            $returnHtml .= '  <button class="copyatt" data-copyid="link_to_zip_'.$lrow['property_id'].'">copy</button>';
            $returnHtml .= '</div>';
            $copyAllIds[] = 'link_to_zip_'.$lrow['property_id'];
        }
    }

    if (isset($showOptions['show_film_permit'])) {
        if (trim($lrow['permit_type']) != '') {
            $returnHtml .= '<div class="flexclass">';
            $returnHtml .= '<div id="film_permit_'.$lrow['property_id'].'">';
            $returnHtml .= '<strong>Film Permit : </strong>'.$lrow['permit_type'];
            $returnHtml .= '</div>';
            $returnHtml .= '  <button class="copyatt" data-copyid="film_permit_'.$lrow['property_id'].'">copy</button>';
            $returnHtml .= '</div>';
            $copyAllIds[] = 'film_permit_'.$lrow['property_id'];
        }
    }

    if (count($copyAllIds) > 1) {
        $returnHtml .= '<div class="flexclass">';
        $returnHtml .= '  <button class="copyatt" data-copyall="'.implode(',', $copyAllIds).'">Copy All</button>';
        $returnHtml .= '</div>';
    }

    if (isset($showOptions['show_category'])) {
        $columnArray = array();
        $sqlCat = "SELECT * FROM sorting WHERE prop_loc = '".$lrow['property_id']."'";
        if($resCat = mysql_query($sqlCat)){
            while($rowCat = mysql_fetch_array($resCat)){
                $columnArray[] = $rowCat['category1'];
            }
        }
        if (!empty($columnArray)) {
            $returnHtml .= '</br>';
            $returnHtml .= '<strong>Categories : </strong>';
            $returnHtml .= implode(', ', $columnArray);
        }
    }
    
    if (isset($showOptions['show_address'])) {
        if ($address != '') {
            $returnHtml .= '</br>';
            $returnHtml .= '<div class="flexclass">';
            $returnHtml .= '<div id="link_to_address_'.$lrow['property_id'].'">';
            $returnHtml .= '<strong>Address : </strong>';
            $returnHtml .= $address;
            $returnHtml .= '</div>';
            $returnHtml .= '  <button class="copyatt" data-copyid="link_to_address_'.$lrow['property_id'].'">copy</button>';
            $returnHtml .= '</div>';
        }
    }

    if (isset($showOptions['show_owner_name'])) {
        if (trim($ownerName) != '') {
            $returnHtml .= '</br>';
            $returnHtml .= '<strong>Owner : </strong>';
            $returnHtml .= $ownerName;
        }
    }

    if (isset($showOptions['show_phone'])) {
        if (trim($phoneNumbers) != '') {
            $returnHtml .= '</br>';
            $returnHtml .= '<strong>Phone : </strong>';
            $returnHtml .= $phoneNumbers;
        }
    }

    if (isset($showOptions['show_email'])) {
        if (trim($emails) != '') {
            $returnHtml .= '</br>';
            $returnHtml .= '<strong>Email : </strong>';
            $returnHtml .= $emails;
        }
    }

    if (isset($showOptions['show_crm_link'])) {
        if ($linkToCrm != '') {
            $returnHtml .= '</br>';
            $returnHtml .= '<strong>Link to CRM : </strong>';
            $returnHtml .= '<a target="_blank" href="'.$linkToCrm.'">'.$linkToCrm.'</a>';
        }
    }

    if (isset($showOptions['show_thumbnails']) && trim($thumbnailsHtml) != "") {
        $returnHtml .= '</br>';
        $returnHtml .= '</br>';
        $returnHtml .= $thumbnailsHtml;
    }

    if (isset($showOptions['show_line'])) {
        $returnHtml .= '</br>';
        $returnHtml .= '....................................................................................................................................';
    }

    if ($returnHtml!="") {
        $returnHtml .= '</br>';
        $returnHtml .= '</br>';
    }
}

echo $returnHtml;
exit;
// while($lrow = $db->fetchArray($result)){