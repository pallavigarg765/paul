<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>CRM</title>
		<?php	                                       					                                       			 include("header.php"); ?>
		<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="css/base/jquery.ui.all.css" type="text/css">
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.custom.js"></script>
		<script type="text/javascript" src="js/jquery.ui.autocomplete.js"></script>
		<script type="text/javascript">
			jQuery.noConflict(); 
			jQuery(document).ready(function() {
				jQuery("#selectCat").change(function () { 
					var cat = jQuery(this).val();
					if(cat != "0"){
						window.location = "newSorting.php?cat=" + cat;
					}
				})
				jQuery("#txtAddProp").autocomplete({
					source: "autocompleteProperty.php",
					minLength: 3
				});
				
				jQuery(".del").click(function () { 
					var r=confirm("Do You want to delete this record?");
					if (r==true){
						var cat = jQuery("#hidCat").val();
						var propName = jQuery(this).prev().val();
						window.location = "newSorting.php?delete=1&cat=" + cat + "&prop=" + propName;
					}else{
						
					}
				})
			})
		</script>
	</head>
	<body>	
		<?php	                                       					                                       			
		//include('include/security.php');
		//include("header.php");
		$conn = mysql_connect('localhost', 'root', 'Securel0gi!12aa');
		if (!$conn) {
			die('Could not connect: ' . mysql_error());
		}else{
			mysql_select_db('planitlo_locations');
		}
		$current_page = 'newSorting.php';
		
		function array_insert(&$array, $insert, $position) {
			array_splice($array,$position,0,$insert);
		}
		
		function checkThird($s){
			$count = 0;
			for($i = 0; $i < count($s); $i++){
				if($s[$i] != ""){
					$sql = "SELECT prop_name3 FROM prop_info WHERE prop_name = '".$s[$i]."'";
					if($res = mysql_query($sql)){
						$row = mysql_fetch_array($res);
						if($row['prop_name3'] != ""){
							break;
						}
						$count++;
					}else{
						echo mysql_error();
					}
				}
			}
			return $count;
		}
		
		function findLocation($s, $propName){
			$count = 0;
			for($i = 0; $i < count($s); $i++){
				if($s[$i] == $propName){
					break;
				}
				$count++;
			}
			return $count;
		}
		
		function checkIfExist($s, $propName){
			$b = 0;
			for($i = 0; $i < count($s); $i++){
				if($s[$i] == $propName){
					$b = 1;
				}
			}
			return $b;
		}
		
		if(isset($_POST['btnAddProp']) && ($_POST['txtAddProp'] != "")){
			$category = $_POST['hidCat'];
			$propName = $_POST['txtAddProp'];
			$sqlGetSort = "SELECT * FROM sortings WHERE cat_name = '".$category."'";
			if($resGetSort = mysql_query($sqlGetSort)){
				$rowGetSort = mysql_fetch_array($resGetSort);
				$s = explode(",", $rowGetSort['sorting']);
				//echo $sqlUpdateSort;
				//countLocation($s);
				//array_insert($s, $propName, 2);
				$b = checkIfExist($s, $propName);
				if($b == 0){
					$pos = checkThird($s);
					//array_push($s, $propName);
					array_splice($s, $pos, 0, $propName);
					$s1 = implode(",", $s);
					$sqlGetPropLoc = "SELECT prop_loc FROM prop_info WHERE prop_name = '".$propName."'";
					if($resGetPropLoc = mysql_query($sqlGetPropLoc)){
						if(mysql_num_rows($resGetPropLoc) > 0){
							$rowGetPropLoc = mysql_fetch_array($resGetPropLoc);
							$propLoc = $rowGetPropLoc['prop_loc'];
							$sqlInsertSort = "INSERT INTO sorting (prop_loc, category1, Priority, creation_date, modified_date) VALUES ('".$propLoc."', '".$category."', '0', '".date('Y-m-d')."', '".date('Y-m-d')."')";
							if(mysql_query($sqlInsertSort)){
								$sqlUpdateSort = "UPDATE sortings SET sorting = '".$s1."' WHERE cat_name = '".$category."'";
								if(mysql_query($sqlUpdateSort)){
									//header("location: newSorting.php?cat=".$category);
								}else{
									echo mysql_error();
									die;
								}
							}else{
								echo mysql_error();
								die;
							}
						}
					}
				}
			}
		}
		
		if(isset($_GET['delete']) && ($_GET['delete'] == '1') && (isset($_GET['prop']) && ($_GET['prop'] != '')) && (isset($_GET['cat'])) && ($_GET['cat'] != '')){
			$category = $_GET['cat'];
			$propName = $_GET['prop'];
			$sqlGetSort = "SELECT * FROM sortings WHERE cat_name = '".$category."'";
			if($resGetSort = mysql_query($sqlGetSort)){
				$rowGetSort = mysql_fetch_array($resGetSort);
				$s = explode(",", $rowGetSort['sorting']);
				$count = findLocation($s, $propName);
				array_splice($s, $count, 1);
				$s1 = implode(",", $s);
				$sqlUpdateSort = "UPDATE sortings SET sorting = '".$s1."' WHERE cat_name = '".$category."'";
				if(mysql_query($sqlUpdateSort)){
					if(($category != "") && ($propName != "")){
						$sqlGetLoc = "SELECT prop_loc FROM prop_info WHERE prop_name = '".$propName."'";
						if($resGetLoc = mysql_query($sqlGetLoc)){
							if(mysql_num_rows($resGetLoc) > 0 ){
								$rowLoc = mysql_fetch_array($resGetLoc);
								$propLoc = $rowLoc['prop_loc'];
								if($propLoc != ""){
									$sqlOld = "DELETE FROM sorting WHERE prop_loc = '".$propLoc."' AND category1 = '".$category."'";
									//echo $sqlOld;
									if(mysql_query($sqlOld)){
										header("location: newSorting.php?cat=".$category);
									}else{
										echo mysql_error();
										die;
									}
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
			}
		}
		?>		
		<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td colspan="4" align="left" valign="top" nowrap="nowrap">
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
							<tbody>
								<tr>
									<td align="center" valign="top" class="side-nav">
										<? include('sidenav.php');?>
									</td>
									<td align="left" valign="top" class="mar">
										<table cellspacing="3" cellpadding="3" border="0" class="listview" style="width: 350px">
											<tr bgcolor="#d0e1f4">
												<td colspan="2">
													<b style="white-space: nowrap;">New sorting  &nbsp;&nbsp;</b> 
												</td>
											</tr>
											<tr>
												<td>Select category:</td>
												<td>
													<select name="selectCat" id="selectCat" style="width: 200px;">
														<option value="0">-- Select category --</option>
														<?php	                                       					                                       			 
														$sqlGetCategories = "SELECT DISTINCT cat_name FROM sortings ORDER BY cat_name ASC";
														if($resGetCategories = mysql_query($sqlGetCategories)){
															while($rowGetCategories = mysql_fetch_array($resGetCategories)){
																if($rowGetCategories['cat_name'] != ""){
																	if($_GET['cat'] == $rowGetCategories['cat_name']){
																		$selected = "selected = 'selected'";
																	}else{
																		$selected = "";
																	}
																	?>
																	<option <?php	                                       					                                       			 echo $selected ?> value="<?php	                                       					                                       			 echo $rowGetCategories['cat_name'] ?>">
																		<?php	                                       					                                       			 echo $rowGetCategories['cat_name'] ?>
																	</option>
																	<?php	                                       					                                       			
																}
															}
														}
														?>
													</select>
												</td>
											</tr>
										</table>
										<br />
										<?php	                                       					                                       			 
										if(isset($_GET['cat']) && ($_GET['cat'] != "")){
											$cat = mysql_real_escape_string($_GET['cat']);
											?>
											<table cellspacing="3" cellpadding="3" border="0" class="listview" style="width: 350px">
												<tr bgcolor="#d0e1f4">
													<td colspan="2">
														<b style="white-space: nowrap;">Add property to category &nbsp;&nbsp;</b> 
													</td>
												</tr>
												<?php	                                       					                                       			 
												if(isset($b) && ($b == 1)){
													?>
													<tr>
														<td colspan="2" style="color: red">Property already exist.</td>
													</tr>
													<?php	                                       					                                       			 
												}
												?>
												<tr>
													<td>Property name: </td>
													<td>
														<form action="" method="post">
															<input type="hidden" value="<?php	                                       					                                       			 echo $cat; ?>" id="hidCat" name="hidCat" />
															<input type="text" name="txtAddProp" id="txtAddProp" />
															<input type="submit" name="btnAddProp" value="Add" />
														</form>
													</td>
												</tr>
											</table>
											<br />
											<table cellspacing="3" cellpadding="3" border="0" class="listview" style="width: 350px">
												<tr bgcolor="#d0e1f4">
													<td colspan="2">
														<b style="white-space: nowrap;">List of properties  &nbsp;&nbsp;</b> 
													</td>
												</tr>
												<?php	                                       					                                       			 
												$sqlGetSort = "SELECT * FROM sortings WHERE cat_name = '".$cat."'";
												if($resGetSort = mysql_query($sqlGetSort)){
													$rowGetSort = mysql_fetch_array($resGetSort);
													$sort = explode(",", $rowGetSort['sorting']);
													for($i = 0; $i < count($sort); $i++){
														if($sort[$i] != ""){
															?>
															<tr>																
																<td>
																	<?php	                                       					                                       			 echo $sort[$i]; ?>
																</td>
																<td>
																	<input type="hidden" value="<?php	                                       					                                       			 echo $sort[$i]; ?>" />
																	<span class="del" href="newSorting.php?cat=<?php	                                       					                                       			 echo $cat ?>&prop=<?php	                                       					                                       			 echo $sort[$i] ?>&delete=1" style="cursor: pointer;">
																		X
																	</span>
																</td>
															</tr>
															<?php	                                       					                                       			 
														}
													}
												}
												?>
											</table>
											<?php	                                       					                                       			
										}
										?>	
									</td>
								</tr>
								<tr>
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