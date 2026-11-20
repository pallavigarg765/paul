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
		$sql = "SELECT * FROM pdf_nda WHERE id=".$edit_id;
		$formres = $db->query($sql) or die($sql);
		$formdata = $db->fetchArray($formres);
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
            <td width="100%" align="left" valign="top" class="lastview">NDA </td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td height="400" align="left" valign="top" class="mar">

                    <form id="form1" name="form1" method="post" action="process_pdf_nda.php">
                    	<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr>
                          <td align="left" valign="top" class="moduleTitle"><?php echo $edit_id!="" ? 'Edit' : 'Create'; ?> NDA</td>
                          <td align="right" valign="top">&nbsp;</td>
                        </tr>
					
                      <tr>
                          <td colspan="2" align="left" valign="top"><div id="pidlist">
                              <table width="600" border="0" cellpadding="5" cellspacing="0" class="listViewThS1" style="border: 1px solid #c9c9c9;padding: 9px;">
                              	<tr>
                              		<td>
                              			<table >
											<tbody>
												<tr>
												
												<td valign="middle" align="right" ><img src="images/Screenshot_17.png" height="105px" width="182px"/>
												</td>
												<td valign="middle" align="left">
													<div style="max-width:100%;font-size:11px">
													Plan-It Locations, Inc.<br>
													Office 818-376-6506<br>
													sales@planitlocations.com<br>
													www.planitlocations.com<br>
													</div>
												</td>
												</tr>
												<tr >
													<td align="center" colspan="2" style="font-size: 16px;font-weight:900"><br>NON-CIRCUMVENTION AND NON-DISCLOSURE AGREEMENT</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div style="max-width:100%;font-size:12px;white-space: break-spaces;text-align:justify">1. This Agreement ("Agreement") is entered into by and between Plan-It Locations, Inc and <input name="recipient" value="<?php echo isset($formdata) ? $formdata['recipient'] : ''; ?>" type="text" class="pdf_text" > ("Recipient") on behalf of Recipient and Recipient's principals, partners, agents, employers, employees, and successors (collectively "Recipients"),</div>
										<div style="max-width:100%;font-size:12px;white-space: break-spaces;text-align:justify">2. In consideration of assistance provided by Plan - It Locations, Inc to Recipients in identifying and/or viewing certain properties ("Properties"), and other good and valuable consideration, the receipt and sufficiency of which is hereby acknowledged, Recipients, and each of them, agree not to enter into any agreement for sale, lease, or use of the Properties, or any of them, unless Plan - It Locations, Inc is acting as the agent of at least one party in the transaction, Recipients, and each of them, further agree not to disclose the address or any other information concerning the Properties, or any of them, to any person or entity, unless such disclosure is required in furtherance of an agreement in which Plan- It Locations, Inc is acting as the agent of at least one party. Recipients\' obligations under this paragraph shall be binding regardless of whether or not Recipients, or any of them had independent knowledge of the Properties, or any of them, prior to receiving assistance from Plan - It Locations, Inc or executing this Agreement.</div>
										<div style="max-width:100%;font-size:12px;white-space: break-spaces;text-align:justify">3. Recipients acknowledge and agree that among the Properties subject to this Agreement are the following:</div><br>
									</td>
								</tr>
								<tr>
									<td>
										<table width="100%">
											<tbody>
												<tr>
													<td width="70%"><input name="line_1_1" value="<?php echo isset($formdata) ? $formdata['line_1_1'] : ''; ?>" type="text" class="pdf_text" ></td>
													<td width="5px">&nbsp;</td>
													<td width="15%"><input name="line_1_2" value="<?php echo isset($formdata) ? $formdata['line_1_2'] : ''; ?>" type="text" class="pdf_text" ></td>
												</tr>
												<tr>
													<td width="70%"><input name="line_2_1" value="<?php echo isset($formdata) ? $formdata['line_2_1'] : ''; ?>" type="text" class="pdf_text" ></td>
													<td width="5px">&nbsp;</td>
													<td width="15%"><input name="line_2_2" value="<?php echo isset($formdata) ? $formdata['line_2_2'] : ''; ?>" type="text" class="pdf_text" ></td>
												</tr>
												<tr>
													<td width="70%"><input name="line_3_1" value="<?php echo isset($formdata) ? $formdata['line_3_1'] : ''; ?>" type="text" class="pdf_text" ></td>
													<td width="5px">&nbsp;</td>
													<td width="15%"><input name="line_3_2" value="<?php echo isset($formdata) ? $formdata['line_3_2'] : ''; ?>" type="text" class="pdf_text" ></td>
												</tr>
												<tr>
													<td width="70%"><input name="line_4_1" value="<?php echo isset($formdata) ? $formdata['line_4_1'] : ''; ?>" type="text" class="pdf_text" ></td>
													<td width="5px">&nbsp;</td>
													<td width="15%"><input name="line_4_2" value="<?php echo isset($formdata) ? $formdata['line_4_2'] : ''; ?>" type="text" class="pdf_text" ></td>
												</tr>

											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<br><br><div style="max-width:100%;font-size:12px;white-space: break-spaces;text-align:justify">4. This Agreement and the obligations here under shall survive any negotiations or discussions between the parties to this Agreement and shall terminate five years from the earliest date appearing below.</div>
										<div style="max-width:100%;font-size:12px;white-space: break-spaces;text-align:justify">5. This Agreement constitutes the entire contract between the parties regarding the subject matter hereof and may only be amended or modified by a writing signed by both parties. Waiver of any provision of this Agreement shall not be deemed or constitute a waiver of any other provision, nor shall such waiver constitute a continuing waiver. If any provision of this Agreement is held invalid, the remaining provisions shall continue in full force and effect. The prevailing party in any action or proceeding brought to enforce or interpret this Agreement shall be entitled to reasonable attorney's fees and costs in addition to any other relief granted.</div>
										<div style="max-width:100%;font-size:12px;white-space: break-spaces;text-align:justify">6. This Agreement may be executed in counterparts, and any facsimile of any party's signature shall be deemed to be an original signature for all purposes.</div>
									</td>
								</tr>
								<tr>
									<td>
										<table width="100%">
											<tbody>
												<tr>
													<td width="22%"><input name="authority" value="<?php echo isset($formdata) ? $formdata['authority'] : ''; ?>" type="text" class="pdf_text" ></td>
													<td width="5px">&nbsp;</td>
													<td width="22%"><input name="date_1" value="<?php echo isset($formdata) ? $formdata['date_1'] : ''; ?>" type="text" class="pdf_text datepick" ></td>
													<td width="5px">&nbsp;</td>
													<td width="22%"><input name="customer" value="<?php echo isset($formdata) ? $formdata['customer'] : ''; ?>" type="text" class="pdf_text" ></td>
													<td width="5px">&nbsp;</td>
													<td width="22%"><input name="date_2" value="<?php echo isset($formdata) ? $formdata['date_2'] : ''; ?>" type="text" class="pdf_text datepick" ></td>
													<td width="5px">&nbsp;</td>
												</tr>
												
												<tr>
													<td width="22%" style="font-size:10px;text-align:center">Ron Jarvis - Agent<br>Plan - It Locations, Inc.</td>
													<td width="5px">&nbsp;</td>
													<td width="22%" style="font-size:10px;text-align:center">Dated</td>
													<td width="5px">&nbsp;</td>
													<td width="22%" style="font-size:10px;text-align:center"></td>
													<td width="5px">&nbsp;</td>
													<td width="22%" style="font-size:10px;text-align:center">Dated</td>
													<td width="5px">&nbsp;</td>
												</tr>
											</tbody>
										</table>


	<table cellpadding="5">


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
			<td><br><br><br><br><input name="submit_btn" type="submit" class="button" value="save" />&nbsp;&nbsp;&nbsp;<button name="submit_btn_2" type="submit" class="button" value="save_create_pdf">Save & Create PDF</button>&nbsp;&nbsp;&nbsp;<input onclick="location.href='pdf_nda_list.php'" type="button" class="button" value="Cancel" ></td>
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
</script>
<? include("footer.php");?>
</body>
</html>