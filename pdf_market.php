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
		$sql = "SELECT * FROM pdf_market WHERE id=".$edit_id;
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
            <td width="100%" align="left" valign="top" class="lastview">Marketing flyer </td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td height="400" align="left" valign="top" class="mar">

                    <form id="form1" name="form1" method="post" action="process_pdf_market.php">
                    	<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr>
                          <td align="left" valign="top" class="moduleTitle"><?php echo $edit_id!="" ? 'Edit' : 'Create'; ?> Marketing flyer</td>
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
	
	<td align="center" style="font-size:12px"><img src="images/planit-logo-2.png" height="105px"/><br/><img src="images/planit-logo-3.png" width="270px"/><br/>
	Office (818) 376-6506
	<br/>
	sales@planitlocations.com
	<br/>
	www.planitlocations.com

	</td>
	</tr>
	<tr>
		<td align="center" style="font-size: 18px;font-weight:900"><br><br>WE WOULD LOVE TO MARKET YOUR PROPERTY!</td>
	</tr>
	<tr>
		<td align="center" style="font-size: 12px;">FOR POSSIBLE FILM, TELEVISION, VIDEO, PHOTOGRAPHY, EVENTS, MUSIC VIDEOS!</td>
	</tr>
	
	</tbody>
	</table>
									</td>
								</tr>
								<tr>
									<td>
										<div style="font-size:12px;text-align:justify;white-space: break-spaces;"><u>Here's a Little About us:</u><br> Plan-It Locations, Inc has many successful locations in the greater Los Angeles area. We enjoy working with Property Owner's like you. We introduce your Property to film producers, photographers, event organizers, and television production companies that may be interested in using your Property as a film location. We register properties on a non-exclusive basis.</div><br>
										<div style="font-size:12px;text-align:justify;white-space: break-spaces;"><u>Here's How It Works:</u><br> If a marketed property is selected for a film project, photography, an event, or TV show, Plan-It Locations, Inc will provide a legal Agreement, inclusive of your rights as Owner, and the rights of those who may rent your property. The Agreement reflects all the terms, i.e. rental fee(s), and additional fees, such as electrical, telephone, pool-heating, etc.) the dates of rental, what the requirements for the project may be, and what you may require as a property owner. When both parties have agreed to all terms, the "Contract" is signed and your earnings are 75% of the total location rental fee. Plan-It Locations, Inc acts as "the Agent" between you and the "Producer" renting your Property for filming. Plan-It Locations, Inc earns 30% of the total location rental fee - making it a 75/25 split. Marketed and selected for rental over a period of months, your earnings could be considerable. <br><br>If you have interest in working with us, Plan-It Locations, Inc will professionally photograph your Property and market the photographs for the filming industry for renting your Property as a film location in their future projects. Our "marketing" is done through E-Mail, Internet, Website and from the photographs we would house in our extensive Location Library. <br> <br>We hope you will consider our proposal regarding your Property, and we look forward to the possibility of making new friends, as well as a successful and profitable year ahead! <br><br>Please visit our website to see our past projects.<br>Yes! As the Owner of the Property located at the following address:</div>
									</td>
								</tr>
								<tr>
									<td>
										<table width="100%">
											<tbody>
												<tr>
													<td width="15%">Full Address:</td>
													<td width="5px">&nbsp;</td>
													<td width="80%"><input name="address" value="<?php echo isset($formdata) ? $formdata['address'] : ''; ?>" type="text" class="pdf_text" ></td>
												</tr>
												<tr>
													<td width="15%">Phone Number:</td>
													<td width="5px">&nbsp;</td>
													<td width="80%"><input name="phone_no" value="<?php echo isset($formdata) ? $formdata['phone_no'] : ''; ?>" type="text" class="pdf_text" ></td>
												</tr>

											</tbody>
										</table>

									</td>
								</tr>
								<tr>
									<td>
										<br><br><div style="font-size:12px;text-align:justify;white-space: break-spaces;">I am giving my permission to Plan-It Locations, Inc to photograph and market my Property by signing below:</div><br>
									</td>
								</tr>
								<tr>
									<td>
										<table width="100%">
											<tbody>
												<tr>
													<td width="30%"><input name="property_owner" value="<?php echo isset($formdata) ? $formdata['property_owner'] : ''; ?>" type="text" class="pdf_text" ></td>
													<td width="5px">&nbsp;</td>
													<td width="30%"><input name="print_name" value="<?php echo isset($formdata) ? $formdata['print_name'] : ''; ?>" type="text" class="pdf_text" ></td>
													<td width="5px">&nbsp;</td>
													<td width="30%"><input name="date" value="<?php echo isset($formdata) ? $formdata['date'] : ''; ?>" type="text" class="pdf_text datepick" ></td>
												</tr>
												<tr>
													<td width="30%" style="text-align:center">(Property Owner)</td>
													<td width="5px">&nbsp;</td>
													<td width="30%" style="text-align:center">(Print Name):</td>
													<td width="5px">&nbsp;</td>
													<td width="30%" style="text-align:center">Date:</td>
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
			<td><br><br><br><br><input name="submit_btn" type="submit" class="button" value="save" />&nbsp;&nbsp;&nbsp;<button name="submit_btn_2" type="submit" class="button" value="save_create_pdf">Save & Create PDF</button>&nbsp;&nbsp;&nbsp;<input onclick="location.href='pdf_market_list.php'" type="button" class="button" value="Cancel" ></td>
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