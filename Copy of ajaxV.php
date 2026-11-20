<?php	                                       					                                       			 
include('include/dbcon.php');
$db = new DBConn();

$dbUser = "root";
$dbPass = "Securel0gi!12aa";
$dbHost = "localhost";


function getPropLocByName($propName){
	$return = "";
	$sqlPropLoc = "SELECT prop_loc FROM prop_info WHERE prop_name = '".$propName."'";
	if($resPropLoc = mysql_query($sqlPropLoc)){
		if(mysql_num_rows($resPropLoc) > 0){
			$rowPropLoc = mysql_fetch_array($resPropLoc);
			$return = $rowPropLoc['prop_loc'];
		}else{
			$return = "0";
		}
	}else{
		$return = "-1";
	}
	if(($return != "0") && ($return != "-1")){
		return $return;
	}else{
		return "";
	}
}

function array_insert(&$array, $insert, $position) {
	array_splice($array,$position,0,$insert);
}

/*
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
*/

function checkThird($s, $propName){
	$count = 0;
	
	$sqlPN = "SELECT prop_name3 FROM prop_info WHERE prop_name = '".$propName."'";
	if($resPN = mysql_query($sqlPN)){
		if(mysql_num_rows($resPN) > 0){
			$rowPN = mysql_fetch_array($resPN);
			$what = $rowPN['prop_name3'];
			for($i = 0; $i < count($s); $i++){
				if($s[$i] != ""){
					$sql = "SELECT prop_name3 FROM prop_info WHERE prop_name = '".$s[$i]."'";
					if($res = mysql_query($sql)){
						$row = mysql_fetch_array($res);
						if(($what == "") || ($what == "-")){ //find first dash
							if($row['prop_name3'] != ""){
								break;
							}
						}else{ //find first city 
							if(($row['prop_name3'] != "") && ($row['prop_name3'] != "-")){
								break;
							}
						}
						$count++;
					}else{
						echo mysql_error();
					}
				}
			}
		}else{
			
		}
	}else{
		echo mysql_error();
		die;
	}
	return $count;
}

function findLocation($s, $propName){
	$count = 0;
	for($i = 0; $i < count($s); $i++){
		if(strtolower($s[$i]) == strtolower($propName)){
			break;
		}
		$count++;
	}
	return $count;
}

function checkIfExist($s, $propName){
	$b = 0;
	for($i = 0; $i < count($s); $i++){
		if(strtolower($s[$i]) == strtolower($propName)){
			$b = 1;
		}
	}
	return $b;
}

if(isset($_POST['task'])){
	if($_POST['task'] == "saveNewSorting"){
		mysql_select_db("planitlo_locations");
		if(isset($_POST['id']) && ($_POST['id'] != '') && isset($_POST['value'])){
			$sql = "UPDATE sorting SET Priority = '".$_POST['value']."', modified_date = '".date('Y-m-d')."' WHERE ID = '".$_POST['id']."'";
			if(mysql_query($sql)){
				echo $_POST['value'];
			}else{
				echo mysql_error();
			}
		}else{
			echo "error";
		}
	}
	if($_POST['task'] == "addSorting"){
		mysql_select_db("planitlo_locations");
		if(isset($_POST['category']) && isset($_POST['sorting']) && isset($_POST['propLoc'])){
			$sql = "INSERT INTO sorting (prop_loc, category1, Priority, creation_date, modified_date) VALUES ('".$_POST['propLoc']."', '".$_POST['category']."', '".$_POST['sorting']."', '".date('Y-m-d')."', '".date('Y-m-d')."');";
			if(mysql_query($sql)){
				echo "Saved.";
			}else{
				echo mysql_error();
			}
		}else{
			echo "error";
		}
	}
	if($_POST['task'] == "deleteSorting"){
		$link = mysql_connect('localhost', 'root', 'Securel0gi!12aa');
		mysql_select_db("planitlo_locations", $link);
		if(isset($_POST['id']) && ($_POST['id'] != '')){
			$sql = "DELETE FROM sorting WHERE ID = '".$_POST['id']."'";
			if(mysql_query($sql)){
				echo "Deleted.";
			}else{
				echo mysql_error();
			}
		}else{
			echo "error";
		}
		mysql_close($link);
	}
	if($_POST['task'] == "deleteToLoc"){
		$link = mysql_connect('localhost', 'root', 'Securel0gi!12aa');
		mysql_select_db("planitlo_locations", $link);
		if(isset($_POST['propLoc']) && ($_POST['propLoc'] != '')){
			$sql = "DELETE FROM prop_info WHERE prop_loc = '".mysql_real_escape_string($_POST['propLoc'])."'";
			if(mysql_query($sql)){
				$sql = "DELETE FROM sorting WHERE prop_loc = '".mysql_real_escape_string($_POST['propLoc'])."'";
				if(mysql_query($sql)){
					echo "ok";
				}else{
					echo mysql_error();
				}
			}else{
				echo mysql_error();
			}
			
		}
		mysql_close($link);
	}
	if($_POST['task'] == "thumbsExist"){
		$thumb1 = $_POST['thumb1'];
		$thumb2 = $_POST['thumb2'];
		$thumb3 = $_POST['thumb3'];
		$thumbUrl = $_POST['thumbUrl'];
		
		if(fopen($thumbUrl.$thumb1, "r")){
			echo "ok";
		}else{
			echo $thumbUrl.$thumb1;
		}
		if(fopen($thumbUrl.$thumb2, "r")){
			echo "ok";
		}else{
			echo $thumbUrl.$thumb2;
		}
		if(fopen($thumbUrl.$thumb3, "r")){
			echo "ok";
		}else{
			echo $thumbUrl.$thumb3;
		}
	}
	
	if($_POST['task'] == "addMissing"){
		$connect = mysql_connect("localhost", "root", "Securel0gi!12aa");
		mysql_select_db("planitlo_locations", $connect);
		$sql = "ALTER TABLE `prop_info` ADD `creation_date` DATE NULL DEFAULT NULL ";
		if(!mysql_query($sql)){
			echo mysql_error()."<br />";
		}else{
			echo "ok";
		}

		$sql = "ALTER TABLE `sorting` ADD `creation_date` DATE NULL DEFAULT NULL ";
		if(!mysql_query($sql)){
			echo mysql_error()."<br />";
		}else{
			echo "ok";
		}
		
		$sql = "ALTER TABLE `sorting` ADD `modified_date` DATE NULL DEFAULT NULL ";
		if(!mysql_query($sql)){
			echo mysql_error()."<br />";
		}else{
			echo "ok";
		}
		
		$sql = "ALTER TABLE `prop_info` ADD `modified_date` DATE NULL DEFAULT NULL ";
		if(!mysql_query($sql)){
			echo mysql_error()."<br />";
		}else{
			echo "ok";
		}
	}
	if($_POST['task'] == "saveIntoCrm"){
		$connect = mysql_connect("localhost", "root", "Securel0gi!12aa");
		mysql_select_db("planitlo_crm", $connect);
		if($_POST['propLoc'] != ''){
			$sql = "INSERT INTO property (property_id) VALUES ('".$_POST['propLoc']."')";
			if($res = mysql_query($sql)){
				echo mysql_insert_id();
			}else{
				echo "error";
			}
		}
	}
	if($_POST['task'] == "checkIfExist"){
		$connect = mysql_connect($dbHost, $dbUser, $dbPass);
		mysql_select_db("planitlo_locations", $connect);
		if($_POST['propLoc'] != ''){
			$sql = "SELECT prop_loc FROM prop_info WHERE prop_loc = '".mysql_real_escape_string($_POST['propLoc'])."'";
			if($res = mysql_query($sql)){
				$count = mysql_num_rows($res);
				if($count > 0){
					echo "1";
				}else{
					echo "0";
				}
			}else{
				echo mysql_error();
			}
		}
	}
	
	if($_POST['task'] == "getCity"){
		$connect = mysql_connect($dbHost, $dbUser, $dbPass);
		mysql_select_db("planitlo_crm", $connect);
		if(isset($_POST['zip'])){
			$sql = "SELECT city FROM zip_codes2010 WHERE zip_code = '".mysql_real_escape_string($_POST['zip'])."'";
			if($res = mysql_query($sql)){
				while($row = mysql_fetch_array($res)){
					echo ucwords(strtolower($row['city']))."|";
				}
			}else{
				echo mysql_error();
			}
		}
		
		mysql_close($connect);
	}
	
	if($_POST['task'] == "getState"){
		$connect = mysql_connect($dbHost, $dbUser, $dbPass);
		mysql_select_db("planitlo_crm", $connect);
		$stateName = "";
		if(isset($_POST['zip'])){
			$sql = "SELECT state_prefix FROM zip_codes2010 WHERE zip_code = '".mysql_real_escape_string($_POST['zip'])."'";
			if($res = mysql_query($sql)){
				$row = mysql_fetch_array($res);
				$statePrefix = $row['state_prefix'];
				$sql = "SELECT full_state FROM zip_codes WHERE state = '".$statePrefix."'";
				if($res = mysql_query($sql)){
					$row = mysql_fetch_array($res);
					echo $stateName = $row['full_state'];
					/*$sqlStateID = "SELECT id FROM state WHERE name = '".$stateName."'";
					$resStateID = mysql_query($sqlStateID);
					$rowStateID = mysql_fetch_array($resStateID);
					echo $rowStateID['id']; */
				}else{
					echo mysql_error();
				}
			}else{
				echo mysql_error();
			}
			
		}
		mysql_close($connect);
	}
	
	/*
	if($_POST['task'] == "addNewSortCategory"){
		$connect = mysql_connect($dbHost, $dbUser, $dbPass);
		mysql_select_db("planitlo_locations", $connect);
		if(isset($_POST['category']) && (isset($_POST['propName']))){
			$category = $_POST['category'];
			$propName = $_POST['propName'];
			$sqlGetSort = "SELECT * FROM sortings WHERE cat_name = '".$category."'";
			if($resGetSort = mysql_query($sqlGetSort)){
				if(mysql_num_rows($resGetSort) > 0){
					$rowGetSort = mysql_fetch_array($resGetSort);
					$s = explode(",", $rowGetSort['sorting']);
					if(count($s) > 1){
						$b = checkIfExist($s, $propName);
						if($b == 0){
							$pos = checkThird($s, $propName);
							//echo $pos;
							//die; exit;
							//array_push($s, $propName);
							if($category == "NEW"){
								array_splice($s, 0, 0, $propName);
							}else{
								array_splice($s, $pos, 0, $propName);
							}
							$s1 = implode(",", $s);
							$sqlGetPropLoc = "SELECT prop_loc FROM prop_info WHERE prop_name = '".$propName."'";
							//echo $sqlGetPropLoc; die;
							if($resGetPropLoc = mysql_query($sqlGetPropLoc)){
								if(mysql_num_rows($resGetPropLoc) > 0){
									$rowGetPropLoc = mysql_fetch_array($resGetPropLoc);
									$propLoc = $rowGetPropLoc['prop_loc'];
									$sqlInsertSort = "INSERT INTO sorting (prop_loc, category1, Priority, creation_date, modified_date) VALUES ('".$propLoc."', '".$category."', '0', '".date('Y-m-d')."', '".date('Y-m-d')."')";
									if(mysql_query($sqlInsertSort)){
										$sqlUpdateSort = "UPDATE sortings SET sorting = '".$s1."' WHERE cat_name = '".$category."'";
										if(mysql_query($sqlUpdateSort)){
											echo "Saved in category ".trim($category, " ");
											if($category == "NEW"){
												echo ", on page 1, position 1.";
											}else{
												$page = ceil($pos / 6);
												$position = ($pos % 6) + 1;
												echo ", on page ".$page.", position ".$position.".";
											}
											$sqlCheckLock = "SELECT id FROM locked WHERE category = '".$category."'";
											if($resCheckLock = mysql_query($sqlCheckLock)){
												if(mysql_num_rows($resCheckLock) > 0){
													echo "<br /><p style='color: red'>This category has <b>locked properties</b> in it. Please open the category link below and check the order.</p>";
												}
											}else{
												echo mysql_error();
												die;
											}
										}else{
											echo mysql_error();
											die;
										}
									}else{
										echo mysql_error();
										die;
									}
								}else{
									echo "Please save record in 'Website data' tab first.";
								}
							}else{
								echo mysql_error();
								die;
							}
						}else{
							echo "Property already exist in this category";
						}
					}else{
						echo "Not sorted yet.";
					}
				}else{
					echo "Wrong category.";
				}	
			}else{
				echo mysql_error();
				die;
			}
		}
		mysql_close($connect);
	}
	*/
	if($_POST['task'] == "addNewSortCategory"){
		$connect = mysql_connect($dbHost, $dbUser, $dbPass);
		mysql_select_db("planitlo_locations", $connect);
		if(isset($_POST['category']) && (isset($_POST['propName']))){
			$category = $_POST['category'];
			$propName = $_POST['propName'];
			$sqlGetSort = "SELECT * FROM sortings WHERE cat_name = '".$category."'";
			if($resGetSort = mysql_query($sqlGetSort)){
				if(mysql_num_rows($resGetSort) < 1){
					$sql = "INSERT INTO sortings (cat_name, sorting) VALUES ('".$category."', '')";
					if(mysql_query($sql)){
						$sqlGetSort = "SELECT * FROM sortings WHERE cat_name = '".$category."'";
						$resGetSort = mysql_query($sqlGetSort);
					}else{
						echo mysql_error();
						die;
					}
				}	
				$rowGetSort = mysql_fetch_array($resGetSort);
				$s = explode(",", $rowGetSort['sorting']);
				$b = checkIfExist($s, $propName);
				if($b == 0){
					$pos = checkThird($s, $propName);
					//echo $pos;
					//die; exit;
					//array_push($s, $propName);
					if($category == "NEW"){
						array_splice($s, 0, 0, $propName);
					}else{
						array_splice($s, $pos, 0, $propName);
					}
					$s1 = implode(",", $s);
					$sqlGetPropLoc = "SELECT prop_loc FROM prop_info WHERE prop_name = '".$propName."'";
					//echo $sqlGetPropLoc; die;
					if($resGetPropLoc = mysql_query($sqlGetPropLoc)){
						if(mysql_num_rows($resGetPropLoc) > 0){
							$rowGetPropLoc = mysql_fetch_array($resGetPropLoc);
							$propLoc = $rowGetPropLoc['prop_loc'];
							$sqlInsertSort = "INSERT INTO sorting (prop_loc, category1, Priority, creation_date, modified_date) VALUES ('".$propLoc."', '".$category."', '0', '".date('Y-m-d')."', '".date('Y-m-d')."')";
							if(mysql_query($sqlInsertSort)){
								$sqlUpdateSort = "UPDATE sortings SET sorting = '".$s1."' WHERE cat_name = '".$category."'";
								if(mysql_query($sqlUpdateSort)){
									echo "Saved in category ".trim($category, " ");
									if($category == "NEW"){
										echo ", on page 1, position 1.";
									}else{
										$page = ceil($pos / 6);
										$position = ($pos % 6) + 1;
										if($page == 0) $page = 1;
										echo ", on page ".$page.", position ".$position.".";
									}
									$sqlCheckLock = "SELECT id FROM locked WHERE category = '".$category."'";
									if($resCheckLock = mysql_query($sqlCheckLock)){
										if(mysql_num_rows($resCheckLock) > 0){
											echo "<br /><p style='color: red'>This category has <b>locked properties</b> in it. Please open the category link below and check the order.</p>";
										}
									}else{
										echo mysql_error();
										die;
									}
								}else{
									echo mysql_error();
									die;
								}
							}else{
								echo mysql_error();
								die;
							}
						}else{
							echo "Please save record in 'Website data' tab first.";
						}
					}else{
						echo mysql_error();
						die;
					}
				}else{
					echo "Property already exist in this category";
				}
			}else{
				echo mysql_error();
				die;
			}
		}
		mysql_close($connect);
	}
	
	if($_POST['task'] == "reloadCategories"){
		$connect = mysql_connect($dbHost, $dbUser, $dbPass);
		mysql_select_db("planitlo_locations", $connect);
		if(isset($_POST['propName'])){
			$propName = mysql_real_escape_string($_POST['propName']);
			$sql = "SELECT * FROM sorting WHERE prop_loc = '".$propName."'";
			if($res = mysql_query($sql)){
				?>
				<table cellspacing="3" cellpadding="3" border="0" class="listview" style="width: 350px" id="tblNSCategories">
					<tr bgcolor="#d0e1f4">
						<td colspan="2">
							<b>Categories</b> - click on the category to sort it
						</td>
					</tr>
					<?php	                                       					                                       			 
					while($rowGetSort = mysql_fetch_array($res)){
						?>
						<tr>																
							<td>
								<a href="https://planitlocations.com/search/search-and-sort.php?p=1&cat=<?php	                                       					                                       			 echo $rowGetSort['category1']; ?>" target="_blank">
									<?php	                                       					                                       			 echo $rowGetSort['category1']; ?>
								</a>
							</td>
							<td>
								<input type="hidden" value="<?php	                                       					                                       			 echo $rowGetSort['category1']; ?>" />
								<span class="delNSCategory" style="cursor: pointer;">
									X
								</span>
							</td>
						</tr>
						<?php	                                       					                                       			 
					}
					?>
				</table>
				<?php	                                       					                                       			 
			}
		}
		mysql_close($connect);
	}
	
	if($_POST['task'] == "deleteNewSorting"){
		$connect = mysql_connect($dbHost, $dbUser, $dbPass);
		mysql_select_db("planitlo_locations", $connect);
		if((isset($_POST['category'])) && (isset($_POST['propName']))){
			$category = mysql_real_escape_string($_POST['category']);
			$propName = mysql_real_escape_string($_POST['propName']);
			$sqlGetSort = "SELECT * FROM sortings WHERE cat_name = '".$category."'";
			if($resGetSort = mysql_query($sqlGetSort)){
				if(mysql_num_rows($resGetSort) > 0){
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
										if(mysql_query($sqlOld)){
											echo "Property was removed from category ".$category.".";
											$sqlCheckLock = "SELECT id FROM locked WHERE category = '".$category."'";
											if($resCheckLock = mysql_query($sqlCheckLock)){
												if(mysql_num_rows($resCheckLock) > 0){
													echo "<br /><p style='color: red'>This category has <b>locked properties</b> in it. Please open this <a href='../search/search-and-sort.php?p=1&cat=".trim($category, " ")."' target='_blank'>link</a> to check the order after deleting.</p>";
												}
											}else{
												echo mysql_error();
												die;
											}
											echo "|".$count;
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
		}
		mysql_close($connect);
	}
	
	if($_POST['task'] == "checkIfPropExist"){
		$connect = mysql_connect($dbHost, $dbUser, $dbPass);
		mysql_select_db("planitlo_crm", $connect);
		$propLoc = $_POST['propLoc'];
		$sql = "SELECT id FROM property WHERE property_id = '".$propLoc."'";
		//echo $sql;
		if($res = mysql_query($sql)){
		//	echo count(mysql_num_rows($res));
			$row = mysql_fetch_array($res);
			$id = $row['id'];
			if($id != ""){
				echo "exist";
			}else{
				echo "free";
			}
		}else{
			echo mysql_error();
			die;
		}
		mysql_close($connect);
	}
	
	if($_POST['task'] == "addLastToEmpty"){
		$connect = mysql_connect($dbHost, $dbUser, $dbPass);
		mysql_select_db("planitlo_locations", $connect);
		//$propLoc = mysql_real_escape_string($_POST['propLoc']);
		$category = mysql_real_escape_string($_POST['category']);
		$pos = mysql_real_escape_string($_POST['pos']);
		
		//$propName = $propLoc." <~Property ID";
		
		$sqlGetSort = "SELECT * FROM sortings WHERE cat_name = '".$category."'";
		if($resGetSort = mysql_query($sqlGetSort)){
			if(mysql_num_rows($resGetSort) > 0){
				$rowGetSort = mysql_fetch_array($resGetSort);
				$s = explode(",", $rowGetSort['sorting']);
				
				$propName = end($s);
				array_splice($s, $pos, 0, $propName);
				array_pop($s);
				
				$s1 = implode(",", $s);
				$sqlUpdateSort = "UPDATE sortings SET sorting = '".$s1."' WHERE cat_name = '".$category."'";
				if(mysql_query($sqlUpdateSort)){
					$propLoc = getPropLocByName($propName);
					if($propLoc != ""){
						$sql = "UPDATE sorting SET modified_date = '".date('Y-m-d')."' WHERE prop_loc = '".$propLoc."' AND category1 = '".$category."'";
						if(mysql_query($sql)){
							echo "Successfully saved.";
						}else{
							echo mysql_error();
							die;
						}
					}else{
						echo "Error.";
					}
				}else{
					echo mysql_error();
					die;
				}
			}
		}
		mysql_close($connect);
	}
	
	if($_POST['task'] == "selectToEmpty"){
		$connect = mysql_connect($dbHost, $dbUser, $dbPass);
		mysql_select_db("planitlo_locations", $connect);
		$category = mysql_real_escape_string($_POST['category']);
		$sqlGetSort = "SELECT * FROM sortings WHERE cat_name = '".$category."'";
		if($resGetSort = mysql_query($sqlGetSort)){
			if(mysql_num_rows($resGetSort) > 0){
				$rowGetSort = mysql_fetch_array($resGetSort);
				$s = explode(",", $rowGetSort['sorting']);
				?><ul><?php	                                       			 
				foreach($s as $propName){
					?>
					<li style="margin-bottom: 5px">
						<span class="propImg" style="cursor: pointer; color: blue">
							<?php echo $propName ?>
						</span>
					</li>
					<?php	                                       			 
				}
				?>
				</ul>
				<?php	                                       			 
			}
		}else{
			echo mysql_error();
			die;
		}
		mysql_close($connect);
	}
	
	if($_POST['task'] == "getImagesByPropName"){
		$connect = mysql_connect($dbHost, $dbUser, $dbPass);
		mysql_select_db("planitlo_locations", $connect);
		
		$propName = mysql_real_escape_string($_POST['propName']);
		$propName = str_replace("&lt;", "<", $propName);
		
		$sql = "SELECT tn_domain_loc, thumb01, thumb02, thumb03, prop_loc FROM prop_info WHERE prop_name = '".$propName."'";
		if($res = mysql_query($sql)){
			if(mysql_num_rows($res) > 0){
				$row = mysql_fetch_array($res);
				?>
				<img width="135" src="<?php	echo $row['tn_domain_loc'].$row['thumb01'] ?>" alt="" />
				<img width="135" src="<?php	echo $row['tn_domain_loc'].$row['thumb02'] ?>" alt="" />
				<img width="135" src="<?php	echo $row['tn_domain_loc'].$row['thumb03'] ?>" alt="" />
				<?php	                                       			 
			}
		}else{
			echo mysql_error();
			die;
		}
		mysql_close($connect);
	}
	
	if($_POST['task'] == "getLastPropImages"){
		$connect = mysql_connect($dbHost, $dbUser, $dbPass);
		mysql_select_db("planitlo_locations", $connect);
		
		$category = mysql_real_escape_string($_POST['category']);
		
		$sqlGetSort = "SELECT * FROM sortings WHERE cat_name = '".$category."'";
		if($resGetSort = mysql_query($sqlGetSort)){
			if(mysql_num_rows($resGetSort) > 0){
				$rowGetSort = mysql_fetch_array($resGetSort);
				$s = explode(",", $rowGetSort['sorting']);
				$propName = end($s);
				$sql = "SELECT tn_domain_loc, thumb01, thumb02, thumb03, prop_loc FROM prop_info WHERE prop_name = '".$propName."'";
				if($res = mysql_query($sql)){
					if(mysql_num_rows($res) > 0){
						$row = mysql_fetch_array($res);
						?>
						<img width="135" src="<?php	echo $row['tn_domain_loc'].$row['thumb01'] ?>" alt="" />
						<img width="135" src="<?php	echo $row['tn_domain_loc'].$row['thumb02'] ?>" alt="" />
						<img width="135" src="<?php	echo $row['tn_domain_loc'].$row['thumb03'] ?>" alt="" />
						<?php	                                       			 
					}
				}else{
					echo mysql_error();
					die;
				}
			}else{
				echo "Not sorted yet.";
			}
		}else{
			echo mysql_error();
			die;
		}
		mysql_close($connect);
	}
	
	if($_POST['task'] == "addClickedToEmpty"){
		$connect = mysql_connect($dbHost, $dbUser, $dbPass);
		mysql_select_db("planitlo_locations", $connect);
		$category = mysql_real_escape_string($_POST['category']);
		$propName = mysql_real_escape_string($_POST['propName']);
		$propName = str_replace("&lt;", "<", $propName);
		$pos = mysql_real_escape_string($_POST['pos']);
		$sqlGetSort = "SELECT * FROM sortings WHERE cat_name = '".$category."'";
		if($resGetSort = mysql_query($sqlGetSort)){
			if(mysql_num_rows($resGetSort) > 0){
				$rowGetSort = mysql_fetch_array($resGetSort);
				$s = explode(",", $rowGetSort['sorting']);
				
				//delete clicked from its previous position
				$count = findLocation($s, $propName);
				echo "count: ".$count;
				array_splice($s, $count, 1);
				if($pos > $count){
					$pos = (int)$pos - 1;
				}
				if($pos < 0) $pos = 0;
				//add the clicked to the empty position
				array_splice($s, $pos, 0, $propName);
				echo " pos: ".$pos." ";
				//print_r($s);
				$s1 = implode(",", $s);
				$sqlUpdateSort = "UPDATE sortings SET sorting = '".$s1."' WHERE cat_name = '".$category."'";
				if(mysql_query($sqlUpdateSort)){
					$propLoc = getPropLocByName($propName);
					if($propLoc != ""){
						$sql = "UPDATE sorting SET modified_date = '".date('Y-m-d')."' WHERE prop_loc = '".$propLoc."' AND category1 = '".$category."'";
						if(mysql_query($sql)){
							echo "Successfully saved.";
						}else{
							echo mysql_error();
							die;
						}
					}else{
						echo "Error.";
					}
				}else{
					echo mysql_error();
					die;
				}
			}else{
				echo "Not sorted yet.";
			}
		}
		mysql_close($connect);
	}
}
?>