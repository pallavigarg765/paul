<?php	                                       					                                       			 
include('include/dbcon.php');
$db = new DBConn();

$dbUser = "root";
$dbPass = "Securel0gi!12aa";
$dbHost = "localhost";

session_start();

include("../logger/Logger.php");
$log = new Logger();
$log->setType("html");
$log->setFile("../logger/crm/".$_SESSION['username'].".html");
$log->setUser($_SESSION['username']);

function in_arrayi($needle, $haystack) {
	return in_array(strtolower($needle), array_map('strtolower', $haystack));
}

function remove_item_by_value($array, $val = '', $preserve_keys = true) {
	if (empty($array) || !is_array($array)) return false;
	
	if (!in_arrayi($val, $array)) return $array;

	foreach($array as $key => $value) {
		if (strtolower(trim($value)) == strtolower(trim($val))) {
			unset($array[$key]);
		}
	}
	return ($preserve_keys === true) ? $array : array_values($array);
}

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
	if($_POST['task'] == "populateFromParent"){
		mysql_select_db("planitlo_crm");
		if(isset($_POST['parent']) && ($_POST['parent'] != '')){
			$sql = "SELECT * FROM property WHERE property_id = '".mysql_real_escape_string($_POST['parent'])."'";
			if($res = mysql_query($sql)){
				$rows = array();
				while($row = mysql_fetch_assoc($res)){
					$rows[] = $row;
				}
				print json_encode($rows);
			}else{
				die(mysql_error());
			}
		}
	}
	
	if($_POST['task'] == "saveNewSorting"){
		mysql_select_db("planitlo_locations");
		if(isset($_POST['id']) && ($_POST['id'] != '') && isset($_POST['value'])){
			$sql = "UPDATE sorting SET Priority = '".$_POST['value']."', modified_date = '".date('Y-m-d')."' WHERE ID = '".$_POST['id']."'";
			if(mysql_query($sql)){
				echo $_POST['value'];
				$log->setText($_SERVER['REMOTE_ADDR']." Saved new sorting ".$_POST['id']." ".$_POST['value']);
				$log->saveLog();
			}else{
				echo mysql_error();
			}
		}else{
			echo "error";
		}
	}
	
	if($_POST['task'] == "getCoutryById"){
		if(isset($_POST['id']) && ($_POST['id'] != '')){
			mysql_select_db("planitlo_crm");
			$sql = "SELECT printable_name  FROM country WHERE id = '".$_POST['id']."'";
			if($res = mysql_query($sql)){
				if(mysql_num_rows($res) > 0){
					$row = mysql_fetch_array($res);
					echo $row['printable_name'];
				}
			}else{
				echo mysql_error();
			}
		}
	}
	
	if($_POST['task'] == "getStateById"){
		if(isset($_POST['id']) && ($_POST['id'] != '')){
			mysql_select_db("planitlo_crm");
			$sql = "SELECT name FROM state WHERE id = '".$_POST['id']."'";
			if($res = mysql_query($sql)){
				if(mysql_num_rows($res) > 0){
					$row = mysql_fetch_array($res);
					echo $row['name'];
				}
			}else{
				echo mysql_error();
			}
		}
	}
	
	if($_POST['task'] == "addSorting"){
		mysql_select_db("planitlo_locations");
		if(isset($_POST['category']) && isset($_POST['sorting']) && isset($_POST['propLoc'])){
			$sql = "INSERT INTO sorting (prop_loc, category1, Priority, creation_date, modified_date) VALUES ('".$_POST['propLoc']."', '".$_POST['category']."', '".$_POST['sorting']."', '".date('Y-m-d')."', '".date('Y-m-d')."');";
			if(mysql_query($sql)){
				echo "Saved.";
				$log->setText($_SERVER['REMOTE_ADDR']." addSorting ".$_POST['category']." ".$_POST['propLoc']);
				$log->saveLog();
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
				$log->setText($_SERVER['REMOTE_ADDR']." deleteSorting ".$_POST['id']);
				$log->saveLog();
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
					$log->setText($_SERVER['REMOTE_ADDR']." deleteToLoc ".$_POST['propLoc']);
					$log->saveLog();
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
				$log->setText($_SERVER['REMOTE_ADDR']." saveIntoCrm ".$_POST['propLoc']);
				$log->saveLog();
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
	
	if($_POST['task'] == "addNewSortCategory"){
		$connect = mysql_connect($dbHost, $dbUser, $dbPass);
		mysql_select_db("planitlo_locations", $connect);
		$logAddNew = new Logger();
		$logAddNew->setType("html");
		$logAddNew->setFile("../logger/crm/addNewSorting.html");
		$logAddNew->setUser($_SESSION['username']);
		if(isset($_POST['category']) && (isset($_POST['propName']))){
			$category = $_POST['category'];
			$propName = $_POST['propName'];
			$strLogAddNew = "In add new ".$category." ".$propName.". ";
			$strLogAddNew .= "category and propName != ''. ";
			$sqlGetSort = "SELECT * FROM sortings WHERE cat_name = '".$category."'";
			if($resGetSort = mysql_query($sqlGetSort)){
				$strLogAddNew .= "sqlGetSort successful . ";
				if(mysql_num_rows($resGetSort) < 1){
					$strLogAddNew .= "sqlGetSort has less than 1 result (it's a new category). ";
					$sql = "INSERT INTO sortings (cat_name, sorting) VALUES ('".$category."', '')";
					if(mysql_query($sql)){
						$strLogAddNew .= "sql successful (insert in new sorting). ";
						$sqlGetSort = "SELECT * FROM sortings WHERE cat_name = '".$category."'";
						if($resGetSort = mysql_query($sqlGetSort)){
							$strLogAddNew .= "sqlGetSort successful. ";
						}else{
							$strLogAddNew .= "<span style='color: red'>sqlGetSort unsuccessful.</span> ";
						}
					}else{
						$strLogAddNew .= "<span style='color: red'>sql (insert) unsuccessful.</span> ";
						die(mysql_error());
					}
				}else{
					$strLogAddNew .= "sqlGetSort has more than 1 result. category already exist in sortings table. ";
				}
				$rowGetSort = mysql_fetch_array($resGetSort);
				$s = explode(",", $rowGetSort['sorting']);
				$strLogAddNew .= " Before add, new sorting had: ".count($s)." ";
				$b = checkIfExist($s, $propName);
				if($b == 0){
					$strLogAddNew .= "property does not exist in category yet. ";
					$pos = checkThird($s, $propName);
					if($category == "NEW"){
						array_splice($s, 0, 0, $propName);
					}else{
						array_splice($s, $pos, 0, $propName);
					}
					$strLogAddNew .= " After add, new sorting has: ".count($s)." ";
					$s1 = implode(",", $s);
					$sqlGetPropLoc = "SELECT prop_loc FROM prop_info WHERE prop_name = '".$propName."'";
					//echo $sqlGetPropLoc; die;
					if($resGetPropLoc = mysql_query($sqlGetPropLoc)){
						$strLogAddNew .= "sqlGetPropLoc successful. ";
						if(mysql_num_rows($resGetPropLoc) > 0){
							$strLogAddNew .= "sqlGetPropLoc has more then 0 results. ";
							$rowGetPropLoc = mysql_fetch_array($resGetPropLoc);
							$propLoc = $rowGetPropLoc['prop_loc'];
							$strLogAddNew .= "propLoc found: ".$propLoc.". ";
							
							//check if exist in old sorting
							$sqlCheckIfOld = "SELECT * FROM sorting WHERE prop_loc = '".$propLoc."' AND category1 = '".$category."'";
							if($resCheckIfOld = mysql_query($sqlCheckIfOld)){
								if(mysql_num_rows($resCheckIfOld) == 0){
									$sqlInsertSort = "INSERT INTO sorting (prop_loc, category1, Priority, creation_date, modified_date) VALUES ('".$propLoc."', '".$category."', '0', '".date('Y-m-d')."', '".date('Y-m-d')."')";
									if(mysql_query($sqlInsertSort)){
										$strLogAddNew .= "sqlInsertSort (sorting) successful. ";
									}else{
										$strLogAddNew .= "<span style='color: red'>sqlInsertSort (sorting) unsuccessful. ".$sqlInsertSort."</span> ";
										die(mysql_error());
									}
								}else{
									$strLogAddNew .= "already exist in old sorting. ";
								}
							}else{
								$strLogAddNew .= "<span style='color: red'>sqlCheckIfOld (sorting) unsuccessful. ".$sqlCheckIfOld."</span> ";
							}
							
							//update the new sorting 
							$sqlUpdateSort = "UPDATE sortings SET sorting = '".$s1."' WHERE cat_name = '".$category."'";
							if(mysql_query($sqlUpdateSort)){
								$strLogAddNew .= "sqlUpdateSort (sortings) successful. ";
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
								$strLogAddNew .= "<span style='color: red'>sqlUpdateSort (sortings) unsuccessful. ".$sqlUpdateSort."</span> ";
								die(mysql_error());
							}
							
						}else{
							$strLogAddNew .= "<span style='color: red'>sqlGetPropLoc has less then 0 results (no record in prop_info). ".$sqlGetPropLoc."</span> ";
							echo "Please save record in 'Website data' tab first.";
						}
					}else{
						$strLogAddNew .= "<span style='color: red'>sqlGetPropLoc unsuccessful ".$sqlGetPropLoc."</span> ";
						die(mysql_error());
					}
				}else{
					$strLogAddNew .= "<span style='color: red'>property already exist in new sort.</span> ";
					$propLoc = "";
					$sqlGetPropLoc = "SELECT prop_loc FROM prop_info WHERE prop_name = '".$propName."'";
					if($resGetPropLoc = mysql_query($sqlGetPropLoc)){
						if(mysql_num_rows($resGetPropLoc) > 0){
							$rowGetPropLoc = mysql_fetch_array($resGetPropLoc);
							$propLoc = $rowGetPropLoc['prop_loc'];
						}
					}
					if($propLoc != ""){
						$strLogAddNew .= "found propLoc. ";
						$sqlCheckIfOld = "SELECT * FROM sorting WHERE prop_loc = '".$propLoc."' AND category1 = '".$category."'";
						if($resCheckIfOld = mysql_query($sqlCheckIfOld)){
							if(mysql_num_rows($resCheckIfOld) == 0){
								$strLogAddNew .= "does not exist in old sort. ";
								//insert in old sort
								$sqlInsertInOld = "INSERT INTO sorting (prop_loc, category1, Priority, creation_date, modified_date)
									VALUES ('".$propLoc."', '".$category."', '0', '".date('Y-m-d')."', '".date('Y-m-d')."')";
								if(mysql_query($sqlInsertInOld)){
									$strLogAddNew .= "inserted in old sort only ";
									echo "Saved.";
								}else{
									$strLogAddNew .= "<span style='color: red'>query unsucessful. ".$sqlInsertInOld."</span> ";
									die(mysql_error());
								}
							}else{
								$strLogAddNew .= "<span style='color: red'>already exist in both tables. </span> ";
								echo "Property already exist in this category";
							}
						}else{
							$strLogAddNew .= "<span style='color: red'>query unsuccessful ".$sqlCheckIfOld.". </span> ";
							die(mysql_error());
						}
					}else{
						$strLogAddNew .= "<span style='color: red'>can't find valid prop_loc in prop_info. </span> ";
					}
				}
			}else{
				die(mysql_error());
			}
			$logAddNew->setText($strLogAddNew);
			$logAddNew->saveLog();
			$log->setText($_SERVER['REMOTE_ADDR']." Added category ".$_POST['category']." to property ".$_POST['propName']);
			$log->saveLog();
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
				<table cellspacing="3" cellpadding="3" border="0" class="listview" style="" id="tblNSCategories">
					<tr bgcolor="#d0e1f4">
						<td colspan="2">
							<b>Categories</b> - click on the category to sort it
						</td>
						<td width="60">&nbsp;</td>
						<td colspan="2">
							Switch between a/b/c/d/prv
						</td>
					</tr>
					<?php	                                       					                                       			 
					while($rowGetSort = mysql_fetch_array($res)){
						?>
						<tr>																
							<td>
								<a href="https://planitlocations.com/search/search-and-sort.php?p=1&cat=<?php	                                       					                                       			 echo $rowGetSort['category1']; ?>" target="_blank">
									<?php echo $rowGetSort['category1']; ?>
								</a>
							</td>
							<td>
								<input type="hidden" class="hidDelCategory" value="<?php echo $rowGetSort['category1']; ?>" />
								<span class="delNSCategory" style="cursor: pointer;">
									X
								</span>
							</td>
							<td width="200">&nbsp;</td>
							<td>
								<?php 
								$mainCat = $rowGetSort['category1'];
								$mainCat = trim($mainCat, "-b");
								$mainCat = trim($mainCat, "-c");
								$mainCat = trim($mainCat, "-d");
								$mainCat = trim($mainCat, "Z");
								$mainCat = trim($mainCat, "prv");
								
								$bType = $mainCat."-b";
								$cType = $mainCat."-c";
								$dType = $mainCat."-d";
								$zType = $mainCat."Z";
								$prvType = $mainCat."prv";
								?>
								<select style="width:150px" class="cboPickCategory">
									<option><?php echo $mainCat ?></option>
									<option <?php if(trim($rowGetSort['category1']) == $bType) echo "selected='selected'"; ?>><?php echo $bType; ?></option>
									<option <?php if(trim($rowGetSort['category1']) == $cType) echo "selected='selected'"; ?>><?php echo $cType; ?></option>
									<option <?php if(trim($rowGetSort['category1']) == $dType) echo "selected='selected'"; ?>><?php echo $dType; ?></option>
									<option <?php if(trim($rowGetSort['category1']) == $zType) echo "selected='selected'"; ?>><?php echo $zType; ?></option>
									<option <?php if(trim($rowGetSort['category1']) == $prvType) echo "selected='selected'"; ?>><?php echo $prvType; ?></option>
								</select>
							</td>
							<td>
								<input type="hidden" value="<?php echo trim($rowGetSort['category1']); ?>" />
								<input type="button" class="switch" value="Switch" />
							</td>
						</tr>
						<?php	                                       					                                       			 
					}
					?>
					<tr>
						<td colspan="2"></td>
						<td style="text-align: right; width: 200px;">
							Switch all to
						</td>
						<td>
							<select id="cboSwitchAll" style="width:150px">
								<option>-a</option>
								<option>-b</option>
								<option>-c</option>
								<option>-d</option>
								<option>Z</option>
								<option>prv</option>
							</select>
						</td>
						<td>
							<input type="button" class="btnSwitchAll" value="Switch All" />
						</td>
					</tr>
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
			$logDelete = new Logger();
			$logDelete->setType("html");
			$logDelete->setFile("../logger/crm/deleteNewSorting.html");
			$logDelete->setUser($_SESSION['username']);
			$category = mysql_real_escape_string($_POST['category']);
			$propName = mysql_real_escape_string($_POST['propName']);
			$strLogDelete = "In delete ".$category." ".$propName.". ";
			$sqlGetSort = "SELECT * FROM sortings WHERE cat_name = '".$category."'";
			if($resGetSort = mysql_query($sqlGetSort)){
				$strLogDelete .= "sqlGetSort successful. ";
				if(mysql_num_rows($resGetSort) > 0){
					$strLogDelete .= "sqlGetSort has more then 0 results. ";
					$rowGetSort = mysql_fetch_assoc($resGetSort);
					$s = explode(",", $rowGetSort['sorting']);
					//$count = findLocation($s, $propName);
					//array_splice($s, $count, 1);
					$strLogDelete .= "Before delete, new sorting had ".count($s)." records ";
					$s1 = remove_item_by_value($s, $propName);
					$strLogDelete .= "After delete, new sorting has ".count($s1)." records ";
					$s1 = implode(",", $s1);
					$sqlUpdateSort = "UPDATE sortings SET sorting = '".$s1."' WHERE cat_name = '".$category."'";
					if(mysql_query($sqlUpdateSort)){
						$strLogDelete .= "sqlUpdateSort successful. ";
						if(($category != "") && ($propName != "")){
							$strLogDelete .= "category and propName != ''. ";
							$sqlGetLoc = "SELECT prop_loc FROM prop_info WHERE prop_name = '".$propName."'";
							if($resGetLoc = mysql_query($sqlGetLoc)){
								$strLogDelete .= "sqlGetLoc successful. ";
								if(mysql_num_rows($resGetLoc) > 0 ){
									$strLogDelete .= "sqlGetLoc has more then 0 results. ";
									$rowLoc = mysql_fetch_array($resGetLoc);
									$propLoc = $rowLoc['prop_loc'];
									if($propLoc != ""){
										$strLogDelete .= "propLoc not empty. ";
										$sqlOld = "DELETE FROM sorting WHERE prop_loc = '".$propLoc."' AND category1 = '".$category."'";
										if(mysql_query($sqlOld)){
											$strLogDelete .= "sqlOld (delete) successful . ";
											echo "Property was removed from category ".$category.".";
											$sqlCheckLock = "SELECT id FROM locked WHERE category = '".$category."'";
											if($resCheckLock = mysql_query($sqlCheckLock)){
												$strLogDelete .= "sqlCheckLock successful . ";
												if(mysql_num_rows($resCheckLock) > 0){
													$strLogDelete .= "sqlCheckLock has more then 0 results . ";
													echo "<br /><p style='color: red'>This category has <b>locked properties</b> in it. Please open this <a href='../search/search-and-sort.php?p=1&cat=".trim($category, " ")."' target='_blank'>link</a> to check the order after deleting.</p>";
												}
											}else{
												$strLogDelete .= "<span style='color: red'>sqlCheckLock unsuccessful. ".$sqlCheckLock."</span> ";
												echo mysql_error();
												die;
											}
											echo "|0"; //.$count;
										}else{
											$strLogDelete .= "<span style='color: red'>sqlOld (delete) unsuccessful. ".$sqlOld."</span> ";
											die(mysql_error());
										}
									}else{
										$strLogDelete .= "<span style='color: red'>propLoc empty.</span> ";
										die(mysql_error());
									}
								}else{
									$strLogDelete .= "<span style='color: red'>sqlGetLoc has 0 results.</span> ";
									die(mysql_error());
								}
							}else{
								$strLogDelete .= "<span style='color: red'>sqlGetLoc unsuccessful. ".$sqlGetLoc."</span> ";
								die(mysql_error());
							}
						}else{
							$strLogDelete .= "<span style='color: red'>category or propName empty.</span> ";
							die(mysql_error());
						}
					}else{
						$strLogDelete .= "<span style='color: red'>sqlUpdateSort unsuccessful. ".$sqlUpdateSort."</span> ";
						die(mysql_error());
					}
				}else{
					$strLogDelete .= "<span style='color: red'>sqlGetSort has 0 results.</span> ";
					die(mysql_error());
				}
			}else{
				$strLogDelete .= "<span style='color: red'>sqlGetSort unsuccessful. ".$sqlGetSort."</span> ";
				die(mysql_error());
			}
			$log->setText($_SERVER['REMOTE_ADDR']." deleteNewSorting Deleted category ".$_POST['category']." from property ".$_POST['propName']);
			$log->saveLog();
			$logDelete->setText($strLogDelete);
			$logDelete->saveLog();
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