<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
	if(isset($_REQUEST['hdncontact']) && $_REQUEST['hdncontact'] == '1'){
		$success = array();
		$create_date = strtotime(date("Y-m-d H:i:s"));
		if($_REQUEST['txtfname'] == '') { $txtfname = '0'; }
		if($_REQUEST['txtlname'] == '') { $txtlname = '0'; }
		if($_REQUEST['txttitle'] == '') { $txttitle = '0'; }
		if($_REQUEST['txtspousename'] == '') { $txtspousename = '0'; }
		if($_REQUEST['txtemail'] == '') { $txtemail = '0'; }
		if($_REQUEST['txtwebsite'] == '') { $txtwebsite = '0'; }
		if($_REQUEST['txtrelation'] == '') { $txtrelation = '0'; }
		if($_REQUEST['txtnotes'] == '') { $txtnotes = '0'; }
		
		$sql = "INSERT INTO contact (userid,firstname,lastname,title,spousename,email,website,relationship,notes,create_date) VALUES(
		'".$_SESSION['userid']."','".addslashes($txtfname)."','".addslashes($txtlname)."','".addslashes($txttitle)."','".addslashes($txtspousename)."','".addslashes($txtemail)."', '".addslashes($txtwebsite)."','".addslashes($txtrelation)."','".addslashes($txtnotes)."','".$create_date."')";
		if($db->query($sql)){
			array_push($success, "1");
		}else{
			array_push($success, "0");
			echo mysql_error();
		}
		$contactid = mysql_insert_id();
		$phone = $_REQUEST['txtphone'];
		foreach($phone as $key => $value){
			if($value != ''){
				if($_REQUEST['txtext'][$key] == '') { $txtext[$key] = '0'; }
				if($_REQUEST['txttype'][$key] == '') { $txttype[$key] = '0'; }
				if($_REQUEST['txtpriority'][$key] == '') { $txtpriority[$key] = '0'; }
				
				$sql = "INSERT INTO phone (contact_id,number,ext,type,priority) VALUES(
				'".$contactid."','".addslashes($value)."','".addslashes($txtext[$key])."','".addslashes($txttype[$key])."','".addslashes($txtpriority[$key])."')";
				if($db->query($sql)){
					array_push($success, "1");
				}else{
					array_push($success, "0");
					echo mysql_error();
				}
			}		
		}
		$val = 1;
		for ($i = 0; $i < count($successfulSubmit); $i++){
			if($successfulSubmit[$i] == '0'){
				$val = 0;
			}
		}
		if($val == 0){
			header("Location: contact.php?success=0");
		}else{
			header("Location: contact.php?success=1");
		}
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CRM</title>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
<script>
function addPhonetypes(){ 
	d_html = document.getElementById("phonetype").innerHTML;
	main_html = document.getElementById("phone").innerHTML;
	document.getElementById("phone").innerHTML = main_html + d_html;
}
function verifcreatecontact(){
	/*if(document.getElementById('txtfname').value == ""){
		alert('Please Enter First Name');
		document.getElementById('txtfname').focus();
		return false;
	}
	if(document.getElementById('txtlname').value == ""){
		alert('Please Enter Last Name');
		document.getElementById('txtlname').focus();
		return false;
	}*/
	return true;
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
</script>
</head>

<body>
<? include("header.php"); ?>
<script type="text/javascript">
	jQuery(document).ready(function(){
		//jQuery('.validPhone').live("keypress", validateNumber);
		var counter = 1;
		jQuery('#contactsAddMore').click(function () {
			d_html = document.getElementById("phonetype").innerHTML;
			var newTextBoxDiv = jQuery(document.createElement('div')).attr("id", 'txtphone' + counter);
			newTextBoxDiv.html(d_html);
			newTextBoxDiv.appendTo("#phone");
			counter++;
		});
	});
</script>
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
      <td colspan="4" align="left" valign="top" nowrap="nowrap"><form id="form1" name="form1" method="post" action="" onsubmit="return verifcreatecontact();">
        <table width="62% border="0" cellspacing="0" cellpadding="0">

          <tr>
            <td width="31%" align="left" valign="top"><span class="formtxt">First Name:</span></td>
            <td width="21%" align="left" style="width: 20px;" valign="top">&nbsp;</td>
            <td align="left" valign="top"><span class="formtxt">Last Name:</span></td>
          </tr>
          <tr>
            <td align="left" valign="top"><input tabindex="1" name="txtfname" id="txtfname" type="text" class="form" size="35" maxlength="255" /></td>
            <td align="left" style="width: 30px;" valign="top">&nbsp;</td>
            <td align="left" valign="top"><input tabindex="5" name="txtlname" id="txtlname" type="text" class="form" size="35" maxlength="255" /></td>
          </tr>
          <tr>
            <td align="left" valign="top"><span class="formtxt">Title:</span></td>
            <td align="left" style="width: 20px;" valign="top">&nbsp;</td>
            <td align="left" valign="top"><span class="formtxt">Spouse Name: </span></td>
          </tr>
          <tr>
            <td align="left" valign="top"><input tabindex="2" name="txttitle" id="txttitle" type="text" class="form" value="" size="35" maxlength="255"/></td>
            <td align="left" style="width: 20px;" valign="top">&nbsp;</td>
            <td align="left" valign="top"><input tabindex="6" name="txtspousename" type="text" class="form" id="txtspousename" size="35" maxlength="255" /></td>
          </tr>
          <tr>
            <td align="left" valign="top"><span class="formtxt">Email:</span></td>
            <td align="left" style="width: 20px;" valign="top">&nbsp;</td>
            <td align="left" valign="top"><span class="formtxt">Website:</span></td>
          </tr>
          <tr>
            <td align="left" valign="top"><input tabindex="3" name="txtemail" type="text" class="form" id="txtemail" size="35" maxlength="255" /></td>
            <td align="left" style="width: 20px;" valign="top">&nbsp;</td>
            <td align="left" valign="top"><input tabindex="7" name="txtwebsite" id="txtwebsite" type="text" class="form" size="35" maxlength="255" /></td>
          </tr>

          <tr>
            <td align="left" valign="top"><span class="formtxt">Relationship:</span></td>
            <td align="left" style="width: 20px;" valign="top" class="formtxt">&nbsp;</td>
            <td align="left" style="width: 20px;" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" valign="top" tabindex="4"><?=listbox_array($relationshipArray,'txtrelation')?></td>
            <td align="left" style="width: 20px;" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" valign="top"><span class="formtxt">Notes:</span></td>
            <td align="left" style="width: 20px;" valign="top">&nbsp;</td>
            <td align="left" style="width: 20px;" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" align="left" valign="top"><textarea tabindex="8" name="txtnotes" id="txtnotes" cols="80" rows="4" class="txtarea"></textarea>            </td>
          </tr>
          <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" align="left" valign="top"><div id="phone"><div id="phonetype">
			<table width="74%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
                <td width="33%" align="left" valign="top" class="formtxt">Phone: (format: 1234567890)</td>
                <td width="29%" align="left" valign="top" class="formtxt">Ext:</td>
                <td width="20%" align="left" valign="top" class="formtxt">Type:</td>
                <td width="18%" align="left" valign="top" class="formtxt">Priority:</td>
              </tr>
              <tr>
                <td align="left" valign="top"><input tabindex="9" name="txtphone[]" type="text" class="form phone" id="txtphone[]" size="22" maxlength="50" /></td>
                <td align="left" valign="top"><input tabindex="10" name="txtext[]" type="text" class="form" id="txtext[]" size="20" maxlength="10" /></td>
                <td align="left" valign="top" tabindex="11"><?=fillCombo('txttype[]','types','id','name','WHERE active=1 AND (id = 1 OR id = 12 OR id = 23)','','dropdown','')?></td>
                <td align="left" valign="top" tabindex="12"><?=listbox_array($priorityArray,'txtpriority[]')?>                </td>
              </tr>
            </table></div></div></td>
            </tr>
          <tr>
            <td height="35" align="left" valign="top">&nbsp;</td>
            <td height="35" align="left" valign="top">&nbsp;</td>
            <td height="35" align="center" valign="middle"><input id="contactsAddMore" tabindex="13" name="Button" type="button" class="button" value="Add More" /></td>
          </tr>
          <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" align="left" valign="top"><table width="60%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="7%" align="left" valign="top">&nbsp;</td>
                  <td width="36%" align="left" valign="top"><span class="dataField"><span class="dataLabel" style="display: inline;">
                    <input name="hdncontact" type="hidden" value="1" /><input tabindex="14" class="button" value="Create Contact" name="createContact" type="submit" />
                  </span></span></td>
                  <td width="50%" align="left" valign="top"><input tabindex="15" name="Button2" type="button" class="button" value="Cancel" onclick="history.back();"/></td>
                  <td width="7%" align="left" valign="top">&nbsp;</td>
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
<? include("footer.php");?>
</body>
</html>

<script type="text/javascript">
jQuery(document).ready(function() {
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
});
</script>
