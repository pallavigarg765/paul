<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>CRM</title>
		<?php	                                       					                                       			 
		include('include/security.php');
		include("header.php");
		?>
		
		<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="css/base/jquery.ui.all.css" type="text/css">
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.custom.js"></script>
		<script type="text/javascript" src="js/jquery.ui.autocomplete.js"></script>
		<script type="text/javascript">
			jQuery.noConflict(); 
			jQuery(document).ready(function() {
			
				function newRow(category, sorting){
					var html = "";
					html += "<tr><td>" + category;
					html += "<input type='hidden' value='" + category + "' name='hidCat[]' />";
					html += "</td><td>" + sorting;
					html += "<input type='hidden' value='"+ sorting + "' name='hidSort[]' />";
					html += "</td><td>";
					html += "<span class='delRow' style='cursor: pointer'>x</span>";
					html += "</td></tr>";
					return html;
				}
			
				function is_int(value){
					if((parseFloat(value) == parseInt(value)) && !isNaN(parseInt(value))){
						return true;
					} else {
						return false;
					}
				}
			
				jQuery("#txtCategory").autocomplete({
					source: "autocomplete.php",
					minLength: 3
				});
			
				jQuery(".clearValue").click(function () { 
					jQuery(this).parent().prev().children().val("");
				})
				
				function clearTexts(){
					jQuery("#wsPropDomLoc").val("");
					jQuery("#wsPropName").val("");
					jQuery("#wsThumbsDomLoc").val("");
					jQuery("#wsThumb1").val("");
					jQuery("#wsThumb2").val("");
					jQuery("#wsThumb3").val("");
					jQuery("#wsPropName2").val("");
					jQuery("#wsPropName3").val("");
					jQuery("#wsAd").val("");
				}
				
				jQuery("#btnFill").click(function () { 
					var propLoc = jQuery("#wsPropLoc").val();
					
					if(propLoc != ""){
						jQuery.post("ajaxV.php", { 
								propLoc: propLoc,
								task: "checkIfExist"
							},function(data){
								if(data == "1"){
									alert("Property already exist.");
									clearTexts();
									//window.location = "webOnly.php?searchPropId=" + propLoc + "&search=Search";
								}else{
									jQuery("#wsPropDomLoc").val("http://www.planitserver.com/");
									jQuery("#wsPropName").val(propLoc.substr(0, 1).toUpperCase() + propLoc.substr(1) + " <~Property ID");
									jQuery("#wsThumbsDomLoc").val("http://www.planitserver.com/thumbs/");
									jQuery("#wsThumb1").val("tn_" + propLoc + "_01.jpg");
									jQuery("#wsThumb2").val("tn_" + propLoc + "_02.jpg");
									jQuery("#wsThumb3").val("tn_" + propLoc + "_03.jpg");
									jQuery("#wsPropName2").val("* Click On Photos Below *");
									jQuery("#wsPropName3").val("");
									jQuery("#wsAd").val("0");
								}
								jQuery("#exist").val(data);
						});
					}else{
						clearTexts();
					}
				})
				
				jQuery("#btnAdd").click(function () { 
					var category = jQuery("#txtCategory").val();
					var sorting = jQuery("#txtSorting").val();
					if(category != ""){
						if(is_int(sorting)){
							var newRecord = newRow(category, sorting);
							jQuery("#topRow").after(newRecord);
							jQuery("#txtCategory").val("");
							jQuery("#txtSorting").val("");
						}else{
							alert("Sorting value is not a proper number.");
						}
					}else{
						alert("Category is empty.");
					}
				})
				
				jQuery(".delRow").live("click", function () { 
					jQuery(this).parent().parent().remove();
				})
				
				jQuery("#checkCat").click(function () { 
					if(jQuery("#txtCategory").val() != ""){
						var category = jQuery("#txtCategory").val();
						var url = "https://planitlocations.com/crm/checkCategory.php?cat=" + category;
						new_win = window.open(url,'_blank'); 
					}
				})
				
				
			})
		</script>
	</head>
	<body>	
		<?php	                                       					                                       			
		$conn = mysql_connect('localhost', 'root', 'Securel0gi!12aa');
		if (!$conn) {
			die('Could not connect: ' . mysql_error());
		}else{
			mysql_select_db('planitlo_locations');
		}
		$current_page = 'saveToWeb.php';
		
		//save button pressed
		if(isset($_POST['saveToLoc']) && ($_POST['wsPropLoc'] != "")){
			if(($_POST['wsPropDomLoc'] != '') && ($_POST['wsPropName'] != '') && ($_POST['wsThumbsDomLoc'] != '') && ($_POST['wsThumb1'] != '') && ($_POST['wsThumb2'] != '') && ($_POST['wsThumb3'] != '') && ($_POST['wsAd'] != '')){
				$propLoc =  mysql_real_escape_string($_POST['wsPropLoc']);
				$propDomLoc = mysql_real_escape_string($_POST['wsPropDomLoc']);
				$propName = mysql_real_escape_string($_POST['wsPropName']);
				$thumbsDomLoc = mysql_real_escape_string($_POST['wsThumbsDomLoc']);
				$thumb1 = mysql_real_escape_string($_POST['wsThumb1']);
				$thumb2 = mysql_real_escape_string($_POST['wsThumb2']);
				$thumb3 = mysql_real_escape_string($_POST['wsThumb3']);
				$propName2 = mysql_real_escape_string($_POST['wsPropName2']);
				$propName3 = mysql_real_escape_string($_POST['wsPropName3']);
				$ad = mysql_real_escape_string($_POST['wsAd']);
				
				$sql = "INSERT INTO prop_info (prop_name, prop_domain_loc, prop_loc, tn_domain_loc, thumb01, thumb02, thumb03, prop_name2, prop_name3, ad, creation_date, modified_date) VALUES 
					('".$propName."', '".$propDomLoc."', '".$propLoc."', '".$thumbsDomLoc."', '".$thumb1."', '".$thumb2."', '".$thumb3."', '".$propName2."', '".$propName3."', '".$ad."', '".date('Y-m-d')."', '".date('Y-m-d')."')";
				//echo $sql."<br />";
				if($res = mysql_query($sql)){
					
				}else{
					echo mysql_error();
					die;
				}
				//save sorting
				for($i = 0; $i < count($_POST['hidCat']); $i++){
					$category = mysql_real_escape_string($_POST['hidCat'][$i]);
					$sorting = mysql_real_escape_string($_POST['hidSort'][$i]);
					$sqlSort = "INSERT INTO sorting (prop_loc, category1, Priority, creation_date, modified_date) 
						VALUES ('".$propLoc."', '".$category."', '".$sorting."', '".date('Y-m-d')."', '".date('Y-m-d')."')";
					// $sqlSort."<br />";
					if($resSort = mysql_query($sqlSort)){
					
					}else{
						echo mysql_error();
						die;
					}
				}
				
				mysql_select_db('planitlo_crm');
				$sqlCrm = "INSERT INTO property (property_id) VALUES ('".$propLoc."')";
				if(mysql_query($sqlCrm)){
				}else{
					echo mysql_error();
					die;
				}
				
				$success = 1; 
			}else{
				echo "Fill all required fields.";
			}
		}
		?>
		<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td colspan="4" align="left" valign="top" nowrap="nowrap"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
						<tbody>
							<tr>
								<td width="100%" align="left" valign="top" class="lastview">
									<b style="white-space: nowrap;">Save to website only  &nbsp;&nbsp;</b> 
								</td>
							</tr>
							<tr>
								<td align="left" valign="top">
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="center" valign="top" class="side-nav">
												<? include('sidenav.php');?>
											</td>
											<td align="left" valign="top" class="mar">
												<?php	                                       					                                       			 if(isset($success) && $success != ""){ ?>
													<table cellspacing="3" cellpadding="3" border="0" class="listview">
														<tr bgcolor="#d0e1f4">
															<?php	                                       					                                       			 
															$sqlGetId = "SELECT id FROM property WHERE property_id = '".$propLoc."'";
															if($resGetId = mysql_query($sqlGetId)){
																$rowGetId = mysql_fetch_array($resGetId);
																$crmId = $rowGetId['id'];
															}else{
																echo mysql_error();
															}
															mysql_select_db('planitlo_locations');
															?>
															<td><b>Saved. Click <a href="editproperty.php?id=<?php	                                       					                                       			 echo $crmId ?>">here</a> if you want to create a crm profile for this property.</b></td>
														</tr>
													</table>
												<?php	                                       					                                       			 } ?>
												<form action="" method="post" name="formAdd" id="formAdd">
													<input type="hidden" value="0" id="exist" />
													<table cellspacing="3" cellpadding="3" border="0" class="listview" style="width: 350px">
														<tr bgcolor="#d0e1f4">
															<td colspan="2">
																<b>Save to website only</b> - <span style="color: red;"><b>Notice: Please upload thumbs first</b></span>
															</td>
														</tr>
														<tr>
															<td>Property location:</td>
															<td>
																<input type="text" class="form" name="wsPropLoc" id="wsPropLoc" style="width: 300px;" />
															</td>
															<td><!-- <span class="clearValue" style="cursor: pointer">x</span> --></td>
														</tr>
														<tr>
															<td colspan="3">
																Fill the rest of the fields with sample data:
																<input type="button" value="Fill" name="btnFill" id="btnFill" />
															</td>
														</tr>
														<tr>
															<td>Property domain location:</td>
															<td>
																<input type="text" class="form" name="wsPropDomLoc" id="wsPropDomLoc" style="width: 300px;" />
															</td>
															<td><span class="clearValue" style="cursor: pointer">x</span></td>
														</tr>
														<tr>
															<td>Property name:</td>
															<td>
																<input type="text" class="form" name="wsPropName" id="wsPropName" style="width: 300px;" />
															</td>
															<td><span class="clearValue" style="cursor: pointer">x</span></td>
														</tr>
														<tr>
															<td>Thumbs domain location:</td>
															<td>
																<input type="text" class="form" name="wsThumbsDomLoc" id="wsThumbsDomLoc" style="width: 300px;" />
															</td>
															<td><span class="clearValue" style="cursor: pointer">x</span></td>
														</tr>
														<tr>
															<td>Thumbnail 1:</td>
															<td>
																<input type="text" class="form" name="wsThumb1" id="wsThumb1" style="width: 300px;" />
															</td>
															<td><span class="clearValue" style="cursor: pointer">x</span></td>
														</tr>
														<tr>
															<td>Thumbnail 2:</td>
															<td>
																<input type="text" class="form" name="wsThumb2" id="wsThumb2" style="width: 300px;" />
															</td>
															<td><span class="clearValue" style="cursor: pointer">x</span></td>
														</tr>
														<tr>
															<td>Thumbnail 3:</td>
															<td>
																<input type="text" class="form" name="wsThumb3" id="wsThumb3" style="width: 300px;" />
															</td>
															<td><span class="clearValue" style="cursor: pointer">x</span></td>
														</tr>
														<tr>
															<td>Property name 2:</td>
															<td>
																<input type="text" class="form" name="wsPropName2" id="wsPropName2" style="width: 300px;" />
															</td>
															<td><span class="clearValue" style="cursor: pointer">x</span></td>
														</tr>
														<tr>
															<td>Property name 3:</td>
															<td>
																<input type="text" class="form" name="wsPropName3" id="wsPropName3" style="width: 300px;" />
															</td>
															<td><span class="clearValue" style="cursor: pointer">x</span></td>
														</tr>
														<tr>
															<td>Ad:</td>
															<td>
																<input type="text" class="form" name="wsAd" id="wsAd" style="width: 300px;" />
															</td>
															<td><span class="clearValue" style="cursor: pointer">x</span></td>
														</tr>
														<tr bgcolor="#d0e1f4">
															<td colspan="2">
																<b>Categories and sorting</b>
															</td>
														</tr>
														<tr id="topRow">
															<td>
																Category: 
																<input type="text" value="" class="category" name="txtCategory" id="txtCategory" />
															</td>
															<td>
																Sorting: 
																<input type="text" value="" name="txtSorting" id="txtSorting" size="15" />
																&nbsp;&nbsp;<span style="cursor: pointer" id="checkCat">Check category</span>
																&nbsp;&nbsp;<input type="button" value="Add" id="btnAdd" />
															</td>
														</tr>
														<tr>
															<td>
																
															</td>
															<td align="right">															
																<input type="submit" name="saveToLoc" value="Save" onClick="return checkformAdd();" />
															</td>
														</tr>
													</table>
												</form>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<? include("footer.php");?>
			</td>
		</table>
	</body>
</html>