<? 

	include('include/security.php');
	include('include/dbcon.php');
	include('include/functions.php');
	$db = new DBConn();
	
	/*$nameorder = 'ASC';
	$nameorder = 'arrow';
	$idorder = 'ASC';
	$idarrow = 'arrow';
	$propertynameorder = 'ASC';
	$propertynamearrow = 'arrow';
*/	
$select_contact_query="SELECT (SELECT property_id FROM property where id=contact_to_property.property_id) as property_id_str ,contact.* FROM contact_to_property inner join contact on contact_to_property.contact_id=contact.id where contact_to_property.property_id>=10000";
$select_contact_query = $db->query($select_contact_query) or die(mysql_error()." ".$select_contact_query);

$filename = "property_contacts.csv";
$fp = fopen('php://output', 'w');
$header = array('Property ID','First Name','Last Name','Title','Email','Notes','Phone 1','Phone 2','Phone 3','Phone 4','Phone 5','Phone 6','Phone 7','Phone 8');
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);
fputcsv($fp, $header);

while($crow = mysql_fetch_assoc ($select_contact_query)){
	if($crow['property_id_str'] !='')
	{
		$csvRowArr = array();
		$csvRowArr['property_id'] = $crow['property_id_str']!='' ? $crow['property_id_str'] : '';
		$csvRowArr['firstname'] = $crow['firstname']!='' ? $crow['firstname'] : '';
		$csvRowArr['lastname'] = $crow['lastname']!='' ? $crow['lastname'] : '';
		$csvRowArr['title'] = $crow['title']!='' ? $crow['title'] : '';
		$csvRowArr['email'] = $crow['email']!='' ? $crow['email'] : '';
		$csvRowArr['notes'] = $crow['notes']!='' ? $crow['notes'] : '';

		$csvRowArr['phone1'] = ''; $csvRowArr['phone2'] = ''; $csvRowArr['phone3'] = ''; $csvRowArr['phone4'] = ''; $csvRowArr['phone5'] = ''; $csvRowArr['phone6'] = ''; $csvRowArr['phone7'] = ''; $csvRowArr['phone8'] = ''; 

		$typesql = "SELECT phone.*, types.name as typename FROM phone left join types on phone.type=types.id WHERE phone.contact_id=".$crow['id'];
		$tresult = $db->query($typesql) or die($typesql);
		if($db->getNumRows($tresult) > 0){
			$counttt = 1;
			while($trow=$db->fetchArray($tresult)){
				$phonenumbers = '';
				$phonenumbers .= CheckUSPhoneNum($trow['number']);
				if($trow['ext'] != '0')
					$phonenumbers.=' ext:'.$trow['ext'];
				if($trow['typename'] != '')
					$phonenumbers.=' ('.$trow['typename'].')';
				
				$csvRowArr['phone'.$counttt] = $phonenumbers;
				$counttt++;
			}
		}
		fputcsv($fp,$csvRowArr);
	}
}
exit;
