<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>CRM</title>
		<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="http://api.maps.yahoo.com/ajaxymap?v=3.8&appid=YD-eQRpTl0_JX2E95l_xAFs5UwZUlNQhhn7lj1H"></script>
		<style type="text/css">
			#map{
				height: 100%;
				width: 100%;
			}
		</style>
	</head>
	<body>	
		<?php 
		ini_set("max_execution_time", 0);
		include('include/security.php');
		include("header.php");
		$conn = mysql_connect('localhost', 'root', 'Securel0gi!12aa');
		if (!$conn) {
			die('Could not connect: ' . mysql_error());
		}else{
			mysql_select_db('planitlo_crm');
		}
		$current_page = 'yahooMap.php';
		
		//search by property id
		if(isset($_GET['propId']) && ($_GET['propId'] != '')){
			$propId = $_GET['propId'];
			$sql = "SELECT property_id, address, zipcode, city FROM property WHERE property_id = '".$propId."' LIMIT 1";
			if($res = mysql_query($sql)){
				if(mysql_num_rows($res) > 0){
					$row = mysql_fetch_assoc($res);
					$address = $row['address'];
					$zipcode = $row['zipcode'];
					$city = $row['city'];
					$marker = "";
					if(($address == '0') || ($city == '0') || ($zipcode == '0')){
						$marker = "Not enough data to create marker on map. Map requires address, city and zipcode.";
					}else{
						$marker = $address." ".$city." ".$zipcode;
						$jsonRow = json_encode($row);
					}
				}else{
					$marker = "No property found.";
				}
			}else{
				$marker = mysql_error();
				die;
			}
		}
		//search by city name
		if(isset($_POST['city']) && ($_POST['city'] != '')){
			$city = $_POST['city'];
			$arrCats = $_POST['categories'];
			$cats = implode("', '", $arrCats);
			$arrBad = array();
			$arrGood = array();
			$sql = "SELECT property_id, address, zipcode, city FROM property WHERE city = '".$city."'";
			if($res = mysql_query($sql)){
				if(mysql_num_rows($res) > 0){
					while($row = mysql_fetch_assoc($res)){
						$address = $row['address'];
						$zipcode = $row['zipcode'];
						$city = $row['city'];
						$propId = $row['property_id'];
						$marker = "";
						if(($address == '0') || ($city == '0') || ($zipcode == '0') || ($propId == '')){
							array_push($arrBad, $row['property_id']);
						}else{
							if($cats != ""){
								mysql_select_db("planitlo_locations");
								$sqlCat = "SELECT category1 FROM sorting WHERE prop_loc = '".$propId."' AND category1 IN ('".$cats."');";
								if($resCat = mysql_query($sqlCat)){
									if(mysql_num_rows($resCat) > 0){
										$marker = $address." ".$city." ".$zipcode;
										array_push($arrGood, $row['property_id']);
										$rows[] = $row;
									}else{
									}
								}else{
									echo mysql_error();
									die;
								}
								mysql_select_db("planitlo_crm");
							}
						}
					}
				}else{
					$marker = "No property found.";
				}
			}else{
				$marker = mysql_error();
				die;
			}
			$jsonRow = json_encode($rows);
		}
		
		
		//search by zipcode
		if(isset($_POST['zipcode']) && ($_POST['zipcode'] != '')){
			$zipcode = $_POST['zipcode'];
			$d = $_POST['radius'];
			$arrCats = $_POST['categories'];
			$cats = implode("', '", $arrCats);
			$arrBad = array();
			$arrGood = array();
			$sql = "SELECT * FROM zip_codes WHERE zip = '".$zipcode."'";
			if($res = mysql_query($sql)){
				if(mysql_num_rows($res) > 0){
					$row = mysql_fetch_array($res);
					$lat1 = $row['latitude'];
					$lon1 = $row['longitude'];
					$r = 3959;
					$latN = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(0))));
					$latS = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(180))));
					$lonE = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(90)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
					$lonW = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(270)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
					$sql1 = "SELECT * FROM zip_codes WHERE (latitude <= ".$latN." AND latitude >= ".$latS." AND longitude <= ".$lonE." AND longitude >= ".$lonW.") 
							AND (latitude <> ".$lat1." AND longitude <> ".$lon1.") ORDER BY state, city, latitude, longitude";
					if($res1 = mysql_query($sql1)){
						if(mysql_num_rows($res1) > 0){
							$sqlAll = "SELECT property_id, address, zipcode, city FROM property WHERE (";
							while($row1 = mysql_fetch_array($res1)) {
								$sqlAll .= " zipcode = '".$row1['zip']."' OR";  					
							}
							$sqlAll = substr($sqlAll, 0, -2);
							$sqlAll .= ")";
							if($resAll = mysql_query($sqlAll)){
								if(mysql_num_rows($resAll) > 0){
									while($rowAll = mysql_fetch_assoc($resAll)){
										$address = $rowAll['address'];
										$zipcode = $rowAll['zipcode'];
										$city = $rowAll['city'];
										$propId = $rowAll['property_id'];
										$marker = "";
										if(($address == '0') || ($city == '0') || ($zipcode == '0') || ($propId == '')){
											array_push($arrBad, $propId);
										}else{
											if($cats != ""){
												mysql_select_db("planitlo_locations");
												$sqlCat = "SELECT category1 FROM sorting WHERE prop_loc = '".$propId."' AND category1 IN ('".$cats."');";
												if($resCat = mysql_query($sqlCat)){
													if(mysql_num_rows($resCat) > 0){
														$marker = $address." ".$city." ".$zipcode;
														array_push($arrGood, $propId);
														$rows[] = $rowAll;
													}else{
													}
												}else{
													echo mysql_error();
													die;
												}
												mysql_select_db("planitlo_crm");
											}
										}
									}
								}else{
									$marker = "No property found.";
								}
							}else{
								echo mysql_error();
								die;
							}
						}
					}else{
						echo mysql_error();
						die;
					}
				}
			}else{
				echo mysql_error();
				die;
			}
			$jsonRow = json_encode($rows);
		}
		
		//excluded 
		$excluded = "";
		$arrExcluded = array();
		if(isset($_POST['filter'])){
			$arrExcluded = $_POST['filter'];
		}
		
		//get categories 
		mysql_select_db("planitlo_locations");
		if(count($arrExcluded) > 0){
			$sql = "SELECT DISTINCT category1 FROM sorting WHERE category1 <> '' AND ";
			foreach($arrExcluded as $ex){
				if($ex == "Event"){
					$sql .= "category1 NOT LIKE '".$ex."%' AND ";
				}else{
					$sql .= "category1 NOT LIKE '%".$ex."' AND ";
				}
			}
			$sql = substr($sql, 0, -5);
			$sql .= "ORDER BY category1 ASC;";
		}else{
			$sql = "SELECT DISTINCT category1 FROM sorting WHERE category1 <> '' ORDER BY category1 ASC;";
		}
		if($res = mysql_query($sql)){
			
		}else{
			echo mysql_error();
			die;
		}
		mysql_select_db("planitlo_crm");
		?>
		<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td colspan="4" align="left" valign="top" nowrap="nowrap">
					  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
						<tbody>
							<tr>
								<td width="100%" align="left" valign="top" class="lastview">
									<b style="white-space: nowrap;">Yahoo map  &nbsp;&nbsp;</b> 
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
												<p style="color: red; font-size: 13px; padding-bottom: 15px;">Map generating might take long time to load. Please wait.</p>
												<div class="listview" style="padding: 5px;">
													<div class="listview" style="padding: 5px;background-color: #d0e1f4">
														<b>Filters for categories</b>
													</div>
													<div class="listview" style="padding: 5px;">
														<form action="" method="post">
															Exclude: 
															<input type="checkbox" <?php if(in_array("-a", $arrExcluded)) echo "checked='checked';"; ?> name="filter[]" value="-a" />
															-a 
															&nbsp;&nbsp;
															<input type="checkbox" <?php if(in_array("-b", $arrExcluded)) echo "checked='checked';"; ?> name="filter[]" value="-b" />
															-b 
															&nbsp;&nbsp;
															<input type="checkbox" <?php if(in_array("-c", $arrExcluded)) echo "checked='checked';"; ?> name="filter[]" value="-c" />
															-c 
															&nbsp;&nbsp;
															<input type="checkbox" <?php if(in_array("-d", $arrExcluded)) echo "checked='checked';"; ?> name="filter[]" value="-d" />
															-d 
															&nbsp;&nbsp;
															<input type="checkbox" <?php if(in_array("prv", $arrExcluded)) echo "checked='checked';"; ?> name="filter[]" value="prv" />
															prv 
															&nbsp;&nbsp;
															<input type="checkbox" <?php if(in_array("Z", $arrExcluded)) echo "checked='checked';"; ?> name="filter[]" value="Z" />
															Z 
															&nbsp;&nbsp;
															<input type="checkbox" <?php if(in_array("Event", $arrExcluded)) echo "checked='checked';"; ?> name="filter[]" value="Event" />
															Event 
															&nbsp;&nbsp;
															<input type="submit" value="Exclude" name="exclude" id="exclude" />
														</form>
													</div>
												</div>
												<br />
												<form action="" method="get">
													<table cellspacing="3" cellpadding="3" border="0" class="listview" style="width: 350px; float: left">
														<tr bgcolor="#d0e1f4">
															<td colspan="2">
																<b>Search by Property ID</b>
															</td>
														</tr>
														<tr>
															<td>Property ID:</td>
															<td>
																<input type="text" value="<?php if($_GET['propId']) echo $_GET['propId']; ?>" name="propId" />
															</td>
														</tr>
														<tr>
															<td></td>
															<td>
																<input type="submit" value="Search" />
															</td>
														</tr>
													</table>
												</form>
												<form action="" method="post">
													<table cellspacing="3" cellpadding="3" border="0" class="listview" style="width: 350px; float: left">
														<tr bgcolor="#d0e1f4">
															<td colspan="2">
																<b>Search by zipcode</b>
															</td>
														</tr>
														<tr>
															<td>
																Zipcode: 
															</td>
															<td>
																<input type="text" name="zipcode" />
															</td>
														</tr>
														<tr>
															<td>Radius:</td>
															<td>
																<select name="radius" style="width: 150px;">
																	<option value="2">Exact</option>
																	<option>5</option>
																	<option>10</option>
																</select>
															</td>
														</tr>
														<tr>
															<td>
															Categories:
															</td>
															<td>
																<select name="categories[]" multiple style="width: 150px; border: 1px solid #AAAAAA">
																	<?php
																	while($row = mysql_fetch_array($res)){
																		?>
																		<option value="<?php echo trim($row['category1']); ?>">
																			<?php echo trim($row['category1']); ?>
																		</option>
																		<?php
																	}	
																	?>
																</select>
															</td>
														</tr>
														<tr>
															<td>
															</td>
															<td>
																<input type="submit" value="Search" />	
															</td>
														</tr>
													</table>
												</form>
												<form action="" method="post">
													<table cellspacing="3" cellpadding="3" border="0" class="listview" style="width: 350px; float: left">
														<tr bgcolor="#d0e1f4">
															<td colspan="2">
																<b>Search by city</b>
															</td>
														</tr>
														<tr>
															<td>City:</td>
															<td>
																<input type="text" name="city" />
															</td>
														<tr>
														<tr>
															<td>Categories:</td>
															<td>
																<select name="categories[]" multiple style="width: 150px; border: 1px solid #AAAAAA">
																	<?php
																	mysql_data_seek($res, 0);
																	while($row = mysql_fetch_array($res)){
																		?>
																		<option value="<?php echo trim($row['category1']); ?>">
																			<?php echo trim($row['category1']); ?>
																		</option>
																		<?php
																	}	
																	?>
																</select>
															</td>
														</tr>
														<tr>
															<td></td>
															<td>
																<input type="submit" value="Search" />
															</td>
														</tr>
													</table>
												</form>
												<br style="clear:both" />
												<div style="padding-top: 10px; float: left; width: 500px;">
													<div id="map"></div>
													<script type="text/javascript">
														var jsonRows = <?php if(isset($jsonRow)) { echo $jsonRow; } else { echo "''"; } ?>;
														if(jsonRows != ""){
															var map = new YMap(document.getElementById('map')); 
															function startMap(){
																map.addTypeControl();
																map.addZoomLong();
																map.addPanControl();  
																
																if(jsonRows.length > 0){
																	for(i in jsonRows){
																		var myMarker;
																		var myImage = new YImage();
																		myImage.src = 'https://planitlocations.com/crm/images/marker.png';
																		myImage.size = new YSize(110,33);
																		myMarker = new YMarker(jsonRows[i].address+" "+jsonRows[i].city+" "+jsonRows[i].zipcode, myImage);
																		myMarker.addLabel(" " + jsonRows[i].property_id);
																		map.addOverlay(myMarker);
																	}
																	map.drawZoomAndCenter(jsonRows[0].address+" "+jsonRows[0].city+" "+jsonRows[0].zipcode, 2);
																}else{
																	map.drawZoomAndCenter(jsonRows.address+" "+jsonRows.city+" "+jsonRows.zipcode, 2);
																	var myMarker;
																	var myImage = new YImage();
																	myImage.src = 'https://planitlocations.com/crm/images/marker.png';
																	myImage.size = new YSize(110,33);
																	myMarker = new YMarker(jsonRows.address+" "+jsonRows.city+" "+jsonRows.zipcode, myImage);
																	myMarker.addLabel(" " + jsonRows.property_id);
																	map.addOverlay(myMarker);
																}
																
																function reportPosition(_e, _c){
																	var mapCoordCenter = map.convertLatLonXY(map.getCenterLatLon());
																	YLog.initPos(mapCoordCenter);
																	var currentGeoPoint = new YGeoPoint( _c.Lat, _c.Lon );
																	map.addMarker(currentGeoPoint);
																}
															}
															window.onload = startMap;
														}
													</script>
												</div>
												<div style="padding: 10px; float: left; width: 150px; color: #656363; font-size: normal">
													<?php 
													if(isset($_GET['propId']) && ($_GET['propId'] != '')){
														echo $marker;
													}
													if((isset($_POST['city']) && ($_POST['city'] != '')) || ((isset($_POST['zipcode'])) && ($_POST['zipcode'] != ''))){
														echo "<b>Marked properties:</b><br />";
														foreach($arrGood as $good){
															echo $good."<br />";
														}
													}
													?>
												</div>
												<div style="padding: 10px; float: left; width: 150px; color: #656363; font-size: normal">
													<?php
													if((isset($_POST['city']) && ($_POST['city'] != '')) || ((isset($_POST['zipcode']) && ($_POST['zipcode'] != '')))){
														echo "<b>Skipped properties:</b><br />";
														foreach($arrBad as $bad){
															echo $bad."<br />";
														}
													}
													?>
												</div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<?php include("footer.php");?>
			</td>
		</table>
	</body>
</html>
