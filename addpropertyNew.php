<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
	
	if(isset($_REQUEST['hdnproperty']) && $_REQUEST['hdnproperty'] == '1'){
		$successfulSubmit = array();
		$create_date = strtotime(date("Y-m-d"));
		// Insert Property Information
		if($_REQUEST['txtproperty_id'] == '') { $txtproperty_id = 0; }
		if($_REQUEST['txtwebid'] == '') { $txtwebid = 0; }
		if($_REQUEST['txtotherpropertyid'] == '') { $txtotherpropertyid = 0; }
		if($_REQUEST['txtaddress'] == '') { $txtaddress = 0; }
		if($_REQUEST['txtaddress2'] == '') { $txtaddress2 = 0; }
		if($_REQUEST['txtcompanyname'] == '') { $txtcompanyname = 0; }
		if($_REQUEST['txtcounty'] == '') { $txtcounty = 0; }
		if($_REQUEST['txtaudit1'] == '') { $txtaudit1 = 0; }
		if($_REQUEST['txtstate'] == '') { $txtstate = 0; }
		if($_REQUEST['txtaudit2'] == '') { $txtaudit2 = 0; }
		if($_REQUEST['txtaudit3'] == '') { $txtaudit3 = 0; }
		if($_REQUEST['txttaxaudit'] == '') { $txttaxaudit = 0; }
		if($_REQUEST['txtzipcode'] == '') { $txtzipcode = 0; }
		if($_REQUEST['txtcity'] == '') { $txtcity = 0; }
		
		$sql = "INSERT INTO property (userid,property_id,company,webid,otherpropertyid,address,address2,state,zipcode,city,county,
				audit1,audit2,audit3,taxaudit,created_date) VALUES('".$_SESSION['userid']."','".addslashes($txtproperty_id)."','".addslashes($txtcompanyname)."','".addslashes($txtwebid)."','".$txtotherpropertyid."','".addslashes($txtaddress)."','".addslashes($txtaddress2)."','".addslashes($txtstate)."','".addslashes($txtzipcode)."',	'".addslashes($txtcity)."','".addslashes($txtcounty)."','".$txtaudit1."','".$txtaudit2."','".$txtaudit3."','".$txttaxaudit."','".$create_date."')";
		if($db->query($sql)) {
			array_push($successfulSubmit, "1");
		}else{
			array_push($successfulSubmit, "0");
			echo mysql_error();
		}
		
		$property_id = mysql_insert_id();
		
		//Add Contacts
		if(isset($_REQUEST['hdnaddcontact']) && $_REQUEST['hdnaddcontact'] == 1){
			$name = $_REQUEST['txtfname'];
			foreach($name as $ckey => $cvalue){
				/* if($_REQUEST['txtfname'][$ckey] !='' || $_REQUEST['txtlname'][$ckey] !='' || $_REQUEST['txttitle'][$ckey] !='' || 
				$_REQUEST['txtspousename'][$ckey] !='' || $_REQUEST['txtemail'][$ckey] !='' || $_REQUEST['txtwebsite'][$ckey] !='' 
				|| $_REQUEST['txtrelation'][$ckey] !='' || $_REQUEST['txtcontactnotes'][$ckey] !=''){	 */
					
					if($_REQUEST['txtfname'][$ckey] == '') { $txtfname[$ckey] = '0'; }
					if($_REQUEST['txtlname'][$ckey] == '') { $txtlname[$ckey] = '0'; }
					if($_REQUEST['txttitle'][$ckey] == '') { $txttitle[$ckey] = '0'; }
					if($_REQUEST['txtspousename'][$ckey] == '') { $txtspousename[$ckey] = '0'; }
					if($_REQUEST['txtemail'][$ckey] == '') { $txtemail[$ckey] = '0'; }
					if($_REQUEST['txtwebsite'][$ckey] == '') { $txtwebsite[$ckey] = '0'; }
					if($_REQUEST['txtrelation'][$ckey] == '') { $txtrelation[$ckey] = '0'; }
					if($_REQUEST['txtcontactnotes'][$ckey] == '') { $txtcontactnotes[$ckey] = '0'; }
					
					$create_date = strtotime(date("Y-m-d"));
					
					$sql = "INSERT INTO contact (userid,firstname,lastname,title,spousename,email,website,relationship,notes,create_date) VALUES(
					'".$_SESSION['userid']."','".addslashes($txtfname[$ckey])."','".addslashes($txtlname[$ckey])."',
					'".addslashes($txttitle[$ckey])."','".addslashes($txtspousename[$ckey])."','".addslashes($txtemail[$ckey])."',
					'".addslashes($txtwebsite[$ckey])."','".addslashes($txtrelation[$ckey])."','".addslashes($txtcontactnotes[$ckey])."','".$create_date."')";
					if($db->query($sql)){
						array_push($successfulSubmit, "1");
					}else{
						array_push($successfulSubmit, "0");
						echo mysql_error(); 
					}
								
					$contactid = mysql_insert_id();
					$sql = "INSERT INTO contact_to_property(contact_id,property_id) VALUES(".$contactid.",".addslashes($property_id).")";
					$db->query($sql) or die($sql);
					$phone = $_REQUEST['txtphone'.$ckey];
					foreach($phone as $key => $value){
						if($value != ''){
							
							//if($_REQUEST['txtphone'.$ckey][$key] == '') { $txtphone[$key] = '0'; }
							if($_REQUEST['txtext'.$ckey][$key] == '') { $txtext[$key] = '0'; }
							if($_REQUEST['txttype'.$ckey][$key] == '') { $txttype[$key] = '0'; }
							if($_REQUEST['txtpriority'.$ckey][$key] == '') { $txtpriority[$key] = '0'; }
							
							$sql = "INSERT INTO phone (contact_id,number,ext,type,priority) VALUES ('".$contactid."','".$_REQUEST['txtphone'.$ckey][$key]."','".$txtext[$key]."','".$txttype[$key]."','".$txtpriority[$ckey]."')";
							if($db->query($sql)){
								array_push($successfulSubmit, "1");
							}else{
								array_push($successfulSubmit, "0");
								echo mysql_error()." ".$sql;
							}
							
						}		
					}
				/* } */ 
			}
		}
		
		
		// Insert Location Information3
		if($_REQUEST['txtlicense'] != '' || $_REQUEST['txtsquarefeet'] != '' || $_REQUEST['txtlimitareas'] != ''
		 || $_REQUEST['txtneighbors'] != '' || $_REQUEST['txtanimals'] != '' || $_REQUEST['txtdatebuilt'] != ''
		  || $_REQUEST['txtpreviousfilming'] != '' || $_REQUEST['txtparking'] != '' || $_REQUEST['txtbedroom'] != ''
		   || $_REQUEST['txtrestroom'] != '' || $_REQUEST['txtreferalform'] != '' || $_REQUEST['txtadultfriendly'] != ''
		    || $_REQUEST['txtpool'] != '' || $_REQUEST['txttennisecourt'] != '' || $_REQUEST['txtcityview'] != ''
			|| $_REQUEST['txtnicebackyard'] != '' || $_REQUEST['txthomeassociation'] != ''
			 || $_REQUEST['txtarchitect'] != '' || $_REQUEST['txthousename'] != '' || $_REQUEST['txtnewpropertyowner'] != ''
			  || $_REQUEST['txtfilmingorevents'] != ''){
				$datebuilt = $_REQUEST['txtdatebuilt'];
				
				if($_REQUEST['txtlicense'] == '') { $txtlicense = '0'; }
				if($_REQUEST['txtsquarefeet'] == '') { $txtsquarefeet = '0'; }
				if($_REQUEST['txtlimitareas'] == '') { $txtlimitareas = '0'; }
				if($_REQUEST['txtneighbors'] == '') { $txtneighbors = '0'; }
				if($_REQUEST['txtanimals'] == '') { $txtanimals = '0'; }
				if($_REQUEST['txtpreviousfilming'] == '') { $txtpreviousfilming = '0'; }
				if($_REQUEST['txtparking'] == '') { $txtparking = '0'; }
				if($_REQUEST['txtbedroom'] == '') { $txtbedroom = '0'; }
				if($_REQUEST['txtrestroom'] == '') { $txtrestroom = '0'; }
				if($_REQUEST['txtreferalform'] == '') { $txtreferalform = '0'; }
				if($_REQUEST['txtadultfriendly'] == '') { $txtadultfriendly = '0'; }
				if($_REQUEST['txtpool'] == '') { $txtpool= '0'; }
				if($_REQUEST['txttennisecourt'] == '') { $txttennisecourt= '0'; }
				if($_REQUEST['txtcityview'] == '') { $txtcityview= '0'; }
				if($_REQUEST['txtnicebackyard'] == '') { $txtnicebackyard= '0'; }
				if($_REQUEST['txthomeassociation'] == '') { $txthomeassociation= '0'; }
				if($_REQUEST['txtarchitect'] == '') { $txtarchitect= '0'; }
				if($_REQUEST['txthousename'] == '') { $txthousename= '0'; }
				if($_REQUEST['txtnewpropertyowner'] == '') { $txtnewpropertyowner= '0'; }
				if($_REQUEST['txtfilmingorevents'] == '') { $txtfilmingorevents= '0'; }
				
				$sql = "INSERT INTO location (property_id,license,squarefit,limitareas,neighbors,animals,datebuilt,
				previousfilming,parking,bedroom,restroom,referalform,adultfriendly,pool,tennisecourt,cityview,nicebackyard,
				homeassociation,architect,housename,newpropertyowner,filmingorevents,create_date) VALUES(
			'".addslashes($property_id)."','".addslashes($txtlicense)."','".addslashes($txtsquarefeet)."','".addslashes($txtlimitareas)."',
			'".addslashes($txtneighbors)."','".addslashes($txtanimals)."','".addslashes($datebuilt)."','".addslashes($txtpreviousfilming)."',
			'".addslashes($txtparking)."','".addslashes($txtbedroom)."','".addslashes($txtrestroom)."','".addslashes($txtreferalform)."',
			'".addslashes($txtadultfriendly)."','".addslashes($txtpool)."','".addslashes($txttennisecourt)."',
			'".addslashes($txtcityview)."','".addslashes($txtnicebackyard)."','".addslashes($txthomeassociation)."',
			'".addslashes($txtarchitect)."','".addslashes($txthousename)."','".addslashes($txtnewpropertyowner)."',
			'".addslashes($txtfilmingorevents)."','".$create_date."')";
				if($db->query($sql)){
					array_push($successfulSubmit, "1");
				}else{
					array_push($successfulSubmit, "0");
					echo mysql_error();
				}
			if($_REQUEST['txtnotes'] != ''){
				if($_REQUEST['txtnotes'] == '') { $txtnotes= '0'; }
				$sql = "INSERT INTO notes (property_id,type,text) VALUES('".addslashes($property_id)."',1,'".addslashes($txtnotes)."')";
				if($db->query($sql)){
					array_push($successfulSubmit, "1");
				}else{
					array_push($successfulSubmit, "0");
					echo mysql_error();
				}
			}
					
		}
		// Insert Budget Information
		if($_REQUEST['txthighbudget'] != '' || $_REQUEST['txtmediumbudget'] != '' || $_REQUEST['txtlowbudget'] != ''
		 || $_REQUEST['txtbudgetnotes'] != '' || $_REQUEST['txtfilmfriendly'] != '' || $_REQUEST['txtinteriorfilming'] != ''
		  || $_REQUEST['txtexteriorfilming'] != '' || $_REQUEST['txtfilmbudget'] != '' || $_REQUEST['txtfilmmaxheat'] != ''
		   || $_REQUEST['txtfilmparking'] != '' || $_REQUEST['txteventfriendly'] != ''
		    || $_REQUEST['txtinteriorevent'] != '' || $_REQUEST['txtexteriorevent'] != '' || $_REQUEST['txteventbudget'] != ''
			 || $_REQUEST['txteventmaxheat'] != '' || $_REQUEST['txteventparking'] != ''){
			 
				if($_REQUEST['txthighbudget'] == '') { $txthighbudget = '0'; }
				if($_REQUEST['txtmediumbudget'] == '') { $txtmediumbudget = '0'; }
				if($_REQUEST['txtlowbudget'] == '') { $txtlowbudget = '0'; }
				if($_REQUEST['txtbudgetnotes'] == '') { $txtbudgetnotes = '0'; }
				if($_REQUEST['txtfilmfriendly'] == '') { $txtfilmfriendly = '0'; }
				if($_REQUEST['txtinteriorfilming'] == '') { $txtinteriorfilming = '0'; }
				if($_REQUEST['txtexteriorfilming'] == '') { $txtexteriorfilming = '0'; }
				if($_REQUEST['txtfilmbudget'] == '') { $txtfilmbudget = '0'; }
				if($_REQUEST['txtfilmmaxheat'] == '') { $txtfilmmaxheat = '0'; }
				if($_REQUEST['txtfilmparking'] == '') {  $txtfilmparking = '0'; }
				if($_REQUEST['txteventfriendly'] == '') { $txteventfriendly = '0'; }
				if($_REQUEST['txtinteriorevent'] == '') { $txtinteriorevent = '0'; }
				if($_REQUEST['txtexteriorevent'] == '') { $txtexteriorevent = '0'; }
				if($_REQUEST['txteventbudget'] == '') {  $txteventbudget = '0'; }
				if($_REQUEST['txteventmaxheat'] == '') { $txteventmaxheat = '0'; }
				if($_REQUEST['txteventparking'] == '') { $txteventparking = '0'; }
				
				$sql = "INSERT INTO budget (property_id,highbudget,mediumbudget,lowbudget,budgetnotes,filmfriendly,interiorfilming,
				exteriorfilming,filmbudget,filmmaxheat,filmparking,eventfriendly,interiorevent,exteriorevent,eventbudget,
				eventmaxheat,eventparking,create_date) VALUES(
			'".addslashes($property_id)."','".addslashes($txthighbudget)."','".addslashes($txtmediumbudget)."','".addslashes($txtlowbudget)."',
			'".addslashes($txtbudgetnotes)."','".addslashes($txtfilmfriendly)."','".addslashes($txtinteriorfilming)."','".addslashes($txtexteriorfilming)."','".addslashes($txtfilmbudget)."','".addslashes($txtfilmmaxheat)."','".addslashes($txtfilmparking)."','".addslashes($txteventfriendly)."','".addslashes($txtinteriorevent)."','".addslashes($txtexteriorevent)."','".addslashes($txteventbudget)."','".addslashes($txteventmaxheat)."','".addslashes($txteventparking)."','".addslashes($create_date)."')";
				if($db->query($sql)){
					array_push($successfulSubmit, "1");
				}else{
					array_push($successfulSubmit, "0");
					echo mysql_error();
				}
			if($_REQUEST['txtfilmnotes'] != ''){
				$sql = "INSERT INTO notes (property_id,type,text,userid) VALUES('".addslashes($property_id)."',2,'".addslashes($_REQUEST['txtfilmnotes'])."','".$_SESSION['userid']."')";
				if($db->query($sql)){
					array_push($successfulSubmit, "1");
				}else{
					echo mysql_error();
					array_push($successfulSubmit, "0");
				}
			}
			if($_REQUEST['txteventnotes'] != ''){
				$sql = "INSERT INTO notes (property_id,type,text,userid) VALUES('".addslashes($property_id)."',3,'".addslashes($_REQUEST['txteventnotes'])."', '".$_SESSION['userid']."')";
				if($db->query($sql)){
					array_push($successfulSubmit, "1");
				}else{
					array_push($successfulSubmit, "0");
					echo mysql_error();
				}
			}
		}
		// Insert Tax Information
		if($_REQUEST['txttaxcompany'] != '' || $_REQUEST['txttaxfirstname'] != '' || $_REQUEST['txttaxlastname'] != ''
		 || $_REQUEST['txttaxaddress'] != '' || $_REQUEST['txttaxaddress2'] != '' || $_REQUEST['txttaxcity'] != ''
		  || $_REQUEST['txttaxstate'] != '' || $_REQUEST['txttaxzipcode'] != '' || $_REQUEST['txttaxamount'] != ''
		   || $_REQUEST['txttaxnotes1'] != '' || $_REQUEST['txttaxnotes2'] != '' || $_REQUEST['txtmailtaxes'] != ''
		    || $_REQUEST['txtquestionable'] != ''){
			
			if($_REQUEST['txttaxcompany'] == '') { $txttaxcompany = '0'; }
			if($_REQUEST['txttaxfirstname'] == '') { $txttaxfirstname = '0'; }
			if($_REQUEST['txttaxlastname'] == '') { $txttaxlastname = '0'; }
			if($_REQUEST['txttaxaddress'] == '') { $txttaxaddress = '0'; }
			if($_REQUEST['txttaxaddress2'] == '') { $txttaxaddress2 = '0'; }
			if($_REQUEST['txttaxcity'] == '') { $txttaxcity = '0'; }
			if($_REQUEST['txttaxstate'] == '') { $txttaxstate = '0'; }
			if($_REQUEST['txttaxzipcode'] == '') { $txttaxzipcode = '0'; }
			if($_REQUEST['txttaxamount'] == '') { $txttaxamount = '0'; }
			if($_REQUEST['txttaxnotes1'] == '') { $txttaxnotes1 = '0'; }
			if($_REQUEST['txttaxnotes2'] == '') { $txttaxnotes2 = '0'; }
			if($_REQUEST['txtmailtaxes'] == '') { $txtmailtaxes = '0'; }
			if($_REQUEST['txtquestionable'] == '') { $txtquestionable = '0'; }
			
			$sql = "INSERT INTO tax (property_id,company,firstname,lastname,address,address2,city,state,zipcode,amount,notes1,notes2,
				mailtaxes,questionable,create_date) VALUES(
			'".$property_id."','".addslashes($txttaxcompany)."','".$txttaxfirstname."','".$txttaxlastname."',
			'".addslashes($txttaxaddress)."','".addslashes($txttaxaddress2)."','".addslashes($txttaxcity)."',
			'".$txttaxstate."','".$txttaxzipcode."','".$txttaxamount."','".addslashes($txttaxnotes1)."','".addslashes($txttaxnotes2)."','".addslashes($txtmailtaxes)."','".addslashes($txtquestionable)."','".$create_date."')";
				if($db->query($sql)){
					array_push($successfulSubmit, "1");
				}else{
					array_push($successfulSubmit, "0");
					echo mysql_error();
				}
		}   
		$val = 1;
		for ($i = 0; $i < count($successfulSubmit); $i++){
			if($successfulSubmit[$i] == '0'){
				$val = 0;
			}
		}
		
		if($val == 0){
			header("Location: property.php?id=".$property_id."&success=0");
		}else{
			header("Location: property.php?id=".$property_id."&success=1");
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CRM</title>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="js/calendar.js"></script>
<script>
<!--
function openwindow(){
	window.open('selectcontact.php','contactwin','height=300,width=800');
}
function addContact(){ 
	if(document.getElementById("addContact").style.display == 'none')
		document.getElementById("addContact").style.display = 'block';
	else
		document.getElementById("addContact").style.display = 'none';
}
function addContactInfo(){
	if(document.getElementById("addcontactinfo").style.display == 'none'){
		document.getElementById("addcontactinfo").style.display = 'block';
		document.getElementById("addContact").style.display = 'none';
	}
	else{
		document.getElementById("addcontactinfo").style.display = 'none';
		document.getElementById("addContact").style.display = 'block';
	}
}
function addLocation(){ 
	if(document.getElementById("addLocation").style.display == 'none')
		document.getElementById("addLocation").style.display = 'block';
	else
		document.getElementById("addLocation").style.display = 'none';
}
function addBudget(){ 
	if(document.getElementById("addBudget").style.display == 'none')
		document.getElementById("addBudget").style.display = 'block';
	else
		document.getElementById("addBudget").style.display = 'none';
}
function addTax(){ 
	if(document.getElementById("addTax").style.display == 'none')
		document.getElementById("addTax").style.display = 'block';
	else
		document.getElementById("addTax").style.display = 'none';
}
function addPhonetypes(type_number){
	hdnphone = document.getElementById("hdnaddphonetype"+type_number).value;
	newhdnphone = hdnphone;
	newhtml = '<table width="99%" border="0" cellpadding="0" cellspacing="0"><tr><td width="33%" align="left" valign="top" class="formtxt">Phone:</td><td width="29%" align="left" valign="top" class="formtxt">Ext:</td><td width="20%" align="left" valign="top" class="formtxt">Type:</td><td width="18%" align="left" valign="top" class="formtxt">Priority:</td></tr><tr><td align="left" valign="top" width="33%"><input name="txtphone'+type_number+'[]" type="text" class="form" id="txtphone'+type_number+'[]" size="35" maxlength="50" /></td><td align="left" valign="top" width="29%"><input name="txtext'+type_number+'[]" type="text" class="form" id="txtext'+type_number+'[]" size="20" maxlength="10" /></td><td align="left" valign="top" width="20%"><select name="txttype'+type_number+'[]" class="dropdown"><?=fillCombo('txttype[]','types','id','name','WHERE active=1','','dropdown','','1')?></select></td><td align="left" valign="top" width="18%"><select name="txtpriority'+type_number+'[]"><?=listbox_array($priorityArray,'txtpriority[]','','1')?></select></td></tr></table>';
	if(document.getElementById("phonetype"+type_number+hdnphone)){
		newhdnphone++;
		document.getElementById("phonetype"+type_number+hdnphone).style.display = 'block';
		document.getElementById("phonetype"+type_number+hdnphone).innerHTML = newhtml +'<div id="phonetype'+type_number+newhdnphone+'" style="display:none"></div>';
	}
	document.getElementById("hdnaddphonetype"+type_number).value = newhdnphone;
}
function verifyaddproperty(){

	return true;
}
function addmorecontact(){
	hdncontact = document.getElementById("hdnmorecontact").value;
	newhdncontact = hdncontact;
	newhtml = '<table width="100%" border="0" cellspacing="0" cellpadding="1">';
    newhtml +=     '<tr>';
    newhtml +=     '<td width="29%" align="left" valign="top"><span class="formtxt">First Name:</span></td>';
    newhtml +=     '<td width="23%" align="left" valign="top">&nbsp;</td>';
    newhtml +=     '<td width="48%" align="left" valign="top"><span class="formtxt">Last Name:</span></td>';
    newhtml +=     '</tr>';
    newhtml +=     '<tr>';
    newhtml +=     '<td align="left" valign="top"><input name="txtfname[]" id="txtfname[]" type="text" class="form" size="35" maxlength="255" /></td>';
    newhtml +=     '<td align="left" valign="top">&nbsp;</td>';
    newhtml +=     '<td align="left" valign="top"><input name="txtlname[]" id="txtlname[]" type="text" class="form" size="35" maxlength="255" /></td>';
    newhtml +=      '</tr>';
    newhtml +=     '<tr>';
    newhtml +=     '<td align="left" valign="top"><span class="formtxt">Title:</span></td>';
    newhtml +=     '<td align="left" valign="top">&nbsp;</td>';
    newhtml +=     '<td align="left" valign="top"><span class="formtxt">Spouse Name: </span></td>';
    newhtml +=     '</tr>';
    newhtml +=     '<tr>';
    newhtml +=     '<td align="left" valign="top"><input name="txttitle[]" id="txttitle[]" type="text" class="form" value="" size="35" maxlength="255"/></td>';
    newhtml +=     '<td align="left" valign="top">&nbsp;</td>';
    newhtml +=     '<td align="left" valign="top"><input name="txtspousename[]" type="text" class="form" id="txtspousename[]" ize="35" maxlength="255" /></td>';
    newhtml +=     '</tr>';
    newhtml +=     '<tr>';
    newhtml +=     '<td align="left" valign="top"><span class="formtxt">Email:</span></td>';
    newhtml +=     '<td align="left" valign="top">&nbsp;</td>';
    newhtml +=     '<td align="left" valign="top"><span class="formtxt">Website:</span></td>';
    newhtml +=     '</tr>';
    newhtml +=     '<tr>';
    newhtml +=     '<td align="left" valign="top"><input name="txtemail[]" type="text" class="form" id="txtemail[]" size="35" maxlength="255" /></td>';
    newhtml +=     '<td align="left" valign="top">&nbsp;</td>';
    newhtml +=     '<td align="left" valign="top"><input name="txtwebsite[]" id="txtwebsite[]" type="text" class="form" size="35" maxlength="255" /></td>';
    newhtml +=     '</tr>';
    newhtml +=     '<tr>';
    newhtml +=     '<td align="left" valign="top"><span class="formtxt">Relationship:</span></td>';
    newhtml +=     '<td align="left" valign="top" class="formtxt">&nbsp;</td>';
    newhtml +=     '<td align="left" valign="top">&nbsp;</td>';
    newhtml +=     '</tr>';
    newhtml +=     '<tr>';
    newhtml +=     '<td align="left" valign="top"><?=listbox_array($relationshipArray,'txtrelation[]')?></td>';
    newhtml +=     '<td align="left" valign="top">&nbsp;</td>';
    newhtml +=     '<td align="left" valign="top">&nbsp;</td>';
    newhtml +=     '</tr>';
    newhtml +=     '<tr>';
    newhtml +=     '<td align="left" valign="top"><span class="formtxt">Note:</span></td>';
    newhtml +=     '<td align="left" valign="top">&nbsp;</td>';
    newhtml +=     '<td align="left" valign="top">&nbsp;</td>';
    newhtml +=     '</tr>';
    newhtml +=     '<tr>';
    newhtml +=     '<td colspan="3" align="left" valign="top"><textarea name="txtcontactnotes[]" id="txtcontactnotes[]" cols="40" rows="4" class="txtarea"></textarea>                  </td>';
    newhtml +=     '</tr>';
    newhtml +=     '<tr>';
    newhtml +=     '<td align="left" valign="top">&nbsp;</td>';
    newhtml +=     '<td align="left" valign="top">&nbsp;</td>';
    newhtml +=     '<td align="left" valign="top">&nbsp;</td>';
    newhtml +=     '</tr>';
    newhtml +=     '<tr>';
    newhtml +=     '<td colspan="3" align="left" valign="top"><div id="phonetype">';
    newhtml +=     '<table width="99%" border="0" cellpadding="0" cellspacing="0">';
    newhtml +=     '<tr>';
    newhtml +=     '<td width="33%" align="left" valign="top" class="formtxt">Phone:</td>';
    newhtml +=     '<td width="29%" align="left" valign="top" class="formtxt">Ext:</td>';
    newhtml +=     '<td width="20%" align="left" valign="top" class="formtxt">Type:</td>';
    newhtml +=     '<td width="18%" align="left" valign="top" class="formtxt">Priority:</td>';
    newhtml +=     '</tr>';
    newhtml +=     '<tr>';
    newhtml +=     '<td align="left" valign="top"><input name="txtphone'+hdncontact+'[]" type="text" class="form" id="txtphone'+hdncontact+'[]" size="35" maxlength="50" /></td>';
    newhtml +=     '<td align="left" valign="top"><input name="txtext'+hdncontact+'[]" type="text" class="form" id="txtext'+hdncontact+'[]" size="20" maxlength="10" /></td>';
    newhtml +=     '<td align="left" valign="top"><select name="txttype'+hdncontact+'[]" classs="dropdown"><?=fillCombo('txttype[]','types','id','name','WHERE active=1','','dropdown','','1')?></td></select>';
    newhtml +=     '<td align="left" valign="top"><select name="txtpriority'+hdncontact+'[]"><?=listbox_array($priorityArray,'txtpriority[]','','1')?></select></td>';
    newhtml +=     '</tr>';
    newhtml +=     '</table>';
    newhtml +=     '</div>';
    newhtml +=     '<input name="hdnaddphonetype'+hdncontact+'" id="hdnaddphonetype'+hdncontact+'" type="hidden" value="1" />';
    newhtml +=     '<div id="phonetype'+hdncontact+'1" style="display:none"></div></td>';
    newhtml +=     '</tr>';
    newhtml +=     '<tr>';
    newhtml +=     '<td height="35" align="left" valign="top">&nbsp;</td>';
    newhtml +=     '<td height="35" align="left" valign="top">&nbsp;</td>';
    newhtml +=     '<td height="35" align="right" valign="middle"><input name="addcontactinfo" id="addcontactinfo" type="hidden" value="1" /><input name="Button5" type="button" class="button" value="Add More" onclick="addPhonetypes('+hdncontact+');"/></td>';
    newhtml +=     '</tr>';
    newhtml +=     '<tr>';
    newhtml +=     '<td colspan="3" align="left" valign="top"><table width="60%" border="0" cellspacing="0" cellpadding="0">';
    newhtml +=     '<tr>';
    newhtml +=     '<td width="7%" align="left" valign="top">&nbsp;</td>';
    newhtml +=     '<td width="18%" align="left" valign="top"><span class="dataField"><span class="dataLabel" style="display: inline;">';
    //newhtml +=     '<input name="hdnaddcontact" type="hidden" value="1" />';
    newhtml +=     '</span></span></td>';
    newhtml +=     '<td width="68%" align="left" valign="top"><div id="addmbtn0"><input name="Button52" type="button" class="button" value="Add More Contact" onclick="addmorecontact();"/></div></td>';
    newhtml +=     '<td width="7%" align="left" valign="top">&nbsp;</td>';
    newhtml +=     '</tr>';
    newhtml +=     '</table></td>';
    newhtml +=     '</tr>';
    newhtml +=  '</table>';
	if(document.getElementById("morecontact"+hdncontact)){
		newhdncontact++;
		document.getElementById("morecontact"+hdncontact).style.display = 'block';
		document.getElementById("morecontact"+hdncontact).innerHTML = newhtml +'<div id="morecontact'+newhdncontact+'" style="display:none"></div>';
	}
	document.getElementById("hdnmorecontact").value = newhdncontact;
}
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
</head>

<body>
<? include("header.php"); ?>
<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="4" align="left" valign="top" nowrap="nowrap"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
        <tbody>
          <tr>
            <td width="100%" align="left" valign="top" class="lastview"><b style="white-space: nowrap;">Create Contact </b> </td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td align="left" valign="top" class="mar"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="4" align="left" valign="top" nowrap="nowrap"><form id="form1" name="form1" method="post" action="" onsubmit="return verifyaddproperty();">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">

          <tr>
            <td width="31%" align="left" valign="top" class="formtxt">Property ID: </td>
            <td width="21%" align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><span class="formtxt">Address:</span></td>
          </tr>
          <tr>
            <td align="left" valign="top"><input name="txtproperty_id" id="txtproperty_id" type="text" class="form" size="35" maxlength="255" /></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><input name="txtaddress" id="txtaddress" type="text" class="form" size="35" maxlength="255"/></td>
          </tr>
          <tr>
            <td align="left" valign="top"><span class="formtxt">Company Name:</span></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><span class="formtxt">Address2: </span></td>
          </tr>
          <tr>
            <td align="left" valign="top"><input name="txtcompanyname" id="txtcompanyname" type="text" class="form" size="35" maxlength="255" /></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><input name="txtaddress2" type="text" class="form" id="txtaddress2" size="35" maxlength="255" iid="txtspousename" ize="35" /></td>
          </tr>
          <tr>
            <td align="left" valign="top"><span class="formtxt">Web ID:</span></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><span class="formtxt">City:</span></td>
          </tr>
          <tr>
            <td align="left" valign="top"><input name="txtwebid" type="text" class="form" id="txtwebid" size="35" maxlength="255" iid="txtspousename" ize="35" /></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><input name="txtcity" id="txtcity" type="text" class="form" size="35" maxlength="255"/></td>
          </tr>
          <tr>
            <td align="left" valign="top"><span class="formtxt">Other Property ID:</span></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top" class="formtxt">State:</td>
          </tr>
          <tr>
            <td align="left" valign="top"><input name="txtotherpropertyid" type="text" class="form" id="txtotherpropertyid" size="35" maxlength="255" iid="txtspousename" ize="35" /></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><?=fillCombo('txtstate','state','id','name','WHERE active=1','','dropdown','')?></td>
          </tr>
          <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top" class="formtxt">Zipcode:</td>
          </tr>
          <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><input name="txtzipcode" id="txtzipcode" type="text" class="form" size="35" maxlength="255" /></td>
          </tr>
          <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top" class="formtxt">County:</td>
          </tr>
          <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><input type="hidden" id="hidCounty" />&nbsp;</td>
            <td align="left" valign="top"><?=fillCombo('txtcounty','county','id','name','WHERE active=1','','dropdown','')?></td>
          </tr>
          <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr align="left">
            <td colspan="3" valign="middle" class="formtxt">&nbsp;</td>
          </tr>
          <tr align="left" style="display:none">
            <td colspan="3" valign="middle" class="formtxt"><label>
              <input name="txtaudit1" type="checkbox" id="txtaudit1" value="1" />
              Audit1
              <input name="txtaudit2" type="checkbox" id="txtaudit2" value="1" />
              Audit2
              <input name="txtaudit3" type="checkbox" id="txtaudit3" value="1" />
              Audit3
              <input name="txttaxaudit" type="checkbox" id="txttaxaudit" value="1" />
              Tax Audit</label></td>
            </tr>
          <tr align="left">
            <td valign="middle"><input name="Button4" type="button" class="h" value="Add Contact Information" onclick="addContactInfo();" style="height:25px"/></td>
            <td valign="top">&nbsp;</td>
            <td valign="middle">&nbsp;</td>
          </tr>
		  <tr>
            <td colspan="3" align="left" valign="top"><div id="addcontactinfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="1">
                <tr>
                  <td width="29%" align="left" valign="top"><span class="formtxt">First Name:</span></td>
                  <td width="23%" align="left" valign="top">&nbsp;</td>
                  <td width="48%" align="left" valign="top"><span class="formtxt">Last Name:</span></td>
                </tr>
                <tr>
                  <td align="left" valign="top"><input name="txtfname[]" id="txtfname[]" type="text" class="form" size="35" maxlength="255" /></td>
                  <td align="left" valign="top">&nbsp;</td>
                  <td align="left" valign="top"><input name="txtlname[]" id="txtlname[]" type="text" class="form" size="35" maxlength="255" /></td>
                </tr>
                <tr>
                  <td align="left" valign="top"><span class="formtxt">Title:</span></td>
                  <td align="left" valign="top">&nbsp;</td>
                  <td align="left" valign="top"><span class="formtxt">Spouse Name: </span></td>
                </tr>
                <tr>
                  <td align="left" valign="top"><input name="txttitle[]" id="txttitle[]" type="text" class="form" value="" size="35" maxlength="255"/></td>
                  <td align="left" valign="top">&nbsp;</td>
                  <td align="left" valign="top"><input name="txtspousename[]" type="text" class="form" id="txtspousename[]" ize="35" maxlength="255" /></td>
                </tr>
                <tr>
                  <td align="left" valign="top"><span class="formtxt">Email:</span></td>
                  <td align="left" valign="top">&nbsp;</td>
                  <td align="left" valign="top"><span class="formtxt">Website:</span></td>
                </tr>
                <tr>
                  <td align="left" valign="top"><input name="txtemail[]" type="text" class="form" id="txtemail[]" size="35" maxlength="255" /></td>
                  <td align="left" valign="top">&nbsp;</td>
                  <td align="left" valign="top"><input name="txtwebsite[]" id="txtwebsite[]" type="text" class="form" size="35" maxlength="255" /></td>
                </tr>
                <tr>
                  <td align="left" valign="top"><span class="formtxt">Relationship:</span></td>
                  <td align="left" valign="top" class="formtxt">&nbsp;</td>
                  <td align="left" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" valign="top"><?=listbox_array($relationshipArray,'txtrelation[]')?></td>
                  <td align="left" valign="top">&nbsp;</td>
                  <td align="left" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" valign="top"><span class="formtxt">Note:</span></td>
                  <td align="left" valign="top">&nbsp;</td>
                  <td align="left" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="3" align="left" valign="top"><textarea name="txtcontactnotes[]" id="txtcontactnotes[]" cols="40" rows="4" class="txtarea"></textarea>                  </td>
                  </tr>
                <tr>
                  <td align="left" valign="top">&nbsp;</td>
                  <td align="left" valign="top">&nbsp;</td>
                  <td align="left" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="3" align="left" valign="top"><div id="phonetype">
                      <table width="99%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="33%" align="left" valign="top" class="formtxt">Phone:</td>
                          <td width="29%" align="left" valign="top" class="formtxt">Ext:</td>
                          <td width="20%" align="left" valign="top" class="formtxt">Type:</td>
                          <td width="18%" align="left" valign="top" class="formtxt">Priority:</td>
                        </tr>
                        <tr>
                          <td align="left" valign="top"><input name="txtphone0[]" type="text" class="form" id="txtphone0[]" size="35" maxlength="50" /></td>
                          <td align="left" valign="top"><input name="txtext0[]" type="text" class="form" id="txtext0[]" size="20" maxlength="10" /></td>
                          <td align="left" valign="top"><?=fillCombo('txttype0[]','types','id','name','WHERE active=1','','dropdown','')?></td>
                          <td align="left" valign="top"><?=listbox_array($priorityArray,'txtpriority0[]')?>                          </td>
                        </tr>
                      </table>
                  </div>
                      <input name="hdnaddphonetype0" id="hdnaddphonetype0" type="hidden" value="1" />
                      <div id="phonetype01" style="display:none"></div></td>
                </tr>
                <tr>
                  <td height="35" align="left" valign="top">&nbsp;</td>
                  <td height="35" align="left" valign="top">&nbsp;</td>
                  <td height="35" align="right" valign="middle"><input name="addcontactinfo" id="addcontactinfo" type="hidden" value="1" /><input name="Button5" type="button" class="button" value="Add More" onclick="addPhonetypes(0);"/></td>
                </tr>
                <tr>
                  <td colspan="3" align="left" valign="top"><table width="60%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="7%" align="left" valign="top">&nbsp;</td>
                        <td width="18%" align="left" valign="top"><span class="dataField"><span class="dataLabel" style="display: inline;">
                          <input name="hdnaddcontact" type="hidden" value="1" />
                        </span></span></td>
                        <td width="68%" align="left" valign="top"><div id="addmbtn0"><input name="Button52" type="button" class="button" value="Add More Contact" onclick="addmorecontact();"/></div></td>
                        <td width="7%" align="left" valign="top">&nbsp;</td>
                      </tr>
                  </table></td>
                </tr>
              </table>
            </div>
			<input name="hdnmorecontact" id="hdnmorecontact" type="hidden" value="1" />
            <div id="morecontact1" style="display:none"></div>
			</td>
		  </tr>
          <!--<tr align="left">
            <td colspan="3" valign="middle"><div id="addContact" style="display: none"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="29%" align="left">
                  <div id="showcontacts" style="float:left">
                    <input name="txtcontacts[]" type="text" class="formtxt" onfocus="this.value='';" onkeyup="ajax_showOptions(this,'name',event);" value="Type Contact Name Here To Select" size="35"/>
                  </div>                </td>
                <td width="1%" align="left">&nbsp;</td>
                <td width="3%" align="left"><span class="bodttxt">OR</span></td>
                <td width="67%" align="left"><input name="selectcontacts" type="button" value="Click to Select Contacts" onclick="window.open('selectcontact.php','contactwin','height=580,width=800,scrollbars=1');" class="h" style="height:25px"/></td>
              </tr>
            </table>
            </div></td>
          </tr>-->
          <tr align="left">
            <td height="35" valign="middle"><input name="Button" type="button" class="h" value="Add Location Information" onclick="addLocation();" style="height:25px"/></td>
            <td height="35" valign="top">&nbsp;</td>
            <td height="35" valign="middle">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" align="left" valign="top"><div id="addLocation" style="display:none">
              <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formtxt">
                <tr>
                  <td align="right">License:</td>
                  <td><input name="txtlicense" type="checkbox" class="formtxt" id="txtlicense" value="1" /></td>
                  <td align="right">Sq Feet: </td>
                  <td><input name="txtsquarefeet" type="text" class="form" id="txtsquarefeet" maxlength="255" /></td>
                  <td align="right">Areas off limit: </td>
                  <td><input name="txtlimitareas" type="text" class="form" id="txtlimitareas" maxlength="255" /></td>
                </tr>
                <tr>
                  <td align="right">Neighbors:</td>
                  <td><input name="txtneighbors" type="text" class="form" id="txtneighbors" maxlength="255" /></td>
                  <td align="right">Animals:</td>
                  <td><input name="txtanimals" type="text" class="form" id="txtanimals" maxlength="255" /></td>
                  <td align="right">Date Built: </td>
                  <td><input name="txtdatebuilt" type="text" class="form" id="txtdatebuilt" onclick="javascript:cal.popup();" /></td>
                </tr>
                <tr>
                  <td align="right">Previous Filming:</td>
                  <td><input name="txtpreviousfilming" type="text" class="form" id="txtpreviousfilming" maxlength="255" /></td>
                  <td align="right">Parking:</td>
                  <td><input name="txtparking" type="text" class="form" id="txtparking" maxlength="255" /></td>
                  <td align="right">Bedroom Count: </td>
                  <td><input name="txtbedroom" type="text" class="form" id="txtbedroom" maxlength="255" /></td>
                </tr>
                <tr>
                  <td align="right">Restroom Count: </td>
                  <td><input name="txtrestroom" type="text" class="form" id="txtrestroom" maxlength="255" /></td>
                  <td align="right">Referal Form: </td>
                  <td><textarea name="txtreferalform" cols="20" rows="2" class="txtarea" id="txtreferalform"></textarea></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="right">Features:</td>
                  <td colspan="5"><label>
                    <input name="txtadultfriendly" type="checkbox" id="txtadultfriendly" value="1" />
                    Adult Friendly
                    <input name="txtpool" type="checkbox" id="txtpool" value="1" />
                    Pool
                    <input name="txttennisecourt" type="checkbox" id="txttennisecourt" value="1" />
                    Tennis Court
                    <input name="txtcityview" type="checkbox" id="txtcityview" value="1" />
                    City View
                    <input name="txtnicebackyard" type="checkbox" id="txtnicebackyard" value="1" />
                    Nice Backyard
                    <input name="txthomeassociation" type="checkbox" id="txthomeassociation" value="1" />
                    Home Association</label></td>
                  </tr>
                <tr>
                  <td align="right">Architect:</td>
                  <td><input name="txtarchitect" type="text" class="form" id="txtarchitect" maxlength="255" /></td>
                  <td align="right">House Name: </td>
                  <td><input name="txthousename" type="text" class="form" id="txthousename" maxlength="255" /></td>
                  <td colspan="2">&nbsp;</td>
                  </tr>
                <tr>
                  <td align="right">&nbsp;</td>
                  <td colspan="5"><label>
                    <input name="txtnewpropertyowner" type="checkbox" id="txtnewpropertyowner" value="1" />
                    Find New Property Owner
                    <input name="txtfilmingorevents" type="checkbox" id="txtfilmingorevents" value="1" />
                    No Longer For Filming or Events</label></td>
                  </tr>
                <tr>
                  <td align="right">Notes:</td>
                  <td colspan="5"><textarea name="txtnotes" cols="45" rows="4" class="txtarea" id="txtnotes"></textarea></td>
                  </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
            </div></td>
            </tr>
          <tr>
            <td height="35" align="left" valign="middle"><input name="Button2" type="button" class="h" value="Add Budget Information" onclick="addBudget();" style="height:25px"/></td>
            <td height="35" align="left" valign="top">&nbsp;</td>
            <td height="35" align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" align="left" valign="top"><div id="addBudget" style="display:none">
              <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formtxt">
                <!--<tr>
                  <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="10%">&nbsp;</td>
                      <td width="90%"><label>
                        <input name="txthighbudget" type="checkbox" id="txthighbudget" value="1" />
                        High Budget
                        <input name="txtmediumbudget" type="checkbox" id="txtmediumbudget" value="1" />
                        Mid Budget
                        <input name="txtlowbudget" type="checkbox" id="txtlowbudget" value="1" />
                        Low Budget</label></td>
                    </tr>
                  </table></td>
                  </tr>-->
                <tr>
                  <td width="7%" align="right">Budget Notes :</td>
                  <td colspan="5"><textarea name="txtbudgetnotes" cols="45" rows="4" class="txtarea" id="txtbudgetnotes"></textarea></td>
                  </tr>
                <tr>
                  <td colspan="6"><strong>Film Information </strong></td>
                  </tr>
                <tr>
                  <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="10%">&nbsp;</td>
                      <td width="90%"><label>
                        <input name="txtfilmfriendly" type="checkbox" id="txtfilmfriendly" value="1" />
                        Film Friendly
                        <input name="txtinteriorfilming" type="checkbox" id="txtinteriorfilming" value="1" />
                        Interior Filming
                        <input name="txtexteriorfilming" type="checkbox" id="txtexteriorfilming" value="1" />
                        Exterior Filming </label></td>
                    </tr>
                  </table></td>
                  </tr>

                <tr>
                  <td align="right">Avg. Film Budget :</td>
                  <td width="22%"><input name="txtfilmbudget" type="text" class="form" id="txtfilmbudget" maxlength="255" /></td>
                  <td width="14%" align="right">Max Head Count:</td>
                  <td width="22%"><input name="txtfilmmaxheat" type="text" class="form" id="txtfilmmaxheat" maxlength="255" /></td>
                  <td width="11%" align="right">Film Parking: </td>
                  <td width="24%"><input name="txtfilmparking" type="text" class="form" id="txtfilmparking" maxlength="255" /></td>
                </tr>
                <tr>
                  <td align="right">Film Notes:</td>
                  <td colspan="5"><textarea name="txtfilmnotes" cols="45" rows="6" class="txtarea" id="txtfilmnotes"></textarea></td>
                  </tr>
                <tr>
                  <td colspan="6"><strong>Events Information </strong></td>
                </tr>
                <tr>
                  <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="10%">&nbsp;</td>
                      <td width="90%"><label>
                        <input name="txteventfriendly" type="checkbox" id="txteventfriendly" value="1" />
                        Event Friendly
                        <input name="txtinteriorevent" type="checkbox" id="txtinteriorevent" value="1" />
                        Interior Events
                        <input name="txtexteriorevent" type="checkbox" id="txtexteriorevent" value="1" />
                        Exterior Events </label></td>
                    </tr>
                  </table></td>
                  </tr>
                <tr>
                  <td align="right">Avg. Event Budget :</td>
                  <td><input name="txteventbudget" type="text" class="form" id="txteventbudget" maxlength="255" /></td>
                  <td align="right">Max Head Count:</td>
                  <td><input name="txteventmaxheat" type="text" class="form" id="txteventmaxheat" maxlength="255" /></td>
                  <td align="right">Event Parking: </td>
                  <td><input name="txteventparking" type="text" class="form" id="txteventparking" maxlength="255" /></td>
                </tr>
                <tr>
                  <td align="right">Event Notes:</td>
                  <td colspan="5"><textarea name="txteventnotes" cols="45" rows="6" class="txtarea" id="txteventnotes"></textarea></td>
                  </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
            </div></td>
            </tr>
          <tr>
            <td height="35" align="left" valign="middle"><input name="Button3" type="button" class="h" value="Add Tax Information" onclick="addTax();" style="height:25px"/></td>
            <td height="35" align="left" valign="top">&nbsp;</td>
            <td height="35" align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" align="left" valign="top"><div id="addTax" style="display:none">
              <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formtxt">
                <tr>
                  <td align="right">Tax Company:</td>
                  <td><input name="txttaxcompany" type="text" class="form" id="txttaxcompany" maxlength="255" /></td>
                  <td align="right">Taxt First Name: </td>
                  <td><input name="txttaxfirstname" type="text" class="form" id="txttaxfirstname" maxlength="255" /></td>
                  <td align="right">Tax Last Name: </td>
                  <td><input name="txttaxlastname" type="text" class="form" id="txttaxlastname" maxlength="255" /></td>
                </tr>
                <tr>
                  <td align="right">Tax Address:</td>
                  <td><input name="txttaxaddress" type="text" class="form" id="txttaxaddress" maxlength="255" /></td>
                  <td align="right">Tax Address2:</td>
                  <td><input name="txttaxaddress2" type="text" class="form" id="txttaxaddress2" maxlength="255" /></td>
                  <td align="right">Tax City: </td>
                  <td><input name="txttaxcity" type="text" class="form" id="txttaxcity" maxlength="255"/></td>
                </tr>
                <tr>
                  <td align="right">Tax State:</td>
                  <td><input name="txttaxstate" type="text" class="form" id="txttaxstate" maxlength="255" /></td>
                  <td align="right">Tax Zipcode:</td>
                  <td><input name="txttaxzipcode" type="text" class="form" id="txttaxzipcode" maxlength="255" /></td>
                  <td align="right">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="right">Tax Amount:</td>
                  <td colspan="5"><label>$ 
                    <input name="txttaxamount" type="text" class="bigValue" id="txttaxamount" maxlength="255"/>
                  </label></td>
                </tr>
                <tr>
                  <td align="right">Tax Notes1:</td>
                  <td><input name="txttaxnotes1" type="text" class="form" id="txttaxnotes1" maxlength="255" /></td>
                  <td align="right">Tax Notes2:  </td>
                  <td colspan="3"><input name="txttaxnotes2" type="text" class="form" id="txttaxnotes2" maxlength="255" /></td>
                  </tr>
                <tr>
                  <td align="right">&nbsp;</td>
                  <td colspan="5"><label>
                    <input name="txtmailtaxes" type="checkbox" id="txtmailtaxes" value="1" />
                    Do Not Mail Taxes
                    <input name="txtquestionable" type="checkbox" id="txtquestionable" value="1" />
                    Questionable</label></td>
                </tr>

                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
            </div></td>
          </tr>
          <tr>
            <td colspan="3" align="left" valign="top">&nbsp;</td>
            </tr>
          <tr>
            <td colspan="3" align="left" valign="top"><table width="60%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="7%" align="left" valign="top">&nbsp;</td>
                  <td width="36%" align="center" valign="top"><span class="dataField"><span class="dataLabel" style="display: inline;">
                    <input name="hdnproperty" type="hidden" id="hdnproperty" value="1" /><input name="addProperty" type="submit" class="button" id="addProperty" value="Save Property" />
                  </span></span></td>
                  <td width="49%" align="left" valign="top"><input name="Button" type="button" class="button" value="Cancel" onclick="history.back();"/></td>
                  <td width="8%" align="left" valign="top">&nbsp;</td>
                </tr>
            </table></td>
          </tr>
        </table>
            </form>
      </td>
    </tr>
  </tbody>
</table></td>
                </tr>
            </table></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
  </tbody>
</table>
<script>
var cal = new calendar(document.forms['form1'].elements['txtdatebuilt']);
cal.year_scroll = false;
cal.time_comp = false;
</script>
<? include("footer.php");?>
</body>
</html>
