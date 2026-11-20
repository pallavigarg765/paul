<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/functions.php');
	$db = new DBConn();
	
	/*$nameorder = 'ASC';
	$nameorder = 'arrow';
	$idorder = 'ASC';
	$idarrow = 'arrow';
	$propertynameorder = 'ASC';
	$propertynamearrow = 'arrow';
*/	
	$current_page = 'searchresults.php';
	$criteria = "";
	
	// Advance Search Criteria
	if(isset($_REQUEST['columns'])){
		$_SESSION['columns'] = $_REQUEST['columns'];
		$_SESSION['conditions'] = $_REQUEST['conditions'];
		$_SESSION['searchkeywords'] = $_REQUEST['searchkeywords'];
	}
	$columns = $_SESSION['columns'];

	$sql_list_query = "SELECT distinct property.id, property.property_id, property.zipcode, property.property_id, property.company,property.city,contact.id as cid,
						contact.firstname, contact.lastname, state.name as statename
					  FROM property 
					  left join contact_to_property on contact_to_property.property_id=property.id 
					  left join contact on  contact_to_property.contact_id=contact.id  
					  left join state on state.id = property.state ";
					  foreach($columns as $key => $value)
					  {
							if($value == 'tags' )
							{
								$sql_list_query .= " join properties_tags_relationships on properties_tags_relationships.property_id=property.id";
								$sql_list_query .= " join tags on properties_tags_relationships.tag_id=tags.tag_id";					
							}
					  }
	$sql_list_query .= " WHERE property.active=1 ";
	
	foreach($columns as $key => $value){
		if($value != ''){
			if($_SESSION['conditions'][$key] == '='){
				if($value == 'pid')
					$sql_list_query .= " and property.property_id = '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'pname')
					$sql_list_query .= " and property.name = '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'title')
					$sql_list_query .= " and contact.title = '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'name')
					$sql_list_query .= " and (contact.firstname = '".addslashes($_SESSION['searchkeywords'][$key])."'  OR contact.lastname ='".$_SESSION['searchkeywords'][$key]."') ";
				if($value == 'email')
					$sql_list_query .= " and contact.email = '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'company')
					$sql_list_query .= " and property.company = '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'notes')
					$sql_list_query .= " and contact.notes = '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'address')
					$sql_list_query .= " and property.address = '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'county')
					$sql_list_query .= " and county.name = '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'state')
					$sql_list_query .= " and state.name = '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'city')
					$sql_list_query .= " and property.city = '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'zipcode')
					$sql_list_query .= " and property.zipcode = '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'tags')
					$sql_list_query .= " and tags.tag_name = '".addslashes($_SESSION['searchkeywords'][$key])."' ";
			}
			if($_SESSION['conditions'][$key] == '!='){
				if($value == 'pid')
					$sql_list_query .= " and property.property_id != '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'pname')
					$sql_list_query .= " and property.name != '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'title')
					$sql_list_query .= " and contact.title != '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'name')
					$sql_list_query .= " and (contact.firstname != '".addslashes($_SESSION['searchkeywords'][$key])."'  OR contact.lastname !='".addslashes($_SESSION['searchkeywords'][$key])."') ";
				if($value == 'email')
					$sql_list_query .= " and contact.email != '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'company')
					$sql_list_query .= " and property.company != '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'notes')
					$sql_list_query .= " and contact.notes != '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'address')
					$sql_list_query .= " and property.address != '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'county')
					$sql_list_query .= " and county.name != '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'state')
					$sql_list_query .= " and state.name != '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'city')
					$sql_list_query .= " and property.city != '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'zipcode')
					$sql_list_query .= " and property.zipcode != '".addslashes($_SESSION['searchkeywords'][$key])."' ";
			}
			if($_SESSION['conditions'][$key] == 'like'){
				if($value == 'pid')
					$sql_list_query .= " and property.property_id LIKE '%".addslashes($_SESSION['searchkeywords'][$key])."%' ";
				if($value == 'pname')
					$sql_list_query .= " and property.name LIKE '%".addslashes($_SESSION['searchkeywords'][$key])."%' ";
				if($value == 'title')
					$sql_list_query .= " and contact.title LIKE '%".addslashes($_SESSION['searchkeywords'][$key])."%' ";
				if($value == 'name')
					$sql_list_query .= " and (contact.firstname LIKE '%".addslashes($_SESSION['searchkeywords'][$key])."%'  OR contact.lastname LIKE '%".addslashes($_SESSION['searchkeywords'][$key])."%') ";
				if($value == 'email')
					$sql_list_query .= " and contact.email LIKE '%".addslashes($_SESSION['searchkeywords'][$key])."%' ";
				if($value == 'company')
					$sql_list_query .= " and property.company LIKE '%".addslashes($_SESSION['searchkeywords'][$key])."%' ";
				if($value == 'notes')
					$sql_list_query .= " and contact.notes LIKE '%".addslashes($_SESSION['searchkeywords'][$key])."%' ";
				if($value == 'address')
					$sql_list_query .= " and property.address LIKE '%".addslashes($_SESSION['searchkeywords'][$key])."%' ";
				if($value == 'county')
					$sql_list_query .= " and county.name LIKE '%".addslashes($_SESSION['searchkeywords'][$key])."%' ";
				if($value == 'state')
					$sql_list_query .= " and state.name LIKE '%".addslashes($_SESSION['searchkeywords'][$key])."%' ";
				if($value == 'city')
					$sql_list_query .= " and property.city LIKE '%".addslashes($_SESSION['searchkeywords'][$key])."%' ";
				if($value == 'zipcode')
					$sql_list_query .= " and property.zipcode LIKE '%".addslashes($_SESSION['searchkeywords'][$key])."%' ";
				if($value == 'tags')
					$sql_list_query .=  " and tags.tag_name LIKE '%".addslashes($_SESSION['searchkeywords'][$key])."%' ";
			}
			if($_SESSION['conditions'][$key] == '<'){
				if($value == 'pid')
					$sql_list_query .= " and property.property_id < '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'pname')
					$sql_list_query .= " and property.name < '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'title')
					$sql_list_query .= " and contact.title < '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'name')
					$sql_list_query .= " and (contact.firstname < '".addslashes($_SESSION['searchkeywords'][$key])."'  OR contact.lastname < '".addslashes($_SESSION['searchkeywords'][$key])."') ";
				if($value == 'email')
					$sql_list_query .= " and contact.email < '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'company')
					$sql_list_query .= " and property.company < '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'notes')
					$sql_list_query .= " and contact.notes < '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'address')
					$sql_list_query .= " and property.address < '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'county')
					$sql_list_query .= " and county.name < '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'state')
					$sql_list_query .= " and state.name < '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'city')
					$sql_list_query .= " and property.city < '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'zipcode')
					$sql_list_query .= " and property.zipcode < '".addslashes($_SESSION['searchkeywords'][$key])."' ";
			}
			if($_SESSION['conditions'][$key] == '>'){
				if($value == 'pid')
					$sql_list_query .= " and property.property_id > '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'pname')
					$sql_list_query .= " and property.name > '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'title')
					$sql_list_query .= " and contact.title > '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'name')
					$sql_list_query .= " and (contact.firstname > '".addslashes($_SESSION['searchkeywords'][$key])."'  AND contact.lastname > '".addslashes($_SESSION['searchkeywords'][$key])."') ";
				if($value == 'email')
					$sql_list_query .= " and contact.email > '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'company')
					$sql_list_query .= " and property.company > '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'notes')
					$sql_list_query .= " and contact.notes > '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'address')
					$sql_list_query .= " and property.address > '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'county')
					$sql_list_query .= " and county.name > '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'state')
					$sql_list_query .= " and state.name > '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'city')
					$sql_list_query .= " and property.city > '".addslashes($_SESSION['searchkeywords'][$key])."' ";
				if($value == 'zipcode')
					$sql_list_query .= " and property.zipcode > '".addslashes($_SESSION['searchkeywords'][$key])."' ";
			}
		}
	}
	//echo $sql_list_query;
	$sql_list_query .= "  GROUP BY property.id "; 

	// Paging Details
	$go = 1;
	$perpage = 50;
	if(isset($_REQUEST['go']) && $_REQUEST['go'] != '')
		$go = $_REQUEST['go'];
	if($go > 1){
		$chkgo = "?go=".$go;
	}
	$offset = (($go * $perpage) - $perpage);
	$result_total = $db->query($sql_list_query) or die(mysql_error()." ".$sql_list_query);
	$totalrecs = $db->getNumRows($result_total);
	$sql_list_query .= " limit $offset,$perpage";
	//echo $sql_list_query;
	$result = $db->query($sql_list_query) or die(mysql_error()." ".$sql_list_query);
	$count=$db->getNumRows($result);
	$total_pages = ceil($totalrecs / $perpage);
	$start_var = $offset + 1;
	$end_var_records = $count + (($go-1)*$perpage);
	$end_var = $start_var + $perpage - 1;
	$paging_var = "";
	// Build Previous Pagin Link 
	if($go > 1){ 
		$prev = ($go - 1); 
		$paging_var .= "<a href=\"".$current_page."?go=".$prev."\" style='text-decoration:none'><img src='images/previous.gif' alt='Previous' align='absmiddle' border='0' width='8' height='11' /> </a>"; 
	} 
	// Build Paging Link 
	//for($i = 1; $i <= $total_pages; $i++){
		$paging_var .=  "<span> (".($offset+1)." - ".($offset+$perpage)." of ".$totalrecs.") </span>"; 
	//} 
	// Build Next Paging Link 
	if($go < $total_pages){ 
		$next = ($go + 1); 
		$paging_var .=  "<a href=\"".$current_page."?go=".$next."\"  style='text-decoration:none'><img src='images/next.gif' alt='Next' align='absmiddle' border='0' width='8' height='11' /> </a>"; 
	}
	$result = $db->query($sql_list_query) or die($sql_list_query);
	$badCategories = array("-a","-b","-c","-d","prv","Z");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CRM</title>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
</head>

<body>
<? include("header.php"); ?>
<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="4" align="left" valign="top" nowrap="nowrap"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
        <tbody>
          <tr>
            <td width="100%" align="left" valign="top" class="lastview"><b style="white-space: nowrap;">Search Results  &nbsp;&nbsp;</b> </td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td align="left" valign="top" class="mar"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top" class="moduleTitle">Search Results </td>
                      <td align="left" valign="top">&nbsp;</td>
                    </tr>
                  </table>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview">
                      <tr>
                        <td width="13" height="18" align="left" valign="top">&nbsp;</td>
                        <td width="107" align="left" valign="top">&nbsp;</td>
                        <td colspan="5" align="right" valign="top"><span style="padding-right:10px;">
                          <?=$paging_var?>
                        </span></span></td>
                      </tr>
                      <tr bgcolor="#D0E1F4" height="20">
                        <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                        <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="100%" align="left"> <strong>Property ID&nbsp;</strong>&nbsp; </div></td>
                        <td width="123" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> Name</strong>&nbsp;</td>
                        <td width="130" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Company</strong></td>
                        <td width="271" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>City</strong></td>
						<td width="271" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Zipcode</strong></td>
						<td width="150" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Web location</strong></td>
                        <td width="85" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Action</strong>&nbsp;&nbsp;</td>
                        <td width="21" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                      </tr>
                      <? 
			$numrows = $db->getNumRows($result);
			$checked = array();
			while($lrow = $db->fetchArray($result)){
				if(in_array($lrow['property_id'], $checked)) continue;
				$checked[] = $lrow['property_id'];
			?>
                      <tr class="grid" height="20">
                        <td width="13" height="20" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                        <td width="107" nowrap="nowrap" scope="col" class="n"><a href="propertydetail.php?id=<?=$lrow['id']?>" class="n"><?=$lrow['property_id']?></a><!--<div style="white-space: nowrap;" width="100%" align="left">
                            <? //fillCombo('pid','property','id','id','WHERE active=1',$lrow['id'],'dropdown','onchange="transfer(this.value);"')?>
                        </div>--></td>
                        <td height="25" nowrap="nowrap" class="n" scope="col"><a href="propertydetail.php?id=<?=$lrow['id']?>" class="n"><?=$lrow['firstname']?>
                            <?=$lrow['lastname']?></a></td>
                        <td height="25" nowrap="nowrap" class="n" scope="col"><a href="propertydetail.php?id=<?=$lrow['id']?>" class="n"><?=$lrow['company']?></a></td>
                        <td height="25" nowrap="nowrap" class="n" scope="col"><a href="propertydetail.php?id=<?=$lrow['id']?>" class="n"><?=$lrow['city']?></a></td>
						<td height="25" nowrap="nowrap" class="n" scope="col"><a href="propertydetail.php?id=<?=$lrow['id']?>" class="n"><?=$lrow['zipcode']?></a></td>
						<td height="25" nowrap="nowrap" class="n" scope="col">
							<a href="propertydetail.php?id=<?=$lrow['id']?>" class="n">
								<?php	                                       					                                       			
								mysql_select_db('planitlo_locations');
								$sqlGetCats = "SELECT category1 FROM sorting WHERE prop_loc = '".$lrow['property_id']."'";
								if($resultGetCats = mysql_query($sqlGetCats)){
									$countPrivate = 0;
									$allCats = mysql_num_rows($resultGetCats);
									while($rowGetCategories = mysql_fetch_array($resultGetCats)){
										for($l = 0; $l < count($badCategories); $l++){
											$lastStr = substr($rowGetCategories['category1'], -strlen($badCategories[$l]));
											if($lastStr == $badCategories[$l]){
												$countPrivate++;
											}
										}
									}
								}
								if(($countPrivate == $allCats) && ($allCats != 0)){
									echo "<span style='color: red;font-weight: bold;'>Secondary</span>";
								}
								if(($countPrivate != $allCats) && ($allCats != 0)){
									echo "Primary";
								}
								if($allCats == 0){
									echo "<span style='color: red;font-weight: bold;'>Missing</span>";
								}
								mysql_select_db('planitlo_crm');
								?>
							</a>
						</td>
                        <td width="85" height="20" nowrap="nowrap" scope="col"><a href="propertydetail.php?id=<?=$lrow['id']?>" class="n"> View </a> | <a href="editproperty.php?id=<?=$lrow['id']?>" class="n">Edit</a></td>
                      	<td width="21" height="25" nowrap="nowrap" class="col" scope="col">&nbsp;</td>
					  </tr>
                      <? }if($numrows < 1){ echo '<tr><td class="formtxt" colspan="6"> No Records against your search criteria.</td></tr>';}?>
                    </table></td>
    </tr>
</table></td>
                </tr>
            </table></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
  </tbody>
</table>
<? include("footer.php");?>
</body>
</html>
