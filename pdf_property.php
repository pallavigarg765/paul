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
		$sql = "SELECT * FROM pdf_property WHERE id=".$edit_id;
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
		width: 97%;
		border: 0;
		outline: 0;
		border-bottom: 1px solid black;
	}
	.heightcss
	{
		line-height: 19.5px;
	    border: 1.5px solid black;
	    vertical-align: bottom;
	    font-size: 12px;
	    margin: 5px 0;
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
            <td width="100%" align="left" valign="top" class="lastview">Property Profiles </td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td height="400" align="left" valign="top" class="mar">

                    <form id="form1" name="form1" method="post" action="process_pdf_property.php">
                    	<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr>
                          <td align="left" valign="top" class="moduleTitle"><?php echo $edit_id!="" ? 'Edit' : 'Create'; ?> Property Profiles</td>
                          <td align="right" valign="top">&nbsp;</td>
                        </tr>
					
                      <tr>
                          <td colspan="2" align="left" valign="top"><div id="pidlist">
                              <table width="800" border="0" cellpadding="5" cellspacing="0" class="listViewThS1" style="border: 1px solid #c9c9c9;padding: 9px;">
                              	<tr>
                              		<td>
                              			<table >
											<tbody>
											<tr>
											<td width="40%" valign="bottom">
												<p style="line-height:22px;">&nbsp;</p>
												<p style="font-size:13px"><b>Please Print All Information Clearly</b></p>
												<div class="heightcss">  Company Name Or Site Name<br>  <input name="company_name" value="<?php echo isset($formdata) ? $formdata['company_name'] : ''; ?>" type="text" class="pdf_text" ></div>
											</td>
											<td valign="middle" align="right" style="font-size:12px;text-align:justify"><img src="images/Screenshot_17.png" height="105px" width="182px"/>
											</td>
											<td valign="middle" align="right" width="165px">
												<div style="font-size:11px;text-align:left">
												Plan-It Locations, Inc.<br>
												Office 818-376-6506<br>
												sales@planitlocations.com<br>
												www.planitlocations.com<br>
												</div>
											</td>
											</tr>
											
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<table width="100%" >
											<tbody>
												<tr>
													<td width="40%" valign="bottom">
														<div class="heightcss">  1st Contact Name<br>  <input name="contact_name_1" value="<?php echo isset($formdata) ? $formdata['contact_name_1'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="40%" valign="bottom">
														<div class="heightcss">  Title<br>  <input name="title_1" value="<?php echo isset($formdata) ? $formdata['title_1'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="20%" valign="bottom">
														
													</td>
												</tr>
												<tr>
													<td width="40%" valign="bottom">
														<div class="heightcss">  Location Address<br>  <input name="location_1" value="<?php echo isset($formdata) ? $formdata['location_1'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="40%" valign="bottom">
														<div class="heightcss">  City, State<br>  <input name="city_state_1" value="<?php echo isset($formdata) ? $formdata['city_state_1'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="20%" valign="bottom">
														<div class="heightcss">  Zip Code<br>  <input name="zip_1" value="<?php echo isset($formdata) ? $formdata['zip_1'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
												</tr>
												<tr>
													<td width="40%" valign="bottom">
														<div class="heightcss">  Mailing Address (if different then above)<br>  <input name="mailing_add_1" value="<?php echo isset($formdata) ? $formdata['mailing_add_1'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="40%" valign="bottom">
														<div class="heightcss">  City, State<br>  <input name="city_state_2" value="<?php echo isset($formdata) ? $formdata['city_state_2'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="20%" valign="bottom">
														<div class="heightcss">  Zip Code<br>  <input name="zip_2" value="<?php echo isset($formdata) ? $formdata['zip_2'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
												</tr>
											</tbody>
										</table>
										<table width="100%" >
											<tbody>
												<tr>
													<td width="25%" valign="bottom">
														<div class="heightcss">  Home Phone #<br>  <input name="home_phone_1" value="<?php echo isset($formdata) ? $formdata['home_phone_1'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="25%" valign="bottom">
														<div class="heightcss">  Office Phone #<br>  <input name="office_phone_1" value="<?php echo isset($formdata) ? $formdata['office_phone_1'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="25%" valign="bottom">
														<div class="heightcss">  Cell Phone #<br>  <input name="cell_phone_1" value="<?php echo isset($formdata) ? $formdata['cell_phone_1'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="25%" valign="bottom">
														<div class="heightcss">  Pager #<br>  <input name="pager_1" value="<?php echo isset($formdata) ? $formdata['pager_1'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
												</tr>
												<tr>
													<td width="25%" valign="bottom">
														<div class="heightcss">  Fax #<br>  <input name="fax_1" value="<?php echo isset($formdata) ? $formdata['fax_1'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="25%" valign="bottom">
														<div class="heightcss">  Other #<br>  <input name="other_1" value="<?php echo isset($formdata) ? $formdata['other_1'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="25%" valign="bottom">
														<div class="heightcss">  Email Address<br>  <input name="email_add_1" value="<?php echo isset($formdata) ? $formdata['email_add_1'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="25%" valign="bottom">
														<div class="heightcss">  Website Address<br>  <input name="web_1" value="<?php echo isset($formdata) ? $formdata['web_1'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
												</tr>
											</tbody>
										</table>
										<p style="font-size:13px;margin:10px 0;"><b>2nd Contact Info</b></p>
										<table width="100%" >
											<tbody>
												<tr>
													<td width="40%" valign="bottom">
														<div class="heightcss">  2nd Contact Name<br>  <input name="contact_name_2" value="<?php echo isset($formdata) ? $formdata['contact_name_2'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="40%" valign="bottom">
														<div class="heightcss">  Title<br>  <input name="title_2" value="<?php echo isset($formdata) ? $formdata['title_2'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="20%" valign="bottom">
														
													</td>
												</tr>
												</tbody>
										</table>
										<table width="100%" >
											<tbody>
												<tr>
													<td width="25%" valign="bottom">
														<div class="heightcss">  Home Phone #<br>  <input name="home_phone_2" value="<?php echo isset($formdata) ? $formdata['home_phone_2'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="25%" valign="bottom">
														<div class="heightcss">  Office Phone #<br>  <input name="office_phone_2" value="<?php echo isset($formdata) ? $formdata['office_phone_2'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="25%" valign="bottom">
														<div class="heightcss">  Cell Phone #<br>  <input name="cell_phone_2" value="<?php echo isset($formdata) ? $formdata['cell_phone_2'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="25%" valign="bottom">
														<div class="heightcss">  Pager #<br>  <input name="pager_2" value="<?php echo isset($formdata) ? $formdata['pager_2'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
												</tr>
												<tr>
													<td width="25%" valign="bottom">
														<div class="heightcss">  Fax #<br>  <input name="fax_2" value="<?php echo isset($formdata) ? $formdata['fax_2'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="25%" valign="bottom">
														<div class="heightcss">  Other #<br>  <input name="other_2" value="<?php echo isset($formdata) ? $formdata['other_2'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="25%" valign="bottom">
														<div class="heightcss">  Email Address<br>  <input name="email_add_2" value="<?php echo isset($formdata) ? $formdata['email_add_2'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td width="25%" valign="bottom">
														<div class="heightcss">  Website Address<br>  <input name="web_2" value="<?php echo isset($formdata) ? $formdata['web_2'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
												</tr>
											</tbody>
										</table>
										<p style="font-size:13px;margin:10px 0;"><b>Location Description</b></p>
										<table width="100%">
											<tbody>
												<tr>
													<td>
														<div class="heightcss">  Lot Size (Acres)<br>  <input name="lot_size" value="<?php echo isset($formdata) ? $formdata['lot_size'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td>
														<div class="heightcss">  Square Footage<br>  <input name="squre_foot" value="<?php echo isset($formdata) ? $formdata['squre_foot'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td>
														<div class="heightcss">  Date Built<br>  <input name="date_built" value="<?php echo isset($formdata) ? $formdata['date_built'] : ''; ?>" type="text" class="pdf_text datepick" ></div>
													</td>
													<td> 
														<div class="heightcss">  Style or Period of the Property<br>  <input name="style_of_property" value="<?php echo isset($formdata) ? $formdata['style_of_property'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
												</tr>
												<tr>
													<td colspan="2">
														<div class="heightcss">  Parking Spots Available Or Parking Issues<br>  <input name="parking_spots" value="<?php echo isset($formdata) ? $formdata['parking_spots'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
													<td colspan="2">
														<div class="heightcss">  Neighbor Issues<br>  <input name="neighbor_issues" value="<?php echo isset($formdata) ? $formdata['neighbor_issues'] : ''; ?>" type="text" class="pdf_text" ></div>
													</td>
												</tr>
											</tbody>
										</table>
										<table width="100%" style="border:2px solid black" cellspacing="6" cellpadding="4">
											<?php $project_interests =  isset($formdata) ? explode(',',$formdata['project_interests']) : array(); ?>
											<tr>
												<td width="50%" >
													<p style="font-size:13px;margin:10px 0;"><b>Important Filming Information</b><br>Select Projects Of Interest For Your Property</p>
													<table width="100%" >
														<tbody>
															<tr>
																<td style="font-size:12px"><input type="checkbox" name="project_interests[]" value="Feature Film" <?php echo in_array('Feature Film', $project_interests) ? 'checked' : ''; ?> >Feature Film</td>
																<td style="font-size:12px"><input type="checkbox" name="project_interests[]" value="Commercials" <?php echo in_array('Commercials', $project_interests) ? 'checked' : ''; ?>>Commercials</td>
															</tr>
															<tr>
																<td style="font-size:12px"><input type="checkbox" name="project_interests[]" value="Still Photography" <?php echo in_array('Still Photography', $project_interests) ? 'checked' : ''; ?>>Still Photography</td>
																<td style="font-size:12px"><input type="checkbox" name="project_interests[]" value="Infomercial" <?php echo in_array('Infomercial', $project_interests) ? 'checked' : ''; ?>>Infomercial</td>
															</tr>
															<tr>
																<td style="font-size:12px"><input type="checkbox" name="project_interests[]" value="TV Movie" <?php echo in_array('TV Movie', $project_interests) ? 'checked' : ''; ?>>TV Movie</td>
																<td style="font-size:12px"><input type="checkbox" name="project_interests[]" value="Events" <?php echo in_array('Events', $project_interests) ? 'checked' : ''; ?>>Events</td>
															</tr>
															<tr>
																<td style="font-size:12px"><input type="checkbox" name="project_interests[]" value="Music Videos" <?php echo in_array('Music Videos', $project_interests) ? 'checked' : ''; ?>>Music Videos</td>
															</tr>
														</tbody>
													</table>
												</td>
												<td width="50%" >
													<table width="100%">
														<tbody>
															<tr>
																<td>
																	<div class="heightcss" style="font-size:10px">  Any Previous Film Projects (Please List Titles & The Year Filmed)<br>  <input name="prev_film_pro" value="<?php echo isset($formdata) ? $formdata['prev_film_pro'] : ''; ?>" type="text" class="pdf_text" ></div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="heightcss" style="font-size:10px">  Any Other Properties Owned For Filming? Or That Friends Own?<br>  <input name="other_prop_owned" value="<?php echo isset($formdata) ? $formdata['other_prop_owned'] : ''; ?>" type="text" class="pdf_text" ></div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="heightcss" style="font-size:10px">  Do You Own Any Show Vehicles, Boats or Aircraft?<br>  <input name="show_vehicles" value="<?php echo isset($formdata) ? $formdata['show_vehicles'] : ''; ?>" type="text" class="pdf_text" ></div>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
												
											</tr>
											
										
									</table>
									<table width="100%" >
										<tbody>
											<tr>
												<td width="65%" valign="bottom">
													<div class="heightcss">  Notes<br>  <input name="notes" value="<?php echo isset($formdata) ? $formdata['notes'] : ''; ?>" type="text" class="pdf_text" ></div>
												</td>
												<td width="35%" valign="bottom">
													<div class="heightcss">  Photographers Name & Date<br>  <input name="photographer" value="<?php echo isset($formdata) ? $formdata['photographer'] : ''; ?>" type="text" class="pdf_text" ></div>
												</td>
											</tr>
											</tbody>
									</table>
									</td>
								</tr>
								
								
								<tr>
									<td>
										


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
			<td><br><br><br><br><input name="submit_btn" type="submit" class="button" value="save" />&nbsp;&nbsp;&nbsp;<button name="submit_btn_2" type="submit" class="button" value="save_create_pdf">Save & Create PDF</button>&nbsp;&nbsp;&nbsp;<input onclick="location.href='pdf_property_list.php'" type="button" class="button" value="Cancel" ></td>
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