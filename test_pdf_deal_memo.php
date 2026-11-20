<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
	mysql_select_db("planitlo_crm");
	$edit_id = '';
	$dealMemoItemsRows = 0;
	if(isset($_GET['edit_id']) && $_GET['edit_id']!="")
	{
		$edit_id = $_GET['edit_id'];
		$sql = "SELECT * FROM test_pdf_deal_memo WHERE id=".$edit_id;
		$formres = $db->query($sql) or die($sql);
		$formdata = $db->fetchArray($formres);

		$dealMemoItemssql = "SELECT * FROM test_pdf_deal_memo_items WHERE deal_memo_id=".$edit_id." order by id asc";
		$dealMemoItems = $db->query($dealMemoItemssql) or die($dealMemoItemssql);
		$dealMemoItemsRows = $db->getNumRows($dealMemoItems); 
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CRM</title>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
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

                    <form id="form1" name="form1" method="post" action="test_process_pdf_deal_memo.php">
                    	<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr>
                          <td align="left" valign="top" class="moduleTitle"><?php echo $edit_id!="" ? 'Edit' : 'Create'; ?> Deal Memo</td>
                          <td align="right" valign="top">&nbsp;</td>
                        </tr>
					
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
				<td  valign="bottom" width="15%"><strong>Today's Date:</strong></td>
			
				<td width="85%" colspan="2"><input name="today_date" value="<?php echo isset($formdata) ? $formdata['today_date'] : ''; ?>" type="text" class="pdf_text datepick" ></td>
			</tr>
			<tr>
				<td  width="15%"><strong>Project Type:</strong></td>
			
				<td width="85%" colspan="2"><input name="project_type" value="<?php echo isset($formdata) ? stripslashes($formdata['project_type']) : ''; ?>" type="text" class="pdf_text" ></td>
			</tr>
			<tr>
				<td  width="30%"><strong>Producer / Event Organizer: </strong></td>
				<td width="70%" colspan="2"><input name="producer" value="<?php echo isset($formdata) ? stripslashes($formdata['producer']) : ''; ?>" type="text" class="pdf_text" ></td>
			</tr>
			<tr>

				<td align="right" width="30%"><strong>Location Owner(s):</strong></td>
				<td width="70%" colspan="2"><input name="location_owner" value="<?php echo isset($formdata) ? stripslashes($formdata['location_owner']) : ''; ?>" type="text" class="pdf_text" ></td>
			</tr>
			<tr>

				<td align="right" width="30%"><strong>Tax ID:</strong></td>
				<td width="70%" colspan="2"><input name="tax_id" value="<?php echo isset($formdata) ? stripslashes($formdata['tax_id']) : ''; ?>" type="text" class="pdf_text" ></td>
			</tr>
			<tr>
				<td align="right" width="30%"><strong>Address:</strong></td>
				<td width="70%" colspan="2"><input name="address" value="<?php echo isset($formdata) ? stripslashes($formdata['address']) : ''; ?>" type="text" class="pdf_text" ></td>
			</tr>

			
		<!-- 	<tr>
				<td width="25%"><strong>Location Rental Date:</strong></td>
				<td width="75%"><input name="location_rental_date" value="<?php echo isset($formdata) ? $formdata['location_rental_date'] : ''; ?>" type="text" class="pdf_text" ></td>
			</tr> -->
			<tr>
				<td align="right" width="30%"><strong>Location Rental Date:</strong></td>
				<td width="70%" colspan="2"><input name="location_rental_date_1" value="<?php echo isset($formdata) ? stripslashes($formdata['location_rental_date_1']) : ''; ?>" type="text" class="pdf_text" ></td>
			</tr>
			<tr>
				<td align="right" width="30%"><strong>&nbsp;</strong></td>
				<td width="70%" colspan="2"><input name="location_rental_date_2" value="<?php echo isset($formdata) ? stripslashes($formdata['location_rental_date_2']) : ''; ?>" type="text" class="pdf_text" ></td>
			</tr>
			<!-- <tr>
				<td colspan="3">
					<table cellpadding="5" width="100%">
						<tr>
							<td align="right" width="30%"><strong>Location Rental Date: </strong></td>
							<td width="32%"><input name="location_rental_date_1" value="<?php echo isset($formdata) ? $formdata['location_rental_date_1'] : ''; ?>" type="text" class="pdf_text" ></td>
							<td width="5%" align="right"><strong>&nbsp;</strong></td>
							<td width="30%"><input name="location_rental_date_2" value="<?php echo isset($formdata) ? $formdata['location_rental_date_2'] : ''; ?>" type="text" class="pdf_text" ></td>
						</tr>
					</table>
				</td>
			</tr> -->

			
			<tr>
				<td  width="100%" colspan="2"><br><br>Fees and Commissions Due Owner and Agent Are as Followed:</td>
			</tr>

			<tr>
				<td  width="100%" colspan="2">1. Location Rental Fees</td>
			</tr>

			<?php 
			if($dealMemoItemsRows > 0) {
				while($memoItem = $db->fetchArray($dealMemoItems)) {
				$rowClass = rand();	
			?>
					<tr class='<?php echo $rowClass; ?>'>
						<td style='line-height: 0px;' colspan='3'>&nbsp;</td>
					</tr>
					<tr class='<?php echo $rowClass; ?>'>
						<td width="30%" align="right"><strong>Net To Owner:</strong></td>
						<td width="60%"><input name="net_to_owner[]" type="text" class="pdf_text" value="<?php echo $memoItem['net_to_owner'] ?>"></td>
						<td width="10%"><button type="button" onclick="removeRow('<?php echo $rowClass; ?>')" class="deleteBtn">X</button></td>
					</tr>
					<tr class='<?php echo $rowClass; ?>'>
						<td width="30%" align="right"><strong>Net Due To Plan It Locations:</strong></td>
						<td width="70%"><input name="net_due_to_pl[]" type="text" class="pdf_text" value="<?php echo $memoItem['net_due_to_pl'] ?>"></td>
						<td width="10%">&nbsp;</td>

					</tr>
			<?php } 
				}
			?>

			<tr id="prepend_here">
				<td colspan="3" align="right">
					<br><br>
					<button type="button" class="button add-row">Add More</button>
				</td>
			</tr>	
			<tr>
				<td style="line-height: 0px;" colspan="2">&nbsp;</td>
			</tr>	
			<tr>
				<td  width="100%" colspan="3"><br><br><b>Agreed and Accepted By:</b></td>
			</tr>
	</tbody>
	</table>
			<table width="100%" cellpadding="5">
				<tr>
					<td><strong>Property Owner:</strong></td>
					<td><input name="accepted_by_property_owner" value="<?php echo isset($formdata) ? $formdata['accepted_by_property_owner'] : ''; ?>" type="text" class="pdf_text" ></td>
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
				
			</table>

	


                           </td></tr>     
                              </table>

                              <table cellpadding="5">
		<tr>
			<td><br><br><b>Status:</b></td> 
			<td><br><br>
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
		<tr>
			<td colspan="2">
				<input name="submit_btn" type="submit" class="button" value="save" />&nbsp;&nbsp;&nbsp;<button name="submit_btn_2" type="submit" class="button" value="save_create_pdf">Save & Create PDF</button>&nbsp;&nbsp;&nbsp;<input onclick="location.href='test_pdf_deal_memo_list.php'" type="button" class="button" value="Cancel" >
			</td>
		</tr>
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

function addNewRow()
{
			var rowClass = (Math.random() + 1).toString(36).substring(7);
			var rowHtml = "<tr class='"+rowClass+"'>";
      		rowHtml += "<td style='line-height: 0px;' colspan='3'>&nbsp;</td>";
      rowHtml += "</tr>";
      rowHtml += "<tr class='"+rowClass+"'>";
      	rowHtml += '<td width="30%" align="right"><strong>Net To Owner:</strong></td>';
      	rowHtml += '<td width="60%"><input name="net_to_owner[]" type="text" class="pdf_text" ></td>';
      	rowHtml += '<td width="10%"><button type="button" class="deleteBtn" onclick="removeRow(\''+rowClass+'\')">X</button></td>';
      rowHtml += "</tr>";
      rowHtml += "<tr class='"+rowClass+"'>";
      	rowHtml += '<td width="30%" align="right"><strong>Net Due To Plan It Locations:</strong></td>';
      	rowHtml += '<td width="70%"><input name="net_due_to_pl[]" type="text" class="pdf_text" ></td>';
      	rowHtml += '<td width="10%">&nbsp;</td>';

      rowHtml += "</tr>";
		$(rowHtml).insertBefore('#prepend_here');
		if($('.deleteBtn').length > 1) {
			$('.deleteBtn').show();
		}
		else {
			$('.deleteBtn').hide();
		}
}

function removeRow(rowclass)
{
	$('.'+rowclass).remove();
	if($('.deleteBtn').length > 1) {
		$('.deleteBtn').show();
	}
	else {
		$('.deleteBtn').hide();
	}
}

$(document).ready(function(){
	<?php if($edit_id=="") { ?>
		for($i=1;$i<=7;$i++)
    {
    	addNewRow();
    }
  <?php } ?>
    $(".add-row").click(function(){
       addNewRow();
    });
});    
</script>
<? include("footer.php");?>
</body>
</html>