<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
	$id = (int)$_REQUEST['id'];
	if(isset($_REQUEST['hdncontact']) && $_REQUEST['hdncontact'] == '1'){
		$create_date = strtotime(date("Y-m-d H:i:s"));
		$id = (int)$_REQUEST['id'];
		
		if($_REQUEST['txtfname'] == '') { $txtfname = '0'; }
		if($_REQUEST['txtlname'] == '') { $txtlname = '0'; }
		if($_REQUEST['txttitle'] == '') { $txttitle = '0'; }
		if($_REQUEST['txtspousename'] == '') { $txtspousename = '0'; }
		if($_REQUEST['txtemail'] == '') { $txtemail = '0'; }
		if($_REQUEST['txtwebsite'] == '') { $txtwebsite = '0'; }
		if($_REQUEST['txtrelation'] == '') { $txtrelation = '0'; }
		if($_REQUEST['txtnotes'] == '') { $txtnotes = '0'; }
		
		$sql = "UPDATE contact SET userid=".$_SESSION['userid'].",firstname='".addslashes($txtfname)."',lastname='".addslashes($txtlname)."',title='".addslashes($txttitle)."',spousename='".addslashes($txtspousename)."',email='".addslashes($txtemail)."',website='".addslashes($txtwebsite)."',relationship='".addslashes($txtrelation)."',notes='".addslashes($txtnotes)."',modified_date='".$create_date."' WHERE id=".$id;
		$db->query($sql) or die($sql);		
		$contactid = mysql_insert_id();
		$phone = $_REQUEST['phoneid'];
		foreach($phone as $key => $value){
			
			if($_REQUEST['txtphone'][$key] == '') { $txtphone[$key] = '0'; }
			if($_REQUEST['txtext'][$key] == '') { $txtext[$key] = '0'; }
			if($_REQUEST['txttype'][$key] == '') { $txttype[$key] = '0'; }
			if($_REQUEST['txtpriority'][$key] == '') { $txtpriority[$key] = '0'; }
			
			if($value != ''){
				$sql = "UPDATE phone SET number='".addslashes($txtphone[$key])."',ext='".addslashes($txtext[$key])."',type='".addslashes($txttype[$key])."',priority='".addslashes($txtpriority[$key])."' WHERE id=".(int)$value;
				$db->query($sql);
			}		
		}
		header("Location: contact.php");
	}
	$sql = "SELECT * FROM contact WHERE id=".$id;
	$res = $db->query($sql) or die($sql);
	$crow = $db->fetchArray($res);
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
	if(document.getElementById('txtfname').value == ""){
		alert('Please Enter First Name');
		document.getElementById('txtfname').focus();
		return false;
	}
	if(document.getElementById('txtlname').value == ""){
		alert('Please Enter Last Name');
		document.getElementById('txtlname').focus();
		return false;
	}
	return true;
}
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
            <td width="100%" align="left" valign="top" class="lastview"><b style="white-space: nowrap;">Edit Contact </b> </td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td align="left" valign="top" class="mar"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="4" align="left" valign="top" nowrap="nowrap"><form id="form1" name="form1" method="post" action="" onsubmit="return verifcreatecontact();">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">

          <tr>
            <td width="31%" align="left" valign="top"><span class="formtxt">First Name:</span></td>
            <td width="21%" align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><span class="formtxt">Last Name:</span></td>
          </tr>
          <tr>
            <td align="left" valign="top"><input name="txtfname" id="txtfname" type="text" class="form" size="35" maxlength="255" value="<?php	                                       					                                       			 if($crow['firstname'] != '0') echo $crow['firstname']; ?>"/></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><input name="txtlname" id="txtlname" type="text" class="form" size="35" maxlength="255" value="<?php	                                       					                                       			 if($crow['lastname'] != '0') echo $crow['lastname']; ?>"/></td>
          </tr>
          <tr>
            <td align="left" valign="top"><span class="formtxt">Title:</span></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><span class="formtxt">Spouse Name: </span></td>
          </tr>
          <tr>
            <td align="left" valign="top"><input name="txttitle" id="txttitle" type="text" class="form" size="35" maxlength="255" value="<?php	                                       					                                       			 if($crow['title'] != '0') echo $crow['title']; ?>"/></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><input name="txtspousename" type="text" class="form" iid="txtspousename" ize="35" maxlength="255" value="<?php	                                       					                                       			 if($crow['spousename'] != '0') echo $crow['spousename']; ?>"/></td>
          </tr>
          <tr>
            <td align="left" valign="top"><span class="formtxt">Email:</span></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><span class="formtxt">Website:</span></td>
          </tr>
          <tr>
            <td align="left" valign="top"><input name="txtemail" type="text" class="form" id="txtemail" size="35" maxlength="255" value="<?php	                                       					                                       			 if($crow['email'] != '0') echo $crow['email']; ?>"/></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><input name="txtwebsite" id="txtwebsite" type="text" class="form" size="35" maxlength="255" value="<?php	                                       					                                       			 if($crow['website'] != '0') echo $crow['website']; ?>"/></td>
          </tr>

          <tr>
            <td align="left" valign="top"><span class="formtxt">Relationship:</span></td>
            <td align="left" valign="top" class="formtxt">:</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" valign="top"><?=listbox_array($relationshipArray,'txtrelation',$relationshipArray[$crow['relationship']])?></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" valign="top"><span class="formtxt">Notes:</span></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" align="left" valign="top"><textarea name="txtnotes" id="txtnotes" cols="80" rows="4" class="txtarea"><?php	                                       					                                       			 if($crow['notes'] != '0') echo $crow['notes']; ?></textarea>            </td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" align="left" valign="top"><div id="phone"><div id="phonetype"><table width="99%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
                <td width="33%" align="left" valign="top" class="formtxt">Phone: (format: 1234567890)</td>
                <td width="29%" align="left" valign="top" class="formtxt">Ext:</td>
                <td width="20%" align="left" valign="top" class="formtxt">Type:</td>
                <td width="18%" align="left" valign="top" class="formtxt">Priority:</td>
              </tr>
			  <?
			  	$sql_query_phone = "SELECT * FROM phone WHERE contact_id=".$crow['id']." ORDER BY id";
				$pres = $db->query($sql_query_phone) or die($sql_query_phone);
				while($prow = $db->fetchArray($pres)){
			  ?>
              <tr>
                <td align="left" valign="top"><input name="txtphone[]" type="text" class="form phone" id="txtphone[]" size="35" maxlength="50" value="<?php	                                       					                                       			 if($prow['number'] != '0') echo $prow['number']; ?>"/></td>
                <td align="left" valign="top"><input name="txtext[]" type="text" class="form" id="txtext[]" size="20" maxlength="10" value="<?php	                                       					                                       			 if($prow['ext'] !='0') echo $prow['ext']; ?>"/></td>
                <td align="left" valign="top"><?=fillCombo('txttype[]','types','id','name','WHERE active=1',$prow['type'],'dropdown','')?></td>
                <td align="left" valign="top"><?=listbox_array($priorityArray,'txtpriority[]',$prow['priority'])?>                </td>
              </tr><input name="phoneid[]" type="hidden" value="<?=$prow['id']?>" />
			  <? }?>
            </table></div></div></td>
            </tr>
          <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" align="left" valign="top"><table width="60%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="6%" align="left" valign="top">&nbsp;</td>
                  <td width="37%" align="left" valign="top"><span class="dataField"><span class="dataLabel" style="display: inline;">
                    <input name="id" type="hidden" value="<?=$crow['id']?>" /><input name="hdncontact" type="hidden" value="1" /><input class="button" value="Save Contact" name="createContact" type="submit" />
                  </span></span></td>
                  <td width="50%" align="left" valign="top"><input name="Button" type="button" class="button" value="Cancel" onclick="history.back();"/></td>
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