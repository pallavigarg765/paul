<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
	mysql_select_db("planitlo_crm");
	$edit_id = '';
	if(isset($_GET['edit_id']) && $_GET['edit_id']!="")
	{
		$edit_id = $_GET['edit_id'];
		$sql = "SELECT * FROM pdf_deal_memo_demo WHERE id=".$edit_id;
		$formres = $db->query($sql) or die($sql);
		$formdata = $db->fetchArray($formres);

		if (!isset($_GET['property_id']) && isset($formdata['property_id']) && $formdata['property_id'] != "") {
			$sqlEditProperty = "SELECT property_id FROM property WHERE id='".$formdata['property_id']."'";
			$presultEditProperty = $db->query($sqlEditProperty) or die($sqlEditProperty);
			$presultEditPropertyRow = $db->fetchArray($presultEditProperty);

			if ($presultEditPropertyRow) {
				$_GET['property_id'] = $presultEditPropertyRow['property_id'];
			}
		}
	}

	$ownerName = '';
	$address = '';
	$propertyID = null;
	$lat = '';
	$long = '';

	if(isset($_GET['property_id']) && $_GET['property_id']!="")
	{
		$sql = "SELECT property.id, property.property_id , property.zipcode, property.property_id, property.company,property.city,contact.id as cid,
		contact.firstname,contact.lastname FROM property left join contact_to_property on contact_to_property.property_id=property.id left join contact on  contact_to_property.contact_id=contact.id WHERE property.property_id='".$_GET['property_id']."'";
		$presult = $db->query($sql) or die($sql);
		$propertyRow = $db->fetchArray($presult);
		
		if ($propertyRow) {
			$propertyID = $propertyRow['id'];
			if (isset($_GET['property_id']) && (is_null($propertyID) || $propertyID == "")) {
				$propertyID = $_GET['property_id'];
			}
			$ownerName = $propertyRow['firstname'].' '.$propertyRow['lastname'];
			$contactSql = "SELECT property.*,state.name as statename, county_new.name as countyname, country.printable_name as countryname FROM property  
				left join state on property.state=state.id
				left join country on property.country=country.id
				left join county_new on property.county=county_new.id 
				WHERE property.property_id='".$_GET['property_id']."'";

			$cpresult = $db->query($contactSql) or die($contactSql);
			$contactRow = $db->fetchArray($cpresult);
			
			if ($contactRow) {
				$contactArr = array();
				if ($contactRow['lat']!="" && $contactRow['lat']!='0') {
					$lat=$contactRow['lat'];
				}
				if ($contactRow['lng']!="" && $contactRow['lng']!='0') {
					$long=$contactRow['lng'];
				}
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
				
				
				if (!empty($contactArr)) {
					$address = implode(', ', $contactArr);
					if ($contactRow['zipcode']!="" && $contactRow['zipcode']!='0') {
						$address .= ' '.$contactRow['zipcode'];
					}
				}
			}

		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta charset="UTF-8">
<title>CRM</title>
<link href="css/crmstyle.css?rel=23234" rel="stylesheet" type="text/css" />
<style type="text/css">
	.pdf_text
	{
		width: 100%;
		border: 0;
		outline: 0;
		border-bottom: 1px solid black;
	}
</style>
<script type="text/javascript" src="js/jquery.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body>
<? include("header.php"); ?>
<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="4" align="left" valign="top" nowrap="nowrap"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
        <tbody>
          <tr>
            <td width="100%" align="left" valign="top" class="lastview">Deal Memo </td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td height="400" align="left" valign="top" class="mar">

                    
                    	
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr>
                          	<td align="left" valign="top" class="moduleTitle">
								<div style="text-align: right;display: flex;gap: 5px;margin-bottom: 10px;width:550px;justify-content: space-between;">
									<div><?php echo $edit_id!="" ? 'Edit' : 'Create'; ?> Deal Memo</div>
									<div class="flex">
										<form action="pdf_deal_memo_demo.php" method="GET">
										<?php  if(isset($_GET['edit_id']) && $_GET['edit_id']!="") { ?>
										<input name="edit_id" type="hidden" value="<?php echo $_GET['edit_id']; ?>"/>
										<?php } ?>
										<input name="property_id" type="text" value="<?php echo isset($_GET['property_id']) ? $_GET['property_id'] : ''; ?>" class="form" id="property_id" onkeyup="ajax_showOptions(this, 'id', event, false);"/>
										<button type="submit" class="button" style="font-size: 10px;">Populate Property Info</button>
										<?php if(isset($_GET['property_id'])) { ?>
											<a target="_blank" href="/crm/propertydetail.php?id=<?php echo $propertyID; ?>" class="a-button" style="font-size: 10px;">Look ID</a>
										<?php } ?>
										</form>
									</div>
								</div>
							</td>
                          <td align="right" valign="top">&nbsp;</td>
                        </tr>
						<form id="form1" name="form1" method="post" action="process_pdf_deal_memo_demo.php">
						<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
							<input type="hidden" value="<?php echo $propertyID; ?>" name="selected_property_id">
                      <tr>
                          <td colspan="2" align="left" valign="top"><div id="pidlist">
                              <table width="700" border="0" cellpadding="5" cellspacing="0" class="listViewThS1" style="border: 1px solid #c9c9c9;padding: 9px;">
                              	<tr>
                              		<td>
								<table width="100%">
									<tbody>
										<tr>
											<td align="center" style="font-size:12px" width="100%"><img src="images/planit-logo.png" height="30px" width="235px"/><br/>
									Office (818) 376-6506
									<br/>
									sales@planitlocations.com
									<br/>
									www.planitlocations.com
									</td>
									</tr>
									<tr>
										<td align="center" style="font-size: 20px;color: #000"><br><b>Deal Memo</b><br><br></td>
									</tr>
									</tbody>
								</table>
								<table width="100%" cellpadding="5">
	<tbody>
	<tr>
		<td  valign="bottom" width="10%"><strong>Today's Date:</strong></td>
	
		<td width="90%"><input name="today_date" value="<?php echo isset($formdata) ? $formdata['today_date'] : date('M d, Y'); ?>" type="text" class="pdf_text datepick" ></td>
	</tr>
	<tr>
		<td  width="10%"><strong>Project Type:</strong></td>
	
		<td width="90%"><input name="project_type" value="<?php echo isset($formdata) ? stripslashes($formdata['project_type']) : ''; ?>" type="text" class="pdf_text" ></td>
	</tr>
	<tr>
		<td  width="15%"><strong>Producer / Event Organizer: </strong></td>
		<td width="85%"><input name="producer" value="<?php echo isset($formdata) ? stripslashes($formdata['producer']) : ''; ?>" type="text" class="pdf_text" ></td>
	</tr>
	<tr>

		<td align="right" width="15%"><strong>Location Owner(s):</strong></td>
		<td width="85%"><input name="location_owner" value="<?php echo isset($formdata) ? stripslashes($formdata['location_owner']) : $ownerName; ?>" type="text" class="pdf_text" ></td>
	</tr>
	<tr>
		<td align="right" width="15%"><strong>Address:</strong></td>
		<td width="85%">
			<input name="address" value="<?php echo isset($formdata) ? stripslashes($formdata['address']) : $address; ?>" type="text" class="pdf_text" >
			<?php
			if ($address != "") {
			?>
				<a style="margin-left: 20px;" class="a-button" href="https://www.google.com/maps/dir//<?php echo urlencode($address) ?>/@<?php echo $lat.','.$long ?>" target="_blank">Map</a>
			<?php } ?>
		</td>
	</tr>

	
<!-- 	<tr>
		<td width="25%"><strong>Location Rental Date:</strong></td>
		<td width="75%"><input name="location_rental_date" value="<?php echo isset($formdata) ? $formdata['location_rental_date'] : ''; ?>" type="text" class="pdf_text" ></td>
	</tr> -->
	<tr>
		<td align="right" width="15%"><strong>Location Rental Date:</strong></td>
		<td width="85%">
			<input name="location_rental_date_1" value="<?php echo isset($formdata) ? $formdata['location_rental_date_1'] : ''; ?>" type="text" class="pdf_text" >
		</td>
	</tr>

	
	<tr>
		<td  width="100%" colspan="2"><br><br>Fees and Commissions Due Owner and Agent Are as Followed:</td>
	</tr>

	<tr>
		<td  width="100%" colspan="2">1. Location Rental Fees</td>
	</tr>

	<tr>
		<td width="20%" align="right"><strong>Net Due To Owner:</strong></td>
		<td width="80%"><input name="net_due_to_owner" value="<?php echo isset($formdata) ? $formdata['net_due_to_owner'] : ''; ?>" type="text" class="pdf_text" ></td>
	</tr>
	<tr>
		<td width="20%" align="right"><strong>Net Due To Plan It Locations:</strong></td>
		<td width="80%"><input name="net_due_pl" value="<?php echo isset($formdata) ? $formdata['net_due_pl'] : ''; ?>" type="text" class="pdf_text" ></td>
	</tr>
	<tr>
		<td style="line-height: 0px;" colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td width="20%" align="right"><strong>Net Due To Owner:</strong></td>
		<td width="80%"><input name="net_to_owner_1" value="<?php echo isset($formdata) ? $formdata['net_to_owner_1'] : ''; ?>" type="text" class="pdf_text" ></td>
	</tr>
	<tr>
		<td width="20%" align="right"><strong>Net Due To Plan It Locations:</strong></td>
		<td width="80%"><input name="net_due_to_pl" value="<?php echo isset($formdata) ? $formdata['net_due_to_pl'] : ''; ?>" type="text" class="pdf_text" ></td>
	</tr>	
	<tr>
		<td style="line-height: 0px;" colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td width="20%" align="right"><strong>Net To Owner:</strong></td>
		<td width="80%"><input name="net_to_owner_2" value="<?php echo isset($formdata) ? $formdata['net_to_owner_2'] : ''; ?>" type="text" class="pdf_text" ></td>
	</tr>
	<tr>
		<td width="20%" align="right"><strong>Net Due To Plan It Locations:</strong></td>
		<td width="80%"><input name="net_due_to_pl_2" value="<?php echo isset($formdata) ? $formdata['net_due_to_pl_2'] : ''; ?>" type="text" class="pdf_text" ></td>
	</tr>
	<tr>
		<td style="line-height: 0px;" colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td width="20%" align="right"><strong>Net To Owner:</strong></td>
		<td width="80%"><input name="net_to_owner_3" value="<?php echo isset($formdata) ? $formdata['net_to_owner_3'] : ''; ?>" type="text" class="pdf_text" ></td>
	</tr>
	<tr>
		<td width="20%" align="right"><strong>Net Due To Plan It Locations:</strong></td>
		<td width="80%"><input name="net_due_to_pl_3" value="<?php echo isset($formdata) ? $formdata['net_due_to_pl_3'] : ''; ?>" type="text" class="pdf_text" ></td>
	</tr>
	<tr>
		<td style="line-height: 0px;" colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td width="20%" align="right"><strong>Net To Owner:</strong></td>
		<td width="80%"><input name="net_to_owner_4" value="<?php echo isset($formdata) ? $formdata['net_to_owner_4'] : ''; ?>" type="text" class="pdf_text" ></td>
	</tr>
	<tr>
		<td width="20%" align="right"><strong>Net Due To Plan It Locations:</strong></td>
		<td width="80%"><input name="net_due_to_pl_4" value="<?php echo isset($formdata) ? $formdata['net_due_to_pl_4'] : ''; ?>" type="text" class="pdf_text" ></td>
	</tr>
	<tr>
		<td style="line-height: 0px;" colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td width="20%" align="right"><strong>Net To Owner:</strong></td>
		<td width="80%"><input name="net_to_owner_5" value="<?php echo isset($formdata) ? $formdata['net_to_owner_5'] : ''; ?>" type="text" class="pdf_text" ></td>
	</tr>
	<tr>
		<td width="20%" align="right"><strong>Net Due To Plan It Locations:</strong></td>
		<td width="80%"><input name="net_due_to_pl_5" value="<?php echo isset($formdata) ? $formdata['net_due_to_pl_5'] : ''; ?>" type="text" class="pdf_text" ></td>
	</tr>


	<tr>
		<td style="line-height: 0px;" colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td width="20%" align="right"><strong>Net To Owner:</strong></td>
		<td width="70%"><input name="net_to_owner_6" value="<?php echo isset($formdata) ? $formdata['net_to_owner_6'] : ''; ?>" type="text" class="pdf_text" ></td>
	</tr>
	<tr>
		<td width="20%" align="right"><strong>Net Due To Plan It Locations:</strong></td>
		<td width="70%"><input name="net_due_to_pl_6" value="<?php echo isset($formdata) ? $formdata['net_due_to_pl_6'] : ''; ?>" type="text" class="pdf_text" ></td>
	</tr>



	<tr>
		<td style="line-height: 0px;" colspan="2">&nbsp;</td>
	</tr>	
	<tr>
		<td  width="100%" colspan="2"><br><br><b>Agreed and Accepted By:</b></td>
	</tr>
	</tbody>
	</table>
	<table cellpadding="5">
		<tr>
			<td><strong>Property Owner:</strong></td>
			<td><input name="accepted_by_property_owner" value="<?php echo isset($formdata) ? $formdata['accepted_by_property_owner'] : $ownerName; ?>" type="text" class="pdf_text" ></td>
			<td align="right"><strong>Date:</strong></td>
			<td><input name="date_1" value="<?php echo isset($formdata) ? $formdata['date_1'] : ''; ?>" type="text" class="pdf_text datepick" ></td>
		</tr>
		<tr>
			<td><strong>Plan-It Locations:</strong></td>
			<td><input name="accepted_by_pl" value="<?php echo isset($formdata) ? $formdata['accepted_by_pl'] : ''; ?>" type="text" class="pdf_text" ></td>
			<td align="right"><strong>Date:</strong></td>
			<td><input name="date_2" value="<?php echo isset($formdata) ? $formdata['date_2'] : ''; ?>" type="text" class="pdf_text datepick" ></td>
		</tr>

		<tr><td colspan="4"><br><br></td></tr>
		<tr>
			<td><b>Status:</b></td> 
			<td>
				<select name="status" id="status">
					<?php 
						$statuses = array('new','pending','on hold','completed','cancelled');
						foreach ($statuses as $key => $value) {
							$selected = isset($formdata) && $formdata['status'] == $value ? 'selected' : '';
							echo '<option value="'.$value.'" '.$selected.'>'.ucfirst($value).'</option>';
						}
					?>
				</select>
			</td>
		</tr>
	</table>

	<table cellpadding="5">
		<tr>
			<td><br><br><br><br><input name="submit_btn" type="submit" class="button" value="save" />&nbsp;&nbsp;&nbsp;<button name="submit_btn_2" type="submit" class="button" value="save_create_pdf">Save & Create PDF</button>&nbsp;&nbsp;&nbsp;<input onclick="location.href='pdf_deal_memo_list_demo.php'" type="button" class="button" value="Cancel" ></td>
		</tr>
	</table>


                           </td></tr>     
                              </table>
                          </div></td>
                        </tr>
                        <tr>
                          <td colspan="2" align="center" valign="top" height="8"></td>
                        </tr>
                        <tr>
                          <td ></td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </form>
                    <p>&nbsp;</p></td>
                </tr>
            </table></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
  </tbody>
</table>
<script type="text/javascript">
$(".datepick").flatpickr({
	 dateFormat: "M d, Y",
});

var getfield=function(div,field)
{  
	var str='';
	str+='<select name="conditions[]">';
	str+='<option value="=">equals</option>';
	str+='<option selected="selected" value="like">contains</option>';
	if(field!='tags')
	{
		str+='<option value="!=">does not equals</option>';
		str+='<option value="<">less then</option>';
		str+='<option value=">">greater then</option>';
	}
	str+='</select>';		
	document.getElementById("conditionsBlock"+div).innerHTML=str;	
}

function fetchProperty() {
	console.log($('#property_id').val());
}
</script>
<? include("footer.php");?>
</body>
</html>