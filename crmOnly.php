<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>CRM</title>
		<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript">
			jQuery.noConflict(); 
			function checkSearch(){
				var s = document.getElementById("searchPropId").value;
				if(s == ""){
					alert("Please enter Property ID to search");
					return false;
				}else{
					return true;
				}
			}
			jQuery(document).ready(function() {
				jQuery(".save").click(function () { 
					var propLoc = jQuery(this).attr("id");
					jQuery.post("ajaxV.php", { 
							propLoc: propLoc,
							task: "saveIntoCrm"
						},function(data){
							if(data != "error"){
								var link = "https://planitlocations.com/crm/editproperty.php?id=" + data;
								jQuery("."+propLoc).html("Saved into crm. <a href='" + link + "' target='_blank'>Edit property</a>");
							}else{
								alert("Error.");
							}
					});
				})
			})
		</script>
	</head>
	<body>	
		<?php	                                       					                                       			
		include('include/security.php');
		include("header.php");
		$conn = mysql_connect('localhost', 'root', 'Securel0gi!12aa');
		if (!$conn) {
			die('Could not connect: ' . mysql_error());
		}else{
			mysql_select_db('planitlo_locations');
		}
		$current_page = 'webOnly.php';
		?>		
		<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td colspan="4" align="left" valign="top" nowrap="nowrap"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
						<tbody>
							<tr>
								<td width="100%" align="left" valign="top" class="lastview">
									<b style="white-space: nowrap;">Web only  &nbsp;&nbsp;</b> 
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
												<?php	                                       					                                       			
												if(isset($_GET['searchPropId']) && ($_GET['searchPropId'] != '')){
													$arrLoc = array();
													$searchProp = mysql_real_escape_string($_GET['searchPropId']);
													$sqlLoc = "SELECT prop_loc FROM prop_info WHERE prop_loc = '".$searchProp."'";
													if($resLoc = mysql_query($sqlLoc)){
														while($rowLoc = mysql_fetch_array($resLoc)){
															if($rowLoc['prop_loc'] != ""){
																array_push($arrLoc, trim(strtolower($rowLoc['prop_loc'])));
															}
														}
													}else{
														echo mysql_error();
														die;
													}
													$arr = $arrLoc;
												}else{
													$arrLoc = array();
													$sqlLoc = "SELECT prop_loc FROM prop_info";
													if($resLoc = mysql_query($sqlLoc)){
														while($rowLoc = mysql_fetch_array($resLoc)){
															if($rowLoc['prop_loc'] != ""){
																array_push($arrLoc, trim(strtolower($rowLoc['prop_loc'])));
															}
														}
													}else{
														echo mysql_error();
														die;
													}
													
													mysql_select_db('planitlo_crm');
													$arrCrm = array();
													$sqlCrm = "SELECT property_id FROM property";
													if($resCrm = mysql_query($sqlCrm)){
														while($rowCrm = mysql_fetch_array($resCrm)){
															if($rowCrm['property_id'] != ""){
																array_push($arrCrm, trim(strtolower($rowCrm['property_id'])));
															}
														}
													}else{
														echo mysql_error();
														die;
													}
												
													$arrDiff = array();
													
													for($i = 0; $i < count($arrCrm); $i++){
														if($arrLoc[$i] != ""){
															if(!in_array($arrCrm[$i], $arrLoc)){
																array_push($arrDiff, $arrCrm[$i]);
															}
														}
													}
													
													$arr = array();
													$arr = $arrDiff;
												}
												?>
												<form action="" method = "get">
													<table cellspacing="3" cellpadding="3" border="0" class="listview" style="width: 350px">
														<tr bgcolor="#d0e1f4">
															<td colspan="2">
																<b>Search by Property ID</b>
															</td>
														</tr>
														<tr>
															<td>Property ID:</td>
															<td>
																<input type="text" id="searchPropId" name="searchPropId" />
															</td>
														</tr>
														<tr>
															<td></td>
															<td>
																<input type="submit" onclick="return checkSearch();" class="h" style="height: 25px; display: inline;" value="Search" name="search" />
																<a style="margin-left: 10px;" href="webOnly.php">Reset search</a>
															</td>
														</tr>
													</table>
												</form>
												<table cellspacing="3" cellpadding="3" border="0" class="listview" style="width: 350px">
													<tr bgcolor="#d0e1f4">
														<td colspan="2">
															<b><?php	                                       					                                       			 echo "Number of missing properties in crm: ".count($arr); ?></b>
														</td>
													</tr>
													<?php	                                       					                                       			 
													for($i = 0; $i < count($arr); $i++){
														?> 
														<tr>
															<td>
																<a href="https://planitlocations.com/search/searchid.php?id=<?php	                                       					                                       			 echo $arr[$i] ?>&id6=&id2=&id7=&id3=&id8=&id4=&id9=&id5=&id10=&B1=Submit" target="_blank">
																	<?php	                                       					                                       			 echo $arr[$i]."<br />"; ?>
																</a>
															</td>
															<td class="<?php	                                       					                                       			 echo $arr[$i]; ?>">
																<input type="button" value="Save to crm" class="save h" style="height: 25px;" id="<?php	                                       					                                       			 echo $arr[$i]; ?>" />
															</td>
														</tr>
														<?php	                                       					                                       			 
													}
													?>
												</table>
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