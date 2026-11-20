<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	
	$db = new DBConn();
	mysql_select_db("planitlo_crm");
	
	$all_home_banner="SELECT * FROM `planitlo_crm`.`home_banner_all` where banner_type='1' order by show_day,priority ASC";
	$select_all_home_banner = $db->query($all_home_banner);

	$one_home_banner="SELECT * FROM `planitlo_crm`.`home_banner_all` where banner_type='2' order by show_day,priority ASC";
	$select_one_home_banner = $db->query($one_home_banner);

	if(isset($_GET['action']) && $_GET['action']=='delete')
	{
		$query = "DELETE FROM `home_banner_all` WHERE `id` = '".$_GET['id']."'";
		mysql_query($query) or die(mysql_error());
		header('location:home_banner.php');
	}
	
	$banner_show = 'location_detail1';
	if(isset($_COOKIE['view_div']) && $_COOKIE['view_div']=='location_detail2')
	{
		$banner_show = 'location_detail2';
	}
	$days_arr = array('0'=>'All Day','1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday','7'=>'Sunday');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CRM</title>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="css/base/jquery.ui.all.css" rel="stylesheet" />	
<script src="js/prototype.js"></script>
<script type="text/javascript" src="js/effects.js"></script>
<script type="text/javascript" src="js/lightwindow.js"></script>
<style type="text/css">
	v\:* {
		behavior:url(#default#VML);
	}
</style>
<link type="text/css" href="css/technocss.css" rel="stylesheet" />	
<link type="text/css" href="css/font-awesome.css" rel="stylesheet" />

<link rel="stylesheet" type="text/css" href="css/lightwindow.css" />

<style type="text/css">
	.tblPhoto {
	    max-height: 105px;
	}
</style>
</head>


<body>
<link rel="stylesheet" type="text/css" href="css/tooltipjs/style.css">
<? include("header.php"); ?>

<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="4" align="left" valign="top" nowrap="nowrap"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
        <tbody>
          <tr>
            <td width="100%" align="left" valign="top" class="lastview"><b style="white-space: nowrap;">Property Detail &nbsp;&nbsp;</b></td>
          </tr>
          <tr>
            <td align="left" valign="top">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	                <tr>
	                  	<td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
	                  	<td align="left" valign="top" class="mar">
		                  	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		                      <tr>
		                        <td align="left" valign="top" class="moduleTitle">Property Information </td>
		                      </tr>
		                    </table>
							
						
							<div id="location_detail1" class="mydi3v" style="<?php echo $banner_show!='location_detail1' ? 'display: none' : ''; ?>">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
					                <tr>
					                  	<td align="left" valign="top">
					                  		<table width="79%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 30px;">
			                                 	<tr>
				                                    <td width="114" align="center" valign="baseline"><a href="javascript:;" class="i showdiv" data-id="location_detail1">Banner Group</a></td>
				                                    <td width="4"></td>
				                                    <td width="135" align="center" valign="middle"><a href="javascript:;" class="h showdiv" data-id="location_detail2">Single Banner</a></td>
				                                    <td width="6"></td>
				                                </tr>
					                      	</table>
					                  	</td>
					                </tr>
                        		</table>
                        		<table class="border2"> 
                        			<tbody>
                        				<tr>
											<td class="formtxt" colspan="3">
												<form method="POST" action="home_banner_process.php">
													<p class="moduleTitle" style="margin-bottom: 20px;">Add Property Banner</p>
													<p>Property ID :<br></p>

													<input class="form ui-autocomplete-input" type="text" size="35" id="property_id_" name="property_id_" autocomplete="off" onkeyup="ajax_showOptions(this, 'id', event, document.getElementById('chkIncludeCh').checked);"><input type="button" id="generate" value="Generate" ><br>
													<div id="banner_images" style="margin-top: 5px;"></div><br>

													<div style="width: 245px;float: left;">
														<p>Sort Order :<br></p>
														<input class="form ui-autocomplete-input" type="number" size="35" id="sort_order_1" name="sort_order">
													</div>
													<div >
													<p>Show On Day :<br></p>
													<select name="show_day" >
														<option value="0">All Day</option>
														<option value="1">Monday</option>
														<option value="2">Tuesday</option>
														<option value="3">Wednesday</option>
														<option value="4">Thursday</option>
														<option value="5">Friday</option>
														<option value="6">Saturday</option>
														<option value="7">Sunday</option>
													</select>
													</div>
													<br><br>
													<button type="submit" name="home_banner_all" value="home_banner_all">Submit</button>
												</form>
											</td>
										</tr>
										<tr>
											<td>
												<p class="moduleTitle" style="margin: 27px 0px 12px;">Property Banner List</p>
												<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
							                      <tr>
							                        <td align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview">
							                          
							                          <tr bgcolor="#D0E1F4" height="20">
							                            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
							                            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="100%" align="left"> <strong>Sl No&nbsp;</strong>&nbsp; </div></td>
							                            <td width="180" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> Property Id</strong>&nbsp;</td>
							                            
							                            <td height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>URL</strong></td>
							                            <td  height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Sort Order</strong></td>
							                            <td  height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Day</strong></td>
							                            <td  height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Action</strong></td>
							                            <td width="21" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
							                          </tr>
							                          <? 
							                         
														$numrows = $db->getNumRows($select_all_home_banner); 
														$c=1;
													while($lrow = $db->fetchArray($select_all_home_banner)){
														          
														?>
							                          <tr class="grid" height="20">
							                            <td  height="20" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
							                            <td  nowrap="nowrap" scope="col" class="n"><?php echo $c; ?></td>
							                            <td height="25" nowrap="nowrap" class="n" scope="col"><?php echo $lrow['property_id']; ?></td>
							                          
							                            <td height="25" nowrap="nowrap" class="n" scope="col"><a href="<?php echo $lrow['url']; ?>" target="_blank">URL</a></td>
							                            <td height="25" nowrap="nowrap" class="n" scope="col"><?php echo $lrow['priority']; ?></td>
							                            <td height="25" nowrap="nowrap" class="n" scope="col"><?php echo $days_arr[$lrow['show_day']]; ?></td>
							                            <td height="25" nowrap="nowrap" class="n" scope="col"><a href="home_banner.php?action=delete&id=<?php echo $lrow['id']; ?>"  style="color:red">Delete</a></td>
						              					<td  height="20" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>										
													  </tr>							 
							                          <? $c=$c+1; }

							                          if($numrows < 1 || $c==0){ echo '<td class="formtxt" colspan="6" height="30" align="center"> No records found.</td>';}?>
							                         
							                        </table></td>
							                      </tr>
							                    </table>
											</td>
										</tr>

									</tbody>
                        		</table>

            				</div>


            				<div id="location_detail2" class="mydi3v" style="<?php echo $banner_show!='location_detail2' ? 'display: none' : ''; ?>">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
					                <tr>
					                  	<td align="left" valign="top">
					                  		<table width="79%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 30px;">
			                                 	<tr>
				                                    <td width="114" align="center" valign="baseline"><a href="javascript:;" class="h showdiv" data-id="location_detail1">Banner Group</a></td>
				                                    <td width="4"></td>
				                                    <td width="135" align="center" valign="middle"><a href="javascript:;" class="i showdiv" data-id="location_detail2">Single Banner</a></td>
				                                    <td width="6"></td>
				                                </tr>
					                      	</table>
					                  	</td>
					                </tr>
                        		</table>
                        		<table class="border2"> 
                        			<tbody>
                        				<tr>
											<td class="formtxt" colspan="3">
												<form method="POST" action="home_banner_process.php">
													<p class="moduleTitle" style="margin-bottom: 20px;">Add Single Banner</p>
													<p>Property ID :<br></p>

													<input class="form ui-autocomplete-input" type="text" size="35" id="property_id_2" name="property_id_" autocomplete="off" onkeyup="ajax_showOptions(this, 'id', event, document.getElementById('chkIncludeCh').checked);"><input type="button" id="generate2" value="Generate" ><br>
													<div id="banner_images2" style="margin-top: 5px;"></div><br>
													

													<div style="width: 245px;float: left;">
														<p>Sort Order :<br></p>
														<input class="form ui-autocomplete-input" type="number" size="35" id="sort_order_2" name="sort_order">
													</div>
													<div >
													<p>Show On Day :<br></p>
													<select name="show_day" >
														<option value="0">All Day</option>
														<option value="1">Monday</option>
														<option value="2">Tuesday</option>
														<option value="3">Wednesday</option>
														<option value="4">Thursday</option>
														<option value="5">Friday</option>
														<option value="6">Saturday</option>
														<option value="7">Sunday</option>
													</select>
													</div>



													<br><br>
													<button type="submit" name="home_banner_single" value="home_banner_single">Submit</button>
												</form>
											</td>
										</tr>
										<tr>
											<td>
												<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
							                      <tr>
							                        <td align="left" valign="top">
												<p class="moduleTitle" style="margin: 27px 0px 12px;">Single Property Banner List</p>

							                        	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview">
							                          
							                          <tr bgcolor="#D0E1F4" height="20">
							                            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
							                            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="100%" align="left"> <strong>Sl No&nbsp;</strong>&nbsp; </div></td>
							                            <td width="180" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> Property Id</strong>&nbsp;</td>
							                            
							                            <td height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>URL</strong></td>
							                            <td height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Image</strong></td>
							                            <td  height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Sort Order</strong></td>
							                            <td  height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Day</strong></td>
							                            <td  height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Action</strong></td>
							                            <td width="21" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
							                          </tr>
							                          <? 
							                         
														$numrows = $db->getNumRows($select_one_home_banner); 
														$c=1;
													while($lrow = $db->fetchArray($select_one_home_banner)){
														          
														?>
							                          <tr class="grid" height="20">
							                            <td  height="20" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
							                            <td  nowrap="nowrap" scope="col" class="n"><?php echo $c; ?></td>
							                            <td height="25" nowrap="nowrap" class="n" scope="col"><?php echo $lrow['property_id']; ?></td>
							                          
							                            <td height="25" nowrap="nowrap" class="n" scope="col"><a href="<?php echo $lrow['url']; ?>" target="_blank">URL</a></td>
							                            <td height="25" nowrap="nowrap" class="n" scope="col"><a href="<?php echo $lrow['banner1']; ?>" target="_blank">View</a></td>
							                            <td height="25" nowrap="nowrap" class="n" scope="col"><?php echo $lrow['priority']; ?></td>
							                            <td height="25" nowrap="nowrap" class="n" scope="col"><?php echo $days_arr[$lrow['show_day']]; ?></td>

							                            <td height="25" nowrap="nowrap" class="n" scope="col"><a href="home_banner.php?action=delete&id=<?php echo $lrow['id']; ?>"  style="color:red">Delete</a></td>
						              					<td  height="20" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>										
													  </tr>							 
							                          <? $c=$c+1; }

							                          if($numrows < 1 || $c==0){ echo '<td class="formtxt" colspan="6" height="30" align="center"> No records found.</td>';}?>
							                         
							                        </table></td>
							                      </tr>
							                    </table>
											</td>
										</tr>

									</tbody>
                        		</table>

            				</div>


						</td>
					</tr>
				</table>
			</td>
		</tr>
	</tbody>

</table></td></tr></tbody></table> 
<? include("footer.php");?>

<script src="css/tooltipjs/tooltop.js" type="text/javascript"></script>
<!-- end display tag search box -->
</body>
</html>
<script type="text/javascript" src="js/jquery-ui-1.8.custom.js"></script>
<script type="text/javascript" src="js/jquery.ui.autocomplete.js"></script>
<script type="text/javascript">
	function createCookie(name, value, days) {
	    var expires;

	    if (days) {
	        var date = new Date();
	        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
	        expires = "; expires=" + date.toGMTString();
	    } else {
	        expires = "";
	    }
	    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
	}
	jQuery(document).ready(function() {
		jQuery('.showdiv').click(function(){
			var id = jQuery(this).attr('data-id');
			jQuery(this).parents('.mydi3v').hide();
			jQuery('#'+id).show();
			createCookie('view_div',id,1);
		});
		jQuery("#generate").click(function() {
			var URL = jQuery("#property_id_").val();
			generateBanner(URL);
		});

		jQuery("#generate2").click(function() {
			var URL = jQuery("#property_id_2").val();
			generateBanner2(URL);
		});
		jQuery('.optionDivSelected').live('click',function(){

			if(jQuery('#location_detail1').is(":visible"))
			{
				var URL = jQuery("#property_id_").val();
				generateBanner(URL);
			}
			else
			{
				var URL = jQuery("#property_id_2").val();
				generateBanner2(URL);
			}
		});
	});
	function generateBanner(URL) {
		jQuery.ajax({
			type: 'POST',
			data: {
				'url': URL,
			},
			url: 'https://planitlocations.com/crm/get_property_images.php',
			beforeSend: function() {
				jQuery("#banner_images").html('<img src="https://planitlocations.com/dev-dev/selection/reports/images/ajax-loader.gif">');
			},
			success: function(res) {
				var obj = JSON.parse(res)
				jQuery("#banner_images").html(obj.photoes);
				jQuery("#name").val(obj.propertyName);
			}
		});
	}
	function generateBanner2(URL) {
		jQuery.ajax({
			type: 'POST',
			data: {
				'url': URL,
				'addcheck' : true
			},
			url: 'https://planitlocations.com/crm/get_property_images.php',
			beforeSend: function() {
				jQuery("#banner_images2").html('<img src="https://planitlocations.com/dev-dev/selection/reports/images/ajax-loader.gif">');
			},
			success: function(res) {
				var obj = JSON.parse(res)
				jQuery("#banner_images2").html(obj.photoes);
			}
		});
	}
</script>
</body>
</html>