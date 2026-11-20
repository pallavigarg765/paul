<?
include('include/security.php');
if(($_SESSION['userid'] == 1) || ($_SESSION['userid'] == 15) || ($_SESSION['userid'] == 16) || ($_SESSION['userid'] == 5) || ($_SESSION['userid'] == 7)){
	ini_set('max_execution_time', 0);
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>CRM</title>
			<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
			<style type="text/css">
				.n{
					border: 1px solid #CCCCCC;
				}
			</style>
		</head>
		<body>
			<? include("header.php"); ?>
			<table width="99%" border="0" align="center" cellpadding="0"
				cellspacing="0">
				<tbody>
					<tr>
						<td colspan="4" align="left" valign="top" nowrap="nowrap">
						<table width="100%" border="0" cellpadding="0" cellspacing="0"
							class="border">
							<tbody>
								<tr>
									<td width="100%" align="left" valign="top" class="lastview">
										<b style="white-space: nowrap;">CRM reports</b>
									</td>
								</tr>
								<tr>
									<td align="left" valign="top">
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="center" valign="top" class="side-nav">
												<? include('admin-sidenav.php');?>
											</td>
											<td align="left" valign="top" class="mar">
											<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
												<tbody>
													<tr>
														<td colspan="4" align="center" valign="top" nowrap="nowrap" class="formtxt">
															<form action="" method="post">
																<p>
																	Select one value and the report will show all properties where this field is empty:
																	<br />
																	Info: Only properties from primary website are visible.
																</p>
																<select name="cboSearch">
																 	<option> -- Select -- </option>
																	<option value="address">Address</option>
																	<option value="address2">Address 2</option>
																	<option value="state">State</option>
																	<option value="zipcode">Zipcode</option>
																	<option value="city">City</option>
																	<option value="county">County</option>
																	<option value="country">Country</option>
																	<option value="phone">Phone</option>
																	<option value="email">Email</option>
																	<option value="permit_type">Film Permit Type</option>
																	<option value="neighborhood">Neighborhood</option>
																	<option value="county">County</option>
																	<option value="lat_lng">Latitude/Longitude</option>
																	<option value="cb1">CB1</option>
																	<option value="cb2">CB2</option>
																	<option value="cb3">CB3</option>
																	<option value="cb4">CB4</option>
																	<option value="cb5">CB5</option>
																	<option value="cb6">CB6</option>
																	<option value="cb7">CB7</option>
																	<option value="cb8">CB8</option>
																	<option value="cb9">CB9</option>
																	<option value="cb10">CB10</option>
																</select>
																<input type="submit" name="btnSearch" value="Search" /> 
																<br />
																<?php 
																if(isset($_POST['btnSearch']) && (isset($_POST['cboSearch'])) && ($_POST['cboSearch'] != "")){
																	?>
																	Please wait for the page to finish loading and then <a href="report.xls" target="_blank">Download Excel report</a>
																	<?php 
																}
																?>
																<br />
															</form>
															<?php 
															if(isset($_POST['btnSearch']) && (isset($_POST['cboSearch'])) && ($_POST['cboSearch'] != "")){
																?>
																<br /><br />
																<?php 
																
																$link = mysql_connect("localhost", "root", "Securel0gi!12aa");
																if($link){
																	mysql_select_db("planitlo_crm");
																	$value = $_POST['cboSearch'];
																	$arrCP = array();
																	if($value == "phone"){
																		$sqlProp = "SELECT id FROM property";
																		if($resProp = mysql_query($sqlProp)){
																			while($rowProp = mysql_fetch_array($resProp)){
																				$hasPhone = false;
																				$propId = $rowProp['id'];
																				$sqlCP = "SELECT contact_id FROM contact_to_property WHERE property_id = '".$propId."'";
																				if($resCP = mysql_query($sqlCP)){
																					while($rowCP = mysql_fetch_array($resCP)){
																						$contId = $rowCP['contact_id'];
																						$sqlPh = "SELECT number FROM phone WHERE contact_id = '".$contId."'";
																						if($resPh = mysql_query($sqlPh)){
																							while($row = mysql_fetch_array($resPh)){
																								if($row['number'] != ''){
																									$hasPhone = true;
																								}
																							}
																						}else{
																							echo mysql_error(); die;
																						}
																					}
																				}else{
																					echo mysql_error(); die;
																				}
																				if($hasPhone == false)
																					array_push($arrCP, $propId);
																			}
																		}
																		if(count($arrCP) > 0){
																			$sql = "SELECT * FROM property WHERE id IN (".implode(",", $arrCP).")";
																		}
																	}else if($value == "email"){
																		$sqlProp = "SELECT id FROM property";
																		if($resProp = mysql_query($sqlProp)){
																			while($rowProp = mysql_fetch_array($resProp)){
																				$hasEmail = false;
																				$propId = $rowProp['id'];
																				$sqlCP = "SELECT contact_id FROM contact_to_property WHERE property_id = '".$propId."'";
																				if($resCP = mysql_query($sqlCP)){
																					while($rowCP = mysql_fetch_array($resCP)){
																						$contId = $rowCP['contact_id'];
																						$sqlPh = "SELECT email FROM contact WHERE id = '".$contId."'";
																						if($resPh = mysql_query($sqlPh)){
																							while($row = mysql_fetch_array($resPh)){
																								if($row['email'] != ''){
																									$hasEmail = true;
																								}
																							}
																						}else{
																							echo mysql_error(); die;
																						}
																					}
																				}else{
																					echo mysql_error(); die;
																				}
																				if($hasEmail == false)
																					array_push($arrCP, $propId);
																			}
																		}
																		if(count($arrCP) > 0){
																			$sql = "SELECT * FROM property WHERE id IN (".implode(",", $arrCP).")";
																		}
																	}else{
																		$sql = "SELECT p.* FROM property AS p ";
																		$sql .= "WHERE p.`".$value."` = '' OR p.`".$value."` = '0' OR p.`".$value."` = 'NULL'";	
																	}
																	ob_start();
																	?>
																	<table class="listview">
																		<tr bgcolor="#d0e1f4">
																			<th class="listViewThS1">Property ID</th>
																			<th class="listViewThS1">Company</th>
																			<th class="listViewThS1">Address</th>
																			<th class="listViewThS1">Address 2</th>
																			<th class="listViewThS1">State</th>
																			<th class="listViewThS1">Zipcode</th>
																			<th class="listViewThS1">City</th>
																			<th class="listViewThS1">County</th>
																			<th class="listViewThS1">Country</th>
																		</tr>
																		<?php 
																		if($res = mysql_query($sql)){
																			while($row = mysql_fetch_array($res)){
																				mysql_select_db("planitlo_locations");
																				$badCategories = array("-b", "-c", "-d", "PRV", "Z");
																				$sqlGetCategories = "SELECT category1 FROM sorting WHERE prop_loc = '".$row['property_id']."'";
																				$countPrivate = 0;
																				$allCats = 0;
																				if($resultGetCategories = mysql_query($sqlGetCategories)){
																					$allCats = mysql_num_rows($resultGetCategories);																				
																					while($rowCategories = mysql_fetch_array($resultGetCategories)){
																						if($rowCategories['category1'] != ""){
																							for($l = 0; $l < count($rowCategories); $l++){
																								$lastStr = substr($rowCategories['category1'], -strlen($badCategories[$l]));
																								if($lastStr == $badCategories[$l]){
																									$countPrivate++;
																								}
																							}
																						}
																					}
																				}else{
																					echo mysql_error();
																					die;
																				}
																				mysql_select_db("planitlo_crm");
																				if(($countPrivate != $allCats) && ($allCats != 0)){
																					?>
																					<tr class="grid"> 	 	 	 	
																						<td class="n">
																							<a href="propertydetail.php?id=<?php echo $row['id'] ?>" target="_blank">		
																								<?php echo $row['property_id'] ?>
																							</a>
																						</td>
																						<td class="n"><?php echo $row['company'] ?></td>
																						<td class="n"><?php echo $row['address'] ?></td>
																						<td class="n"><?php echo $row['address2'] ?></td>
																						<td class="n">
																							<?php 
																							$sqlState = "SELECT name FROM state WHERE id = '".$row['state']."'";
																							if($resState = mysql_query($sqlState)){
																								$rowState = mysql_fetch_array($resState);
																								echo $rowState['name'];
																							}
																							?>
																						</td>
																						<td class="n"><?php echo $row['zipcode'] ?></td>
																						<td class="n"><?php echo $row['city'] ?></td>
																						<td class="n">
																							<?php 
																							if(!empty($row['county'])){
																								$sqlCounty = "SELECT name FROM county_new WHERE id_country = '".$row['county']."'";
																								if($resCounty = mysql_query($sqlCounty)){
																									$rowCounty = mysql_fetch_array($resCounty);
																									echo $rowCounty['name'];
																								}
																							}
																							?>
																						</td>
																						<td class="n">
																							<?php 
																							if($row['country'] != '0'){
																								$sqlCountry = "SELECT printable_name FROM country WHERE id = '".$row['country']."'";
																								if($resCountry = mysql_query($sqlCountry)){
																									if(mysql_num_rows($resCountry) > 0){
																										$rowCountry = mysql_fetch_array($resCountry);
																										echo $rowCountry['printable_name'];
																									}
																								}
																							}
																							?>
																						</td>
																					</tr>
																					<?php 
																				}else{
																					//echo $row['property_id']."<br />";
																				}
																			}
																		}
																		?>
																	</table>
																	<?php 
																}
																$export = ob_get_contents();
																$myFile = "report.xls";
																$fh = fopen($myFile, 'w') or die("can't open file");
																fwrite($fh, $export);
																fclose($fh);
																mysql_close($link);
															}
															?>
														</td>
													</tr>
												</tbody>
											</table>
											</td>
										</tr>
									</table>
									</td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
				</tbody>
			</table>
			<? include("footer.php");?>
		</body>
	</html>
<? }
?>