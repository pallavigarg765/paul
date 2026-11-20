<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
	$id = (int)$_REQUEST['id'];
	/* Add Contacts */
	if(isset($_REQUEST['hdnaddcontact']) && $_REQUEST['hdnaddcontact'] == '1'){
		$create_date = strtotime(date("Y-m-d"));
		$sql = "INSERT INTO contact (userid,firstname,lastname,title,spousename,email,website,relationship,notes,create_date) VALUES(
		'".$_SESSION['userid']."','".addslashes($_REQUEST['txtfname'])."','".addslashes($_REQUEST['txtlname'])."','".addslashes($_REQUEST['txttitle'])."','".addslashes($_REQUEST['txtspousename'])."','".addslashes($_REQUEST['txtemail'])."',
		'".addslashes($_REQUEST['txtwebsite'])."','".addslashes($_REQUEST['txtrelation'])."','".addslashes($_REQUEST['txtnotes'])."','".$create_date."')";
		$db->query($sql) or die($sql);		
		$contactid = mysql_insert_id();
		$sql = "INSERT INTO contact_to_property(contact_id,property_id) VALUES(".$contactid.",".$id.")";
		$db->query($sql) or die($sql);
		$phone = $_REQUEST['txtphone'];
		if(count($phone) > 0){
			foreach($phone as $key => $value){
				//if($value != ''){
					if($_REQUEST['txtphone'][$key] == '') { $_REQUEST['txtphone'][$key] = '0'; }
					if($_REQUEST['txtext'][$key] == '') { $_REQUEST['txtext'][$key] = '0'; }
					if($_REQUEST['txttype'][$key] == '') { $_REQUEST['txttype'][$key] = '0'; }
					if($_REQUEST['txtpriority'][$key] == '') { $_REQUEST['txtpriority'][$key] = '0'; }
					
					$sql = "INSERT INTO phone (contact_id,number,ext,type,priority) VALUES(
					'".$contactid."','".addslashes($_REQUEST['txtphone'][$key])."','".addslashes($_REQUEST['txtext'][$key])."','".addslashes($_REQUEST['txttype'][$key])."','".addslashes($_REQUEST['txtpriority'][$key])."')";
					if(!$db->query($sql)) echo mysql_error();
				//}		
			}
		}
	}
	/* Update Contacts */
	if(isset($_REQUEST['hdnpropertycontact']) && $_REQUEST['hdnpropertycontact'] == '1'){
		$create_date = strtotime(date("Y-m-d"));
		$cid = (int)$_REQUEST['cid'];
		$sql = "UPDATE contact SET userid=".$_SESSION['userid'].",firstname='".addslashes($_REQUEST['txtfname'])."',lastname='".addslashes($_REQUEST['txtlname'])."',title='".addslashes($_REQUEST['txttitle'])."',spousename='".addslashes($_REQUEST['txtspousename'])."',email='".addslashes($_REQUEST['txtemail'])."',website='".addslashes($_REQUEST['txtwebsite'])."',relationship='".addslashes($_REQUEST['txtrelation'])."',notes='".addslashes($_REQUEST['txtnotes'])."',modified_date='".$create_date."' WHERE id=".$cid;
		$db->query($sql) or die($sql);		
		$contactid = $cid;
		$phone = $_REQUEST['phoneid'];
		foreach($phone as $key => $value){
			//if($value != ''){
			
				if($_REQUEST['txtphone'][$key] == '') { $_REQUEST['txtphone'][$key] = '0'; }
				if($_REQUEST['txtext'][$key] == '') { $_REQUEST['txtext'][$key] = '0'; }
				if($_REQUEST['txttype'][$key] == '') { $_REQUEST['txttype'][$key] = '0'; }
				if($_REQUEST['txtpriority'][$key] == '') { $_REQUEST['txtpriority'][$key] = '0'; }
			
				$sql = "SELECT * from phone WHERE id=".(int)$value;
				$res = $db->query($sql);
				$nrows = $db->getNumRows($res);
				if($nrows >= 1){
					$sql = "UPDATE phone SET number='".$_REQUEST['txtphone'][$key]."',ext='".$_REQUEST['txtext'][$key]."',type='".$_REQUEST['txttype'][$key]."',priority='".$_REQUEST['txtpriority'][$key]."' WHERE id=".(int)$value;
					$db->query($sql);
				}else{
					$sql = "INSERT INTO phone (contact_id,number,ext,type,priority) VALUES(
				'".$contactid."','".$_REQUEST['txtphone'][$key]."','".$_REQUEST['txtext'][$key]."','".$_REQUEST['txttype'][$key]."','".$_REQUEST['txtpriority'][$key]."')";
					$db->query($sql);
				}
			//}		
		}
	}
	
	if(isset($_REQUEST['hdnproperty']) && $_REQUEST['hdnproperty'] == '1'){
		$create_date = strtotime(date("Y-m-d"));
		// Update Property Information
		$txtcounty = $_REQUEST['txtcounty'];
		if($txtcounty == '') { $txtcounty = '0'; }
		if($_REQUEST['txtaudit1'] == '') { $audit1 = '0'; }
		if($_REQUEST['txtaudit2'] == '') { $audit2 = '0'; }
		if($_REQUEST['txtaudit3'] == '') { $audit3 = '0'; }
		if($_REQUEST['txttaxaudit'] == '') { $taxaudit = '0'; }
		if($_REQUEST['txtproperty_id'] == '') { $txtproperty_id = '0'; }
		if($_REQUEST['txtcompanyname'] == '') { $txtcompanyname = '0'; }
		if($_REQUEST['txtwebid'] == '') { $txtwebid = '0'; }
		if($_REQUEST['txtotherpropertyid'] == '') { $txtotherpropertyid = '0'; }
		if($_REQUEST['txtaddress'] == '') { $txtaddress = '0'; }
		if($_REQUEST['txtaddress2'] == '') { $txtaddress2 = '0'; }
		if($_REQUEST['txtstate'] == '') { $txtstate = '0'; }
		if($_REQUEST['txtcountry'] == '') { $txtcountry = '0'; }
		if($_REQUEST['txtzipcode'] == '') { $txtzipcode = '0'; }
		if($_REQUEST['txtcity'] == '') { $txtcity = '0'; }
		if($_REQUEST['cb1'] == '') { $cb1 = '0'; } else { $cb1 = '1'; }
		if($_REQUEST['cb2'] == '') { $cb2 = '0'; } else { $cb2 = '1'; }
		if($_REQUEST['cb3'] == '') { $cb3 = '0'; } else { $cb3 = '1'; }
				
		if($txtcounty != '0'){
			$sqlCounty = "SELECT id FROM county_new WHERE name = '".$txtcounty."'";
			//echo $sqlCounty;
			if($resCounty = mysql_query($sqlCounty)){
				if(mysql_num_rows($resCounty) > 0){
					$rowCounty = mysql_fetch_array($resCounty);
					$txtcounty = $rowCounty['id'];
				}else{
					$insCounty = "INSERT INTO county_new (name) VALUES ('".$txtcounty."')";
					if(mysql_query($insCounty)){
						$txtcounty = mysql_insert_id();
					}
				}
			}else{
				$txtcounty = '0';
			}
		}
		
		$sql = "UPDATE property SET userid='".$_SESSION['userid']."',property_id='".addslashes($txtproperty_id)."',company='".addslashes($txtcompanyname)."',webid='".addslashes($txtwebid)."',	otherpropertyid='".addslashes($txtotherpropertyid)."', address='".addslashes($txtaddress)."',address2='".addslashes($txtaddress2)."',state='".addslashes($txtstate)."',zipcode='".addslashes($txtzipcode)."',city='".addslashes($txtcity)."',county='".$txtcounty."', audit1='".addslashes($audit1)."',audit2='".addslashes($audit2)."',audit3='".addslashes($audit3)."', taxaudit='".addslashes($taxaudit)."',modified_date='".$create_date."', cb1 = '".$cb1."', cb2 = '".$cb2."', cb3 = '".$cb3."', country = '".$txtcountry."',is_show='".$_REQUEST['is_show']."'  WHERE id='".$_REQUEST['id']."'";
		$db->query($sql) or die($sql." ".mysql_error());		
		$property_id = $_REQUEST['id'];
		if(isset($_REQUEST['txtcontacts'])){
			$sql = "DELETE FROM contact_to_property WHERE property_id=".$property_id;
			$db->query($sql);
			foreach($_REQUEST['txtcontacts'] as $value){
				if($value != ''){
					$sql = "INSERT INTO contact_to_property(contact_id,property_id) VALUES(".$value.",".$property_id.")";
					$db->query($sql) or die($sql);
				}
			}
		}
		// Insert Location Information
		if($_REQUEST['txtlicense'] != '' || $_REQUEST['txtsquarefeet'] != '' || $_REQUEST['txtlimitareas'] != ''
		 || $_REQUEST['txtneighbors'] != '' || $_REQUEST['txtanimals'] != '' || $_REQUEST['txtdatebuilt'] != ''
		  || $_REQUEST['txtpreviousfilming'] != '' || $_REQUEST['txtparking'] != '' || $_REQUEST['txtbedroom'] != ''
		   || $_REQUEST['txtrestroom'] != '' || $_REQUEST['txtreferalform'] != '' || $_REQUEST['txtadultfriendly'] != ''
		    || $_REQUEST['txtpool'] != '' || $_REQUEST['txttennisecourt'] != '' || $_REQUEST['txtcityview'] != ''
			|| $_REQUEST['txtnicebackyard'] != '' || $_REQUEST['txthomeassociation'] != ''
			 || $_REQUEST['txtarchitect'] != '' || $_REQUEST['txthousename'] != '' || $_REQUEST['txtnewpropertyowner'] != ''
			  || $_REQUEST['txtfilmingorevents'] != '' || $_REQUEST['txtnotes'] != ''){
				$datebuilt = $_REQUEST['txtdatebuilt'];
				$sql = "SELECT * FROM location WHERE property_id=".(int)$_REQUEST['location_id'];
				$res1 = $db->query($sql) or die($sql);
				
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
				if($_REQUEST['txtpool'] == '') { $txtpool = '0'; }
				if($_REQUEST['txttennisecourt'] == '') { $txttennisecourt = '0'; }
				if($_REQUEST['txtcityview'] == '') { $txtcityview = '0'; }
				if($_REQUEST['txtnicebackyard'] == '') { $txtnicebackyard = '0'; }
				if($_REQUEST['txthomeassociation'] == '') { $txthomeassociation = '0'; }
				if($_REQUEST['txtarchitect'] == '') { $txtarchitect = '0'; }
				if($_REQUEST['txthousename'] == '') { $txthousename = '0'; }
				if($_REQUEST['txtnewpropertyowner'] == '') { $txtnewpropertyowner = '0'; }
				if($_REQUEST['txtfilmingorevents'] == '') { $txtfilmingorevents = '0'; }
				if($_REQUEST['txtnotes'] == '') { $txtnotes = '0'; }
				if($_REQUEST['txtdatebuilt'] == '') { $txtdatebuilt = $create_date; }
				
				
				if($db->getNumRows($res1) >= 1){
					$sql = "UPDATE location SET license='".addslashes($txtlicense)."',squarefit='".addslashes($txtsquarefeet)."',
							limitareas='".addslashes($txtlimitareas)."',neighbors='".addslashes($txtneighbors)."',animals='".addslashes($txtanimals)."',
							datebuilt='".addslashes($datebuilt)."',previousfilming='".addslashes($txtpreviousfilming)."',parking='".addslashes($txtparking)."',
							bedroom='".addslashes($txtbedroom)."',restroom='".addslashes($txtrestroom)."',referalform='".addslashes($txtreferalform)."',
							adultfriendly='".addslashes($txtadultfriendly)."',pool='".addslashes($txtpool)."',tennisecourt='".addslashes($txttennisecourt)."',
							cityview='".addslashes($txtcityview)."',nicebackyard='".addslashes($txtnicebackyard)."',
							homeassociation='".addslashes($txthomeassociation)."',architect='".addslashes($txtarchitect)."',
							housename='".addslashes($txthousename)."',newpropertyowner='".addslashes($txtnewpropertyowner)."',
							filmingorevents='".addslashes($txtfilmingorevents)."',notes='".addslashes($txtnotes)."',modified_date='".$create_date."' WHERE property_id=".(int)$_REQUEST['location_id'];
							
					$db->query($sql) or die($sql." ".mysql_error());
				}else{
					$sql = "INSERT INTO location (property_id,license,squarefit,limitareas,neighbors,animals,datebuilt,
					previousfilming,parking,bedroom,restroom,referalform,adultfriendly,pool,tennisecourt,cityview,nicebackyard,
					homeassociation,architect,housename,newpropertyowner,filmingorevents,notes,create_date) VALUES(
				'".addslashes($property_id)."','".addslashes($txtlicense)."','".addslashes($txtsquarefeet)."','".addslashes($txtlimitareas)."',
				'".addslashes($txtneighbors)."','".addslashes($txtanimals)."','".addslashes($datebuilt)."','".addslashes($txtpreviousfilming)."',
				'".addslashes($txtparking)."','".addslashes($txtbedroom)."','".addslashes($txtrestroom)."','".addslashes($txtreferalform)."',
				'".addslashes($txtadultfriendly)."','".addslashes($txtpool)."','".addslashes($txttennisecourt)."',
				'".addslashes($txtcityview)."','".addslashes($txtnicebackyard)."','".addslashes($txthomeassociation)."',
				'".addslashes($txtarchitect)."','".addslashes($txthousename)."','".addslashes($txtnewpropertyowner)."',
				'".addslashes($txtfilmingorevents)."','".addslashes($txtnotes)."','".$create_date."')";
					$db->query($sql) or die($sql);
				}
		}
		// Insert Budget Information
		if($_REQUEST['txthighbudget'] != '' || $_REQUEST['txtmediumbudget'] != '' || $_REQUEST['txtlowbudget'] != ''
		 || $_REQUEST['txtbudgetnotes'] != '' || $_REQUEST['txtfilmfriendly'] != '' || $_REQUEST['txtinteriorfilming'] != ''
		  || $_REQUEST['txtexteriorfilming'] != '' || $_REQUEST['txtfilmbudget'] != '' || $_REQUEST['txtfilmmaxheat'] != ''
		   || $_REQUEST['txtfilmparking'] != '' || $_REQUEST['txtfilmnotes'] != '' || $_REQUEST['txteventfriendly'] != ''
		    || $_REQUEST['txtinteriorevent'] != '' || $_REQUEST['txtexteriorevent'] != '' || $_REQUEST['txteventbudget'] != ''
			 || $_REQUEST['txteventmaxheat'] != '' || $_REQUEST['txteventparking'] != '' || $_REQUEST['txteventnotes'] != ''){
				$sql = "SELECT * FROM budget WHERE id=".(int)$_REQUEST['budget_id'];
				$res1 = $db->query($sql) or die($sql);
					
				if($_REQUEST['txthighbudget'] == '') { $txthighbudget = 0; }
				if($_REQUEST['txtmediumbudget'] == '') { $txtmediumbudget = 0; }
				if($_REQUEST['txtlowbudget'] == '') { $txtlowbudget = 0; }
				if($_REQUEST['txtbudgetnotes'] == '') { $txtbudgetnotes = 0; }
				if($_REQUEST['txtfilmfriendly'] == '') { $txtfilmfriendly = 0; }
				if($_REQUEST['txtinteriorfilming'] == '') { $txtinteriorfilming = 0; }
				if($_REQUEST['txtexteriorfilming'] == '') { $txtexteriorfilming = 0; }
				if($_REQUEST['txtfilmbudget'] == '') { $txtfilmbudget = 0; }
				if($_REQUEST['txtfilmmaxheat'] == '') { $txtfilmmaxheat = 0; }
				if($_REQUEST['txtfilmparking'] == '') { $txtfilmparking = 0; }
				if($_REQUEST['txtfilmnotes'] == '') { $txtfilmnotes = 0; }
				if($_REQUEST['txteventfriendly'] == '') { $txteventfriendly = 0; }
				if($_REQUEST['txtinteriorevent'] == '') { $txtinteriorevent = 0; }
				if($_REQUEST['txtexteriorevent'] == '') { $txtexteriorevent = 0; }
				if($_REQUEST['txteventbudget'] == '') { $txteventbudget = 0; }
				if($_REQUEST['txteventmaxheat'] == '') { $txteventmaxheat = 0; }
				if($_REQUEST['txteventparking'] == '') { $txteventparking = 0; }
				if($_REQUEST['txteventnotes'] == '') { $txteventnotes = 0; }
				
				if($db->getNumRows($res1) >= 1){	
					$sql = "UPDATE budget SET highbudget='".addslashes($txthighbudget)."',mediumbudget='".addslashes($txtmediumbudget)."',lowbudget='".addslashes($txtlowbudget)."',budgetnotes='".addslashes($txtbudgetnotes)."',filmfriendly='".addslashes($txtfilmfriendly)."',interiorfilming='".addslashes($txtinteriorfilming)."',exteriorfilming='".addslashes($txtexteriorfilming)."',filmbudget='".addslashes($txtfilmbudget)."',filmmaxheat='".addslashes($txtfilmmaxheat)."',filmparking='".addslashes($txtfilmparking)."',filmnotes='".addslashes($txtfilmnotes)."',eventfriendly='".addslashes($txteventfriendly)."',interiorevent='".addslashes($txtinteriorevent)."',exteriorevent='".addslashes($txtexteriorevent)."',eventbudget='".addslashes($txteventbudget)."',eventmaxheat='".addslashes($txteventmaxheat)."',eventparking='".addslashes($txteventparking)."',eventnotes='".addslashes($txteventnotes)."',modified_date='".$create_date."' WHERE id=".(int)$_REQUEST['budget_id'];
					$db->query($sql) or die($sql);
				}else{
					$sql = "INSERT INTO budget (property_id,highbudget,mediumbudget,lowbudget,budgetnotes,filmfriendly,interiorfilming,
					exteriorfilming,filmbudget,filmmaxheat,filmparking,filmnotes,eventfriendly,interiorevent,exteriorevent,eventbudget,
					eventmaxheat,eventparking,eventnotes,create_date) VALUES(
				'".addslashes($property_id)."','".addslashes($txthighbudget)."','".addslashes($txtmediumbudget)."','".addslashes($txtlowbudget)."',
				'".addslashes($txtbudgetnotes)."','".addslashes($txtfilmfriendly)."','".addslashes($txtinteriorfilming)."',
				'".addslashes($txtexteriorfilming)."','".addslashes($txtfilmbudget)."','".addslashes($txtfilmmaxheat)."',
				'".addslashes($txtfilmparking)."','".addslashes($txtfilmnotes)."','".addslashes($txteventfriendly)."',
				'".addslashes($txtinteriorevent)."','".addslashes($txtexteriorevent)."','".addslashes($txteventbudget)."',
				'".addslashes($txteventmaxheat)."','".addslashes($txteventparking)."','".addslashes($txteventnotes)."','".addslashes($create_date)."')";
					$db->query($sql) or die($sql);
				}
		}
		// Insert Tax Information
		if($_REQUEST['txttaxcompany'] != '' || $_REQUEST['txttaxfirstname'] != '' || $_REQUEST['txttaxlastname'] != ''
		 || $_REQUEST['txttaxaddress'] != '' || $_REQUEST['txttaxaddress2'] != '' || $_REQUEST['txttaxcity'] != ''
		  || $_REQUEST['txttaxstate'] != '' || $_REQUEST['txttaxzipcode'] != '' || $_REQUEST['txttaxamount'] != ''
		   || $_REQUEST['txttaxnotes1'] != '' || $_REQUEST['txttaxnotes2'] != '' || $_REQUEST['txtmailtaxes'] != ''
		    || $_REQUEST['txtquestionable'] != ''){
			$sql = "SELECT * FROM tax WHERE id=".(int)$_REQUEST['tax_id'];
			$res1 = $db->query($sql) or die($sql);
			
			if($_REQUEST['txttaxcompany'] == ''){ $txttaxcompany = 0; }
			if($_REQUEST['txttaxfirstname'] == ''){ $txttaxfirstname = 0; }
			if($_REQUEST['txttaxlastname'] == ''){ $txttaxlastname = 0; }
			if($_REQUEST['txttaxaddress'] == ''){ $txttaxaddress = 0; }
			if($_REQUEST['txttaxaddress2'] == ''){ $txttaxaddress2 = 0; }
			if($_REQUEST['txttaxcity'] == ''){ $txttaxcity = 0; }
			if($_REQUEST['txttaxstate'] == ''){ $txttaxstate = 0; }
			if($_REQUEST['txttaxzipcode'] == ''){ $txttaxzipcode = 0; }
			if($_REQUEST['txttaxamount'] == ''){ $txttaxamount = 0; }
			if($_REQUEST['txttaxnotes1'] == ''){ $txttaxnotes1 = 0; }
			if($_REQUEST['txttaxnotes2'] == ''){ $txttaxnotes2 = 0; }
			if($_REQUEST['txtmailtaxes'] == ''){ $txtmailtaxes = 0; }
			if($_REQUEST['txtquestionable'] == ''){ $txtquestionable = 0; }
			
			if($db->getNumRows($res1) >= 1){
				
				$sql = "UPDATE tax SET company='".addslashes($txttaxcompany)."',firstname='".addslashes($txttaxfirstname)."',
				lastname='".addslashes($txttaxlastname)."',address='".addslashes($txttaxaddress)."',address2='".addslashes($txttaxaddress2)."',
				city='".addslashes($txttaxcity)."',state='".addslashes($txttaxstate)."',zipcode='".addslashes($txttaxzipcode)."',
				amount='".addslashes($txttaxamount)."',notes1='".addslashes($txttaxnotes1)."',notes2='".addslashes($txttaxnotes2)."',
				mailtaxes='".addslashes($txtmailtaxes)."',questionable='".addslashes($txtquestionable)."',modified_date='".$create_date."'
				WHERE id=".(int)$_REQUEST['tax_id'];
				$db->query($sql) or die($sql);
			}else{
				$sql = "INSERT INTO tax (property_id,company,firstname,lastname,address,address2,city,state,zipcode,amount,notes1,notes2,
				mailtaxes,questionable,create_date) VALUES(
				'".addslashes($property_id)."','".addslashes($txttaxcompany)."','".addslashes($txttaxfirstname)."','".addslashes($txttaxlastname)."',
				'".addslashes($txttaxaddress)."','".addslashes($txttaxaddress2)."','".addslashes($txttaxcity)."',
				'".addslashes($txttaxstate)."','".addslashes($txttaxzipcode)."','".addslashes($txttaxamount)."',
				'".addslashes($txttaxnotes1)."','".addslashes($txttaxnotes2)."','".addslashes($txtmailtaxes)."',
				'".addslashes($txtquestionable)."','".$create_date."')";
				$db->query($sql) or die($sql);
			}
		}   		
		//header("Location: property.php?id=".$id);
	}
	$sql = "SELECT * FROM property WHERE id=".$id;
	$pres = $db->query($sql) or die($sql);
	$prow = $db->fetchArray($pres);
	
	$sql = "SELECT * FROM location WHERE property_id=".$id;
	$lres = $db->query($sql) or die($sql);
	$lrow = $db->fetchArray($lres);
	
	$sql = "SELECT * FROM budget WHERE property_id=".$id;
	$bres = $db->query($sql) or die($sql);
	$brow = $db->fetchArray($bres);
	
	$sql = "SELECT * FROM tax WHERE property_id=".$id;
	$tres = $db->query($sql) or die($sql);
	$trow = $db->fetchArray($tres);
	 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CRM</title>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="js/calendar.js"></script>
<script src="js/prototype.js"></script>
<script type="text/javascript" src="js/effects.js"></script>
 <script type="text/javascript" src="js/lightwindow.js"></script> 
<script>
<!--
function openwindow(){
	window.open('selectcontact.php','contactwin','height=300,width=800');
}

function validateNumber(event) {
    var key = window.event ? event.keyCode : event.which;

    if (event.keyCode == 8 || event.keyCode == 46
     || event.keyCode == 37 || event.keyCode == 39) {
        return true;
    }
    else if ( key < 48 || key > 57 ) {
        return false;
    }
    else return true;
};

function addContact(){ 
	if(document.getElementById("addContact").style.display == 'none')
		document.getElementById("addContact").style.display = 'block';
	else
		document.getElementById("addContact").style.display = 'none';
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
function verifyaddproperty(){
	return true;
}

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

//Delete Notes
function deleteNotes(noteid,type,property_id){
		url = '<?=APP_WEB_ROOT?>selectnotes.php?id='+noteid+'&type='+type+'&property_id='+property_id;
		notediv = 'notes'+type;
		var er = $('errornotes');
		new Ajax.Request(url, {
		method: 'get',
		onSuccess: function(transport) {
			document.getElementById(notediv).innerHTML=transport.responseText;
		},onFailure: function(){ alert('Error while performing AJAX request.') }
    	});
}
function addPhonetypes(){
	var divTag = document.createElement("div");
	divTag.innerHTML = '<div id="phonetype"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tr><td width="33%" align="left" valign="top" class="formtxt">Phone:</td><td width="29%" align="left" valign="top" class="formtxt">Ext:</td><td width="20%" align="left" valign="top" class="formtxt">Type:</td><td width="18%" align="left" valign="top" class="formtxt">Priority:</td></tr><tr><td align="left" valign="top" width="33%"><input name="txtphone[]" type="text" class="form phone" id="txtphone[]" size="35" maxlength="50" /></td><td align="left" valign="top" width="29%"><input name="txtext[]" type="text" class="form" id="txtext[]" size="20" maxlength="10" /></td><td align="left" valign="top" width="20%"><?=fillCombo('txttype[]','types','id','name','WHERE active=1','','dropdown','')?></td><td align="left" valign="top" width="18%"><?=listbox_array($priorityArray,'txtpriority[]')?></td></tr></table></div><input name="phoneid[]" type="hidden" value="0" />';
	document.getElementById("phonetype").appendChild(divTag);
}

function editPhonetypes(){ 
	var divTag = document.createElement("div");
	divTag.innerHTML = '<div id="phonetype"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tr><td width="33%" align="left" valign="top" class="formtxt">Phone:</td><td width="29%" align="left" valign="top" class="formtxt">Ext:</td><td width="20%" align="left" valign="top" class="formtxt">Type:</td><td width="18%" align="left" valign="top" class="formtxt">Priority:</td></tr><tr><td align="left" valign="top" width="33%"><input name="txtphone[]" type="text" class="form phone" id="txtphone[]" size="35" maxlength="50" /></td><td align="left" valign="top" width="29%"><input name="txtext[]" type="text" class="form" id="txtext[]" size="20" maxlength="10" /></td><td align="left" valign="top" width="20%"><?=fillCombo('txttype[]','types','id','name','WHERE active=1','','dropdown','')?></td><td align="left" valign="top" width="18%"><?=listbox_array($priorityArray,'txtpriority[]')?></td></tr></table></div><input name="phoneid[]" type="hidden" value="0" />';
	document.getElementById("phonetype").appendChild(divTag);
}
function verifcreatecontact(){
	return true;
}
//-->
</script>

<link rel="stylesheet" type="text/css" href="css/lightwindow.css" />
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
            <td colspan="3" align="left" valign="top"><table width="60%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="top"><span class="dataField"><span class="dataLabel" style="display: inline;">
                  <input name="hdnproperty2" type="hidden" id="hdnproperty2" value="1" />
                  <input name="addProperty2" type="submit" class="button" id="addProperty2" value="Save Property" />
                </span></span></td>
                <td width="49%" align="left" valign="top"><input name="Button4" type="button" class="button" value="Cancel" onclick="history.back();"/></td>
                <td width="8%" align="left" valign="top">&nbsp;</td>
              </tr>
            </table></td>
            </tr>
          <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td width="31%" align="left" valign="top" class="formtxt">Property ID: </td>
            <td width="0" align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><span class="formtxt">Address:</span></td>
          </tr>
          <tr>
            <td align="left" valign="top"><input tabindex="1" name="txtproperty_id" id="txtproperty_id" type="text" class="form" size="35" maxlength="255" value="<?php	                                       				if($prow['property_id'] != '0') echo $prow['property_id']; ?>"/></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><input tabindex="5" name="txtaddress" id="txtaddress" type="text" class="form" size="35" maxlength="255" value="<?php	                                       				if($prow['address'] != '0') echo $prow['address'];  ?>"/></td>
          </tr>
          <tr>
            <td align="left" valign="top"><span class="formtxt">Company Name:</span></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><span class="formtxt">Address2: </span></td>
          </tr>
          <tr>
            <td align="left" valign="top"><input tabindex="2" name="txtcompanyname" id="txtcompanyname" type="text" class="form" size="35" maxlength="255" value="<?php	                                       				if($prow['company'] != '0') echo $prow['company']; ?>"/></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><input tabindex="6" name="txtaddress2" type="text" class="form" id="txtaddress2" size="35" maxlength="255" value="<?php	                                       				if($prow['address2'] != '0') echo $prow['address2'];  ?>"/></td>
          </tr>
          <tr>
            <td align="left" valign="top"><span class="formtxt">Web ID:</span></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><span class="formtxt">City:</span></td>
          </tr>
          <tr>
            <td align="left" valign="top"><input tabindex="3" name="txtwebid" id="txtwebid" type="text" class="form" size="35" maxlength="255" value="<?php	                                       				if($prow['webid'] != '0') echo $prow['webid']; ?>"/></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">
				<input tabindex="7" name="txtcity" id="txtcity" type="text" class="form" size="35" maxlength="255" value="<?php if($prow['city'] != '0') echo $prow['city']; ?>"/>
				<div id="listCities" style="background-color: white; padding: 3px;display: none; border: 1px solid silver; position: absolute;width: 222px; min-height: 10px;">Select city: <br /><br /></div>
			</td>
          </tr>

          <tr>
            <td align="left" valign="top"><span class="formtxt">Other Property ID:</span></td>
            <td align="left" valign="top" class="formtxt">&nbsp;</td>
            <td align="left" valign="top" class="formtxt">State:</td>
          </tr>
          <tr>
            <td align="left" valign="top"><input tabindex="4" name="txtotherpropertyid" id="txtotherpropertyid" type="text" class="form" size="35" maxlength="255" value="<?php	                                       				if($prow['otherpropertyid'] != '0') echo $prow['otherpropertyid']; ?>"/></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"  tabindex="8" >
				<?php
				$stateName = "";
				$getStateName = "SELECT name FROM state WHERE id = '".$prow['state']."'";
				if($resStateName = mysql_query($getStateName)){
					$rowStateName = mysql_fetch_array($resStateName);
					$stateName = $rowStateName['name'];
				}
				?>
				<select class="dropdown" id="txtstate" name="txtstate">
					<?php                           				
					$getStates = "SELECT id, name FROM state WHERE active = 1 GROUP BY name ORDER BY name ASC";
					if($resStates = $db->query($getStates)){
						while($rowStates = $db->fetchArray($resStates)){
							?>
							<option <?php if(trim($rowStates['name'], " ") == trim($stateName, " ")) echo "selected='selected'" ?> value="<?php echo $rowStates['id'] ?>">
								<?php echo $rowStates['name']; ?>
							</option>
							<?php	                                       				
						}
					}
					?>
				</select>
				<!-- <?=fillCombo('txtstate','state','id','name','WHERE active=1',$prow['state'],'dropdown','')?> -->
				<span id="infoZip" class="formtxt" style="margin-left:10px;"></span>
			</td>
          </tr>
          <tr>
            <td align="left" valign="top" colspan="2" class="formtxt">
				CB1 <input class="cb1" id="cb1<?php	echo $prow['id']; ?>" tabindex="11" type="checkbox" name="cb1" <?php if($prow["cb1"] == "1") echo "checked='checked'"; ?> />
				&nbsp;&nbsp;&nbsp;
				CB2 <input class="cb1" id="cb2<?php echo $prow['id']; ?>" tabindex="11" type="checkbox" name="cb2" <?php if($prow["cb2"] == "1") echo "checked='checked'"; ?> />
				&nbsp;&nbsp;&nbsp;
				CB3 <input class="cb1" id="cb3<?php echo $prow['id']; ?>" tabindex="11" type="checkbox" name="cb3" <?php if($prow["cb3"] == "1") echo "checked='checked'"; ?> />
			</td>
            <td align="left" valign="top" class="formtxt">Zipcode:</td>
          </tr>
          <tr>
            <td align="left" valign="top" colspan="2"  class="formtxt">Property show/hide on search Page : </td>
            <td align="left" valign="top" >
				<input tabindex="9" name="txtzipcode" id="txtzipcode" type="text" class="form" size="10" maxlength="5" value="<?php if($prow['zipcode'] != '0') echo $prow['zipcode']; ?>" />
				<input tabindex="9" type="button" value="Find city and state" name="getCity" id="getCity" />
			</td>
          </tr>
          <tr>
            <td align="left" valign="top" class="formtxt">Show <input class="cb1" id="cb1<?php echo $prow['is_show']; ?>" value="Y" type="radio" name="is_show" <?php if($prow["is_show"] == 'Y') echo "checked='checked'"; ?> />&nbsp;&nbsp;&nbsp;
			Hide <input class="cb1" id="cb1<?php echo $prow['is_show']; ?>" value="N" type="radio" name="is_show" <?php if($prow["is_show"] == 'N') echo "checked='checked'"; ?> />			
			</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top" class="formtxt">County:</td>
          </tr>
          <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top" tabindex="10">
				<?php // echo fillCombo('txtcounty','county','id','name','WHERE active=1',$prow['county'],'dropdown',''); ?>
				<?php 
				$county = '';
				$sqlCounty = "SELECT name FROM county_new WHERE id = '".$prow['county']."'";
				if($resCounty = mysql_query($sqlCounty)){
					if(mysql_num_rows($resCounty) > 0){
						$rowCounty = mysql_fetch_array($resCounty);
						$county = $rowCounty['name'];
					}
				}
				?>
				<input type="text" name="txtcounty" id="txtcounty" class="form" size="35" value="<?php echo $county; ?>" />
			</td>
          </tr>
		  <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top" class="formtxt">Country:</td>
          </tr>
          <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top" tabindex="10">
				<?php //print_r($prow); die; ?>
				<select class="dropdown" id="txtcountry" name="txtcountry" style="width: 230px;">
					<?php
					$getCountries = "SELECT id, printable_name FROM country ORDER BY printable_name ASC";
					if($resCountries = $db->query($getCountries)){
						$p = $prow['country'];
						if($p == "0")
							$p = "223";
						while($rowCountries = $db->fetchArray($resCountries)){
							?>
							<option <?php if($p == $rowCountries['id']) echo "selected='selected'" ?> value="<?php echo $rowCountries['id'] ?>">
								<?php echo $rowCountries['printable_name'] ?>
							</option>
							<?php	                                       				
						}
					}
					?>
				</select>
			</td>
          </tr>
          <tr align="left">
            <td colspan="3" valign="middle" class="formtxt">&nbsp;</td>
          </tr>
          <tr align="left" style="display:none">
            <td colspan="3" valign="middle" class="formtxt"><label>
              <input name="txtaudit1" type="checkbox" id="txtaudit1" value="1" <? if($prow['audit1'] == 1){?> checked="checked" <? }?>/>
              Audit1
              <input name="txtaudit2" type="checkbox" id="txtaudit2" value="1" <? if($prow['audit2'] == 1){?> checked="checked" <? }?>/>
              Audit2
              <input name="txtaudit3" type="checkbox" id="txtaudit3" value="1" <? if($prow['audit3'] == 1){?> checked="checked" <? }?>/>
              Audit3
              <input name="txttaxaudit" type="checkbox" id="txttaxaudit" value="1" <? if($prow['taxaudit'] == 1){?> checked="checked" <? }?>/>
              Tax Audit</label></td>
            </tr><input name="id" type="hidden" value="<?=$prow['id']?>" />
          <tr align="left">
            <td valign="middle">&nbsp;</td>
            <td valign="top">&nbsp;</td>
            <td valign="middle">&nbsp;</td>
          </tr>
          <tr align="left">
            <td colspan="3" valign="middle"><div id="addContact" style="display:"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2"><table width="100%" border="0" cellpadding="2" cellspacing="0" class="listview">
                  <tr bgcolor="#D0E1F4" height="20">
                    <td width="4%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    <td height="25" colspan="2" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a"> Contact Name &nbsp;&nbsp; </div></td>
                    <td width="14%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a">Phone&nbsp; </div></td>
                    <td width="19%" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a">Email&nbsp;&nbsp; </div></td>
                    <td width="25%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a">Notes&nbsp;&nbsp; </div></td>
                    <td width="12%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><span class="a">Action</span>&nbsp;&nbsp; <a href="add-property-contact.php" class="lightwindow page-options n">Add New</a> </td>
                    <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                  </tr>
                  <? 
						$sqlcontact = "SELECT contact.* FROM contact_to_property inner join contact on contact_to_property.contact_id=contact.id
										WHERE contact_to_property.property_id=".$prow['id'];
						$cresult = $db->query($sqlcontact) or die(mysql_error()." ".$sqlcontact);
						if($db->getNumRows($cresult) >= 1){
							while($crow = $db->fetchArray($cresult)){
								$pssql = "SELECT number,ext, types.name as typename FROM phone 
								left join types on phone.type=types.id WHERE number!= '' and contact_id=".$crow['id']." ORDER by priority asc ";
								$psres = $db->query($pssql) or die($pssql);
								$phonenumbers = '';
								while($psrow = $db->fetchArray($psres)){
									$phonenumbers .= CheckUSPhoneNum($psrow['number']);
									if($psrow['ext'] != '0')
										$phonenumbers.=' ext:'.$psrow['ext'];
									if($psrow['typename'] != '')
										$phonenumbers.=' ('.$psrow['typename'].')';
									$phonenumbers .= '<br>';
								}
							 
							
					?>
                  <tr height="20">
                    <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    <td height="25" colspan="2" nowrap="nowrap" class="listViewThS1" scope="col"><a href="view-property-contact.php?c=<?=$crow['id']?>" class="lightwindow page-options n"><?php	                                       				if($crow['firstname'] != '0') echo $crow['firstname']; ?> <?php	                                       				if($crow['lastname'] != '0') echo $crow['lastname']; ?></a></td>
                    <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><?php	                                       				if($phonenumbers != '0') echo $phonenumbers; ?></td>
                    <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><?php	                                       				if($crow['email'] != '0') echo $crow['email']; ?></td>
                    <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><?php	                                       				if($crow['notes'] != '0') echo $crow['notes']; ?></td>
                    <td width="12%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><a href="edit-property-contact.php?id=<?=$crow['id']?>" class="lightwindow page-options n">Edit</a></td>
                    <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                  </tr>
                  <? }
					}else{?>
                  <tr height="20">
                    <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    <td height="25" colspan="5" nowrap="nowrap" class="listViewThS1" scope="col">No record exists.</td>
                    <td width="12%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"></td>
                    <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                  </tr>
                  <? }?>
                </table></td>
                </tr>
				 <?
					  	$sqlcontact = "SELECT contact.* FROM contact_to_property inner join contact on contact_to_property.contact_id=contact.id
										WHERE contact_to_property.property_id=".$prow['id'];
						$cresult = $db->query($sqlcontact) or die($sqlcontact);
						while($crow = $db->fetchArray($cresult)){
					  ?>
					  	<input name="txtcontacts[]" type="hidden" value="<?=$crow['id']?>" />
					  <? }?>
              
            </table>
            </div></td>
          </tr>
          <tr align="left">
            <td height="35" valign="middle"><input tabindex="11" name="Button" type="button" class="h" value="Add Location Information" onclick="addLocation();" style="height:25px"/></td>
            <td height="35" valign="top">&nbsp;</td>
            <td height="35" valign="middle">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" align="left" valign="top"><div id="addLocation" <? if(!is_array($lrow) && empty($lrow)){?>style="display:none"<? }?>>
              <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formtxt">
                <tr>
                  <td align="right">License:</td>
                  <td><input tabindex="11" name="txtlicense" type="checkbox" class="formtxt" id="txtlicense" value="1" <? if($lrow['license'] == 1){?> checked="checked" <? }?>/></td>
                  <td align="right">Sq Feet: </td>
                  <td><input tabindex="15" name="txtsquarefeet" type="text" class="form" id="txtsquarefeet" maxlength="255" value="<?php	                                       				if($lrow['squarefit'] != '0') echo $lrow['squarefit']; ?>"/></td>
                  <td align="right">Areas off limit: </td>
                  <td><input tabindex="19" name="txtlimitareas" type="text" class="form" id="txtlimitareas" maxlength="255" value="<?php	                                       				if($lrow['limitareas'] != '0') echo $lrow['limitareas']; ?>"/></td>
                </tr>
                <tr>
                  <td align="right">Neighbors:</td>
                  <td><input tabindex="12" name="txtneighbors" type="text" class="form" id="txtneighbors" maxlength="255" value="<?php	                                       				if($lrow['neighbors'] != '0') echo $lrow['neighbors']; ?>"/></td>
                  <td align="right">Animals:</td>
                  <td><input tabindex="16" name="txtanimals" type="text" class="form" id="txtanimals" maxlength="255" value="<?php	                                       				if($lrow['animals'] != '0') echo $lrow['animals']; ?>"/></td>
                  <td align="right">Date Built: </td>
                  <td>
					
					<input tabindex="20" name="txtdatebuilt" type="text" class="form" id="txtdatebuilt" value="<?php if($lrow['datebuilt'] != '0') echo $lrow['datebuilt']; ?>" onclick="javascript:cal.popup();"/>
					<!--
					<select class="form" id="txtdatebuilt" name="txtdatebuilt" tabindex="20" style="width: 150px; height: 19px;">
						<?php 
						for($y = date("Y"); $y > 1949; $y--){
							?>
							<option <?php if($lrow['datebuilt'] == $y) echo "selected='selected'"; ?> value="<?php echo $y ?>"><?php echo $y ?></option>
							<?php 
						}
						?>
					</select>
					-->
				  </td>
                </tr>
                <tr>
                  <td align="right">Previous Filming:</td>
                  <td><input tabindex="13" name="txtpreviousfilming" type="text" class="form" id="txtpreviousfilming" maxlength="255" value="<?php	                                       				if($lrow['previousfilming'] != '0') echo $lrow['previousfilming']; ?>"/></td>
                  <td align="right">Parking:</td>
                  <td><input tabindex="17" name="txtparking" type="text" class="form" id="txtparking" maxlength="255" value="<?php	                                       				if($lrow['parking'] != '0') echo $lrow['parking']; ?>"/></td>
                  <td align="right">Bedroom Count: </td>
                  <td><input tabindex="21" name="txtbedroom" type="text" class="form" id="txtbedroom" maxlength="255" value="<?php	                                       				if($lrow['bedroom'] != '0') echo $lrow['bedroom']; ?>"/></td>
                </tr>
                <tr>
                  <td align="right">Restroom Count: </td>
                  <td><input tabindex="14" name="txtrestroom" type="text" class="form" id="txtrestroom" maxlength="255" value="<?php	                                       				if($lrow['restroom'] != '0') echo $lrow['restroom']; ?>"/></td>
                  <td align="right">Referal Form: </td>
                  <td><textarea tabindex="18" name="txtreferalform" cols="20" rows="2" class="txtarea" id="txtreferalform"><? if($lrow['referalform'] != '0') echo $lrow['referalform']; ?></textarea></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="right">Features:</td>
                  <td colspan="5"><label>
                    <input tabindex="22" name="txtadultfriendly" type="checkbox" id="txtadultfriendly" value="1" <? if($lrow['adultfriendly'] == 1){?> checked="checked" <? }?>/>
                    Adult Friendly
                    <input tabindex="22" name="txtpool" type="checkbox" id="txtpool" value="1" <? if($lrow['pool'] == 1){?> checked="checked" <? }?>/>
                    Pool
                    <input tabindex="23" name="txttennisecourt" type="checkbox" id="txttennisecourt" value="1" <? if($lrow['tennisecourt'] == 1){?> checked="checked" <? }?>/>
                    Tennis Court
                    <input tabindex="24" name="txtcityview" type="checkbox" id="txtcityview" value="1" <? if($lrow['cityview'] == 1){?> checked="checked" <? }?>/>
                    City View
                    <input tabindex="25" name="txtnicebackyard" type="checkbox" id="txtnicebackyard" value="1" <? if($lrow['nicebackyard'] == 1){?> checked="checked" <? }?>/>
                    Nice Backyard
                    <input tabindex="26" name="txthomeassociation" type="checkbox" id="txthomeassociation" value="1" <? if($lrow['homeassociation'] == 1){?> checked="checked" <? }?>/>
                    Home Association</label></td>
                  </tr>
                <tr>
                  <td align="right">Architect:</td>
                  <td><input tabindex="27" name="txtarchitect" type="text" class="form" id="txtarchitect" maxlength="255" value="<?php	                                       				if($lrow['architect'] != '0') echo $lrow['architect']; ?>"/></td>
                  <td align="right">House Name: </td>
                  <td><input tabindex="28" name="txthousename" type="text" class="form" id="txthousename" maxlength="255" value="<?php	                                       				if($lrow['housename'] != '0') echo $lrow['housename']; ?>"/></td>
                  <td colspan="2">&nbsp;</td>
                  </tr>
                <tr>
                  <td align="right">&nbsp;</td>
                  <td colspan="5"><label>
				  
                    <input tabindex="29" name="txtnewpropertyowner" type="checkbox" id="txtnewpropertyowner" value="1" <? if($lrow['newpropertyowner'] == '1'){?> checked="checked" <? }?>/>
                    Find New Property Owner
                    <input tabindex="30" name="txtfilmingorevents" type="checkbox" id="txtfilmingorevents" value="1" <? if($lrow['filmingorevents'] == '1'){?> checked="checked" <? }?>/>
                    No Longer For Filming or Events</label></td>
                  </tr>
                <tr align="left">
                  <td colspan="6"><div id="notes1">
				  <? 
				  	$type = 1;
					$property_id = $prow['id'];
				  ?>
				  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview">
                    <tr bgcolor="#D0E1F4" height="20">
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="3" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a"> Notes&nbsp;&nbsp; </div></td>
                      <td width="11%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a">User&nbsp;&nbsp; </div></td>
                      <td width="14%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a">Date Modified&nbsp;&nbsp; </div></td>
                      <td width="10%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><span class="a">Action</span>&nbsp;&nbsp; <a href="#" onclick="window.open('notes.php?property_id=<?=$prow['id']?>&amp;type=<?=$type?>&amp;op=1','noteswin','height=190,width=310,scrollbars=0');return false;" class="n">Add New</a> </td>
                      <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
                    <? 
						$sqlnotes = "SELECT * FROM notes WHERE type=".$type." and property_id=".$prow['id'];
						$nresult = $db->query($sqlnotes) or die(mysql_error()." ".$sqlnotes);
						if($db->getNumRows($nresult) >= 1){
						while($nrow = $db->fetchArray($nresult)){
							if($nrow['userid'] == '')
								$nrow['userid'] = 1;
					?>
                    <tr height="20">
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="3" nowrap="nowrap" class="listViewThS1" scope="col"><?php	                                       				if($nrow['text'] != '0') echo $nrow['text']; ?></td>
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><?=ReturnName('user','username','id',$nrow['userid'])?></td>
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><?=date("m/d/Y",strtotime($nrow['datentime']))?></td>
                      <td width="10%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><a href="#" onclick="window.open('notes.php?id=<?=$nrow['id']?>&amp;property_id=<?=$prow['id']?>&amp;type=<?=$type?>&amp;op=2','noteswin','height=190,width=310,scrollbars=0');return false;" class="n">Edit</a> | <a href="#" onclick="if(confirm('Are you sure want to delete this notes?')){deleteNotes('<?=$nrow['id']?>','<?=$type?>','<?=$property_id?>');return false;}else{return false};" class="n">Delete</a> </td>
                      <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
                    <? }
					}else{?>
                    <tr height="20">
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="5" nowrap="nowrap" class="listViewThS1" scope="col">No record exists.</td>
                      <td width="10%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><a href="#" onclick="window.open('notes.php?property_id=<?=$prow['id']?>&amp;type=<?=$type?>&amp;op=1','noteswin','height=190,width=310,scrollbars=0');return false;" class="n"></a> </td>
                      <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
                    <? }?>
                  </table>
                  </div></td>
                  </tr><input name="location_id" type="hidden" value="<?=$prow['id']?>" />
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
            <td height="35" align="left" valign="middle"><input tabindex="31" name="Button2" type="button" class="h" value="Add Budget Information" onclick="addBudget();" style="height:25px"/></td>
            <td height="35" align="left" valign="top">&nbsp;</td>
            <td height="35" align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" align="left" valign="top"><div id="addBudget" <? if(!is_array($brow) && empty($brow)){?>style="display:none"<? }?>>
              <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formtxt">
                <tr>
                  <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="10%">&nbsp;</td>
                      <td width="90%"><label>
                        <input tabindex="31" name="txthighbudget" type="checkbox" id="txthighbudget" value="1" <? if($brow['highbudget'] == 1){?> checked="checked" <? }?>/>
                        High Budget
                        <input tabindex="32" name="txtmediumbudget" type="checkbox" id="txtmediumbudget" value="1" <? if($brow['mediumbudget'] == 1){?> checked="checked" <? }?>/>
                        Mid Budget
                        <input tabindex="33" name="txtlowbudget" type="checkbox" id="txtlowbudget" value="1" <? if($brow['lowbudget'] == 1){?> checked="checked" <? }?>/>
                        Low Budget</label></td>
                    </tr>
                  </table></td>
                  </tr>
                <tr>
                  <td width="16%" align="right">Budget Notes :</td>
                  <td colspan="5"><textarea tabindex="34" name="txtbudgetnotes" cols="45" rows="4" class="txtarea" id="txtbudgetnotes"><?php	                                       				if($brow['budgetnotes'] != '0') echo $brow['budgetnotes']; ?></textarea></td>
                  </tr>
                <tr>
                  <td colspan="6"><strong>Film Information </strong></td>
                  </tr>
                <!--<tr>
                  <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="10%">&nbsp;</td>
                      <td width="90%"><label>
                        <input name="txtfilmfriendly" type="checkbox" id="txtfilmfriendly" value="1" <? if($brow['filmfriendly'] == 1){?> checked="checked" <? }?>/>
                        Film Friendly
                        <input name="txtinteriorfilming" type="checkbox" id="txtinteriorfilming" value="1" <? if($brow['interiorfilming'] == 1){?> checked="checked" <? }?>/>
                        Interior Filming
                        <input name="txtexteriorfilming" type="checkbox" id="txtexteriorfilming" value="1" <? if($brow['exteriorfilming'] == 1){?> checked="checked" <? }?>/>
                        Exterior Filming </label></td>
                    </tr>
                  </table></td>
                  </tr>-->

                <tr>
                  <td  width="100px" align="right">Avg. Film Budget :</td>
                  <td width="50px"><input tabindex="35" name="txtfilmbudget" type="text" class="form" id="txtfilmbudget" maxlength="255" value="<?php	                                       				if($brow['filmbudget'] != '0') echo $brow['filmbudget']; ?>"/></td>
                  <td width="137px" align="right">Max Head Count:</td>
                  <td><input tabindex="36" name="txtfilmmaxheat" type="text" class="form" id="txtfilmmaxheat" maxlength="255" value="<?php	                                       				if($brow['filmmaxheat'] != '0') echo $brow['filmmaxheat']; ?>"/></td>
                  <td width="2%" align="right">Film Parking: </td>
                  <td width="440px"><input tabindex="37" name="txtfilmparking" type="text" class="form" id="txtfilmparking" maxlength="255" value="<?php	                                       				if($brow['filmparking'] != '0') echo $brow['filmparking']; ?>"/></td>
                </tr>
                <tr>
                  <td colspan="6" align="left"><div id="notes2">
				  <? 
				  	$type = 2;
					$property_id = $prow['id'];
				  ?>
				  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview">
                    <tr bgcolor="#D0E1F4" height="20">
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="3" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a"> Notes&nbsp;&nbsp; </div>                        </td>
                      <td width="11%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a">User&nbsp;&nbsp; </div></td>
                      <td width="14%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a">Date Modified&nbsp;&nbsp; </div>                        </td>
                      <td width="10%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><span class="a">Action</span>&nbsp;&nbsp; <a href="#" onclick="window.open('notes.php?property_id=<?=$prow['id']?>&type=<?=$type?>&op=1','noteswin','height=190,width=310,scrollbars=0');return false;" class="n">Add New</a> </td>
                      <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
					<? 
						$sqlnotes = "SELECT * FROM notes WHERE type=".$type." and property_id=".$prow['id'];
						$nresult = $db->query($sqlnotes) or die(mysql_error()." ".$sqlnotes);
						if($db->getNumRows($nresult) >= 1){
						while($nrow = $db->fetchArray($nresult)){
							if($nrow['userid'] == '')
								$nrow['userid'] = 1;
					?>
					<tr height="20">
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="3" nowrap="nowrap" class="listViewThS1" scope="col"><?php	                                       				if($nrow['text'] != '0') echo $nrow['text']; ?></td>
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><?=ReturnName('user','username','id',$nrow['userid'])?></td>
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><?=date("m/d/Y",strtotime($nrow['datentime']))?></td>
                      <td width="10%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"> <a href="#" onclick="window.open('notes.php?id=<?=$nrow['id']?>&property_id=<?=$prow['id']?>&type=<?=$type?>&op=2','noteswin','height=190,width=310,scrollbars=0');return false;" class="n">Edit</a> | <a href="#" onClick="if(confirm('Are you sure want to delete this notes?')){deleteNotes('<?=$nrow['id']?>','<?=$type?>','<?=$property_id?>');return false;}else{return false};" class="n">Delete</a> </td>
                      <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
					<? }
					}else{?>
					<tr height="20">
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="5" nowrap="nowrap" class="listViewThS1" scope="col">No record exists.</td>
                      <td width="10%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"> <a href="#" onclick="window.open('notes.php?property_id=<?=$prow['id']?>&type=<?=$type?>&op=1','noteswin','height=190,width=310,scrollbars=0');return false;" class="n"></a> </td>
                      <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
					<? }?>
				  </table></div></td>
                  </tr>
                <tr>
                  <td colspan="6"><strong>Events Information </strong></td>
                </tr>
                <tr>
                  <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="10%">&nbsp;</td>
                      <td width="90%"><label>
                        <input tabindex="38" name="txteventfriendly" type="checkbox" id="txteventfriendly" value="1" <? if($brow['eventfriendly'] == 1){?> checked="checked" <? }?>/>
                        Event Friendly
                        <input tabindex="39" name="txtinteriorevent" type="checkbox" id="txtinteriorevent" value="1" <? if($brow['interiorevent'] == 1){?> checked="checked" <? }?>/>
                        Interior Events
                        <input tabindex="40" name="txtexteriorevent" type="checkbox" id="txtexteriorevent" value="1" <? if($brow['exteriorevent'] == 1){?> checked="checked" <? }?>/>
                        Exterior Events </label></td>
                    </tr>
                  </table></td>
                  </tr>
                <tr>
                  <td align="right">Avg. Event Budget :</td>
                  <td><input tabindex="41" name="txteventbudget" type="text" class="form" id="txteventbudget" maxlength="255" value="<?php	                                       				if($brow['eventbudget'] != '0') echo $brow['eventbudget']; ?>"/></td>
                  <td align="right">Max Head Count:</td>
                  <td><input tabindex="42" name="txteventmaxheat" type="text" class="form" id="txteventmaxheat" maxlength="255" value="<?php	                                       				if($brow['eventmaxheat'] != '0') echo $brow['eventmaxheat']; ?>"/></td>
                  <td align="right">Event Parking: </td>
                  <td><input tabindex="43" name="txteventparking" type="text" class="form" id="txteventparking" maxlength="255" value="<?php	                                       				if($brow['eventparking'] != '0') echo $brow['eventparking']; ?>"/></td>
                </tr>
                <tr>
                  <td colspan="6" align="left"><div id="notes3">
				  <? 
				  	$type = 3;
					$property_id = $prow['id'];
				  ?>
				  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview">
                    <tr bgcolor="#D0E1F4" height="20">
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="3" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a"> Notes&nbsp;&nbsp; </div>                        </td>
                      <td width="11%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a">User&nbsp;&nbsp; </div></td>
                      <td width="14%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a">Date Modified&nbsp;&nbsp; </div>                        </td>
                      <td width="10%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><span class="a">Action</span>&nbsp;&nbsp; <a href="#" onclick="window.open('notes.php?property_id=<?=$prow['id']?>&type=<?=$type?>&op=1','noteswin','height=190,width=310,scrollbars=0');return false;" class="n">Add New</a> </td>
                      <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
					<? 
						$sqlnotes = "SELECT * FROM notes WHERE type=".$type." and property_id=".$prow['id'];
						$nresult = $db->query($sqlnotes) or die(mysql_error()." ".$sqlnotes);
						if($db->getNumRows($nresult) >= 1){
						while($nrow = $db->fetchArray($nresult)){
							if($nrow['userid'] == '')
								$nrow['userid'] = 1;
					?>
					<tr height="20">
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="3" nowrap="nowrap" class="listViewThS1" scope="col"><?php	                                       				if($nrow['text'] != '0') echo $nrow['text']; ?></td>
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><?=ReturnName('user','username','id',$nrow['userid'])?></td>
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><?=date("m/d/Y",strtotime($nrow['datentime']))?></td>
                      <td width="10%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"> <a href="#" onclick="window.open('notes.php?id=<?=$nrow['id']?>&property_id=<?=$prow['id']?>&type=<?=$type?>&op=2','noteswin','height=190,width=310,scrollbars=0');return false;" class="n">Edit</a> | <a href="#" onClick="if(confirm('Are you sure want to delete this notes?')){deleteNotes('<?=$nrow['id']?>','<?=$type?>','<?=$property_id?>');return false;}else{return false};" class="n">Delete</a> </td>
                      <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
					<? }
					}else{?>
					<tr height="20">
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="5" nowrap="nowrap" class="listViewThS1" scope="col">No record exists.</td>
                      <td width="10%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"> <a href="#" onclick="window.open('notes.php?property_id=<?=$prow['id']?>&type=<?=$type?>&op=1','noteswin','height=190,width=310,scrollbars=0');return false;" class="n"></a> </td>
                      <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
					<? }?>
				  </table></div></td>
                  </tr><input name="budget_id" type="hidden" value="<?=$brow['id']?>" />
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
            <td height="35" align="left" valign="middle"><input tabindex="44" name="Button3" type="button" class="h" value="Add Tax Information" onclick="addTax();" style="height:25px"/></td>
            <td height="35" align="left" valign="top">&nbsp;</td>
            <td height="35" align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" align="left" valign="top"><div id="addTax" <? if(!is_array($trow) && empty($trow)){?>style="display:none"<? }?>>
              <table width="80%" border="0" cellpadding="2" cellspacing="0" class="formtxt">
                <tr>
                  <td align="right">Tax Company:</td>
                  <td><input tabindex="44" name="txttaxcompany" type="text" class="form" id="txttaxcompany" maxlength="255" value="<?php	                                       				if($trow['company'] != '0') echo $trow['company']; ?>"/></td>
                  <td align="right">Taxt First Name: </td>
                  <td><input tabindex="49" name="txttaxfirstname" type="text" class="form" id="txttaxfirstname" maxlength="255" value="<?php	                                       				if($trow['firstname'] != '0') echo $trow['firstname']; ?>"/></td>
                  <td align="right">Tax Last Name: </td>
                  <td><input tabindex="53" name="txttaxlastname" type="text" class="form" id="txttaxlastname" maxlength="255" value="<?php	                                       				if($trow['lastname'] != '0') echo $trow['lastname']; ?>"/></td>
                </tr>
                <tr>
                  <td align="right">Tax Address:</td>
                  <td><input tabindex="45" name="txttaxaddress" type="text" class="form" id="txttaxaddress" maxlength="255" value="<?php	                                       				if($trow['address'] != '0') echo $trow['address']; ?>"/></td>
                  <td align="right">Tax Address2:</td>
                  <td><input tabindex="50" name="txttaxaddress2" type="text" class="form" id="txttaxaddress2" maxlength="255" value="<?php	                                       				if($trow['address2'] != '0') echo $trow['address2']; ?>"/></td>
                  <td align="right">Tax City: </td>
                  <td><input tabindex="54" name="txttaxcity" type="text" class="form" id="txttaxcity" maxlength="255" value="<?php	                                       				if($trow['city'] != '0') echo $trow['city']; ?>"/></td>
                </tr>
                <tr>
                  <td align="right">Tax State:</td>
                  <td><input tabindex="46" name="txttaxstate" type="text" class="form" id="txttaxstate" maxlength="255" value="<?php	                                       				if($trow['state'] != '0') echo $trow['state']; ?>"/></td>
                  <td align="right">Tax Zipcode:</td>
                  <td><input tabindex="51" name="txttaxzipcode" type="text" class="form" id="txttaxzipcode" maxlength="255" value="<?php	                                       				if($trow['zipcode'] != '0') echo $trow['zipcode']; ?>"/></td>
                  <td align="right">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="right">Tax Amount:</td>
                  <td colspan="5"><label>$ 
                    <input tabindex="47" name="txttaxamount" type="text" class="bigValue" id="txttaxamount" maxlength="255" value="<?php	                                       				if($trow['amount'] != '0') echo $trow['amount']; ?>"/>
                  </label></td>
                </tr>
                <tr>
                  <td align="right">Tax Notes1:</td>
                  <td><input tabindex="48" name="txttaxnotes1" type="text" class="form" id="txttaxnotes1" maxlength="255" value="<?php	                                       				if($trow['notes1'] != '0') echo $trow['notes1']; ?>"/></td>
                  <td align="right">Tax Notes2:  </td>
                  <td colspan="3"><input tabindex="52" name="txttaxnotes2" type="text" class="form" id="txttaxnotes2" maxlength="255" value="<?php	                                       				if($trow['notes2'] != '0') echo $trow['notes2']; ?>"/></td>
                  </tr>
                <tr>
                  <td align="right">&nbsp;</td>
                  <td colspan="5"><label>
                    <input tabindex="55" name="txtmailtaxes" type="checkbox" id="txtmailtaxes" value="1" <? if($trow['mailtaxes'] == 1){?> checked="checked" <? }?>/>
                    Do Not Mail Taxes
                    <input tabindex="55" name="txtquestionable" type="checkbox" id="txtquestionable" value="1" <? if($trow['questionable'] == 1){?> checked="checked" <? }?>/>
                    Questionable</label></td>
                </tr><input tabindex="56" name="tax_id" type="hidden" value="<?=$trow['id']?>" />

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
                    <input name="hdnproperty" type="hidden" id="hdnproperty" value="1" /><input tabindex="57" name="addProperty" type="submit" class="button" id="addProperty" value="Save Property" />
                  </span></span></td>
                  <td width="49%" align="left" valign="top"><input tabindex="58" name="Button" type="button" class="button" value="Cancel" onclick="history.back();"/></td>
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

<link rel="stylesheet" href="css/base/jquery.ui.all.css" type="text/css">
<script type="text/javascript" src="js/jquery-ui-1.8.custom.js"></script>
<script type="text/javascript" src="js/jquery.ui.autocomplete.js"></script>

<script type="text/javascript">
 jQuery(document).ready(function() {
	jQuery(".cb1").click(function () { 
		jQuery.post("updateCbField.php", { 
				id: jQuery(this).attr("id"),
				checked: jQuery(this).attr("checked")
			},function(data){
				//alert(data);
		    });
	});
	jQuery('.phone').live("keypress", function(event){
		var key = window.event ? event.keyCode : event.which;
		if(key != 8){
			var number = jQuery(this).val();
			number = jQuery.trim(number);
			var count = number.length;
			var first = number.substring(0, 1);
			if(first != "+"){ //usa format
				if(count < 14){
					if(count == 3){
						number = "(" + number + ") ";
					}
					if(count == 9){
						number = number + "-";
					}
					jQuery(this).val(number);
				}else{
					jQuery(this).val(number.slice(0, -1));
				}
			}else{ //international
				
			}
		}
	});
	jQuery('.phone').live("blur", function(event){
		var number = jQuery(this).val();
		number = jQuery.trim(number);
		var count = number.length;
		var first = number.substring(0, 1);
		if(first != "+"){ //usa format
			if(count == 10){
				first = number.substring(0, 3);
				second = number.substring(3, 6);
				third = number.substring(6, 10);
				number = "(" + first + ") " + second + "-" + third;
				jQuery(this).val(number);
			}
		}
	});
	jQuery("#getCity").click(function () { 
		if(jQuery("#txtzipcode").val() != ""){
			jQuery.post("ajaxV.php", { 
					zip: jQuery("#txtzipcode").val(),
					task: "getCity"
				},function(data){
					if(data != ""){
						jQuery("#infoZip").html("")
						arrData = data.split("|");
						jQuery("#listCities").html("Select city: <br />");
						if(arrData.length == 2){
							jQuery("#txtcity").val(arrData[0]);
						}else{
							jQuery("#listCities").show();
							for(var i = 0; i < arrData.length - 1; i++){
								jQuery("#listCities").append("<div class='clickCity formtxt' style='cursor: pointer'>"+arrData[i]+"</div>");
							}
						}
						
						jQuery.post("ajaxV.php", { 
								zip: jQuery("#txtzipcode").val(),
								task: "getState"
							},function(data){
								jQuery("#txtstate :selected").text(data);
						});
					}else{
						jQuery("#infoZip").html("Invalid zipcode.")
					}
			});
		}
	});
	jQuery('#txtzipcode').keypress(validateNumber);
	jQuery(".clickCity").live("click",function () { 
		jQuery("#txtcity").val(jQuery(this).html());
		jQuery("#listCities").hide();
	});
	
	jQuery("#txtcounty").autocomplete({
		source: "autocompleteCounty.php",
		minLength: 4
	});
})
</script>
