<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
	
	if(isset($_REQUEST['del']) && $_REQUEST['del'] == 1 && isset($_REQUEST['id']) && intval($_REQUEST['id'])){
		$sql = "DELETE FROM contact_to_property WHERE property_id=".$_REQUEST['id'];
		$db->query($sql);
		$sql = "DELETE FROM location WHERE property_id=".$_REQUEST['id'];
		$db->query($sql);
		$sql = "DELETE FROM budget WHERE property_id=".$_REQUEST['id'];
		$db->query($sql);
		$sql = "DELETE FROM tax WHERE property_id=".$_REQUEST['id'];
		$db->query($sql);
		$sql = "DELETE FROM property WHERE id=".$_REQUEST['id'];
		$db->query($sql);
	}
	$id = (int)$_REQUEST['id'];
	/*$next = (int)$_REQUEST['n'];
	$setid = 0;
	$MaxID = $db->getField('property','max(id)','1=1');
	$MinID = $db->getField('property','min(id)','1=1');
	if($next !='' && $next != 0){
		if($next > $MaxID)
			$setid = $MinID;
		else
			$setid = $next;
	}elseif(isset($_REQUEST['p']) && intval($_REQUEST['p'])){
		$previous = $_REQUEST['p'];
		if($previous == 0)
			$setid = $MaxID;
		else
			$setid = $previous;
	}*/
	// Select Property and related information
	$sql = "SELECT property.*,state.name as statename, country.name as countryname FROM property  
			left join state on property.state=state.id
			left join country on property.country=country.id ";
	if($id != 0)
		$sql .= " WHERE property.id=".$id;
	/*if($setid != 0)
		$sql .= " WHERE property.id=".$setid;
	$sql .= " ORDER BY property.id asc LIMIT 0,1 ";
	$presult = $db->query($sql) or die($sql);
	$prow = $db->fetchArray($presult);
	$nexturl = 'property.php?n='.($prow['id']+1);
	$previousurl = 'property.php?p='.($prow['id']-1);*/
	$current_page = 'property.php';
	$go = 1;
	$perpage = 1;
	if(isset($_REQUEST['go']) && $_REQUEST['go'] != '')
		$go = $_REQUEST['go'];
	if($go > 1){
		$chkgo = "?go=".$go;
	}
	$offset = (($go * $perpage) - $perpage);
	$result_total = $db->query($sql);
	$totalrecs = $db->getNumRows($result_total);
	$sql .= " limit $offset,$perpage";
	$result = $db->query($sql) or die(mysql_error()." ".$sql);
	$count=$db->getNumRows($result);
	$total_pages = ceil($totalrecs / $perpage);
	$start_var = $offset + 1;
	$end_var_records = $count + (($go-1)*$perpage);
	$end_var = $start_var + $perpage - 1;
	$paging_var = "";
	// Build Previous Pagin Link 
	if($go > 1){ 
		$prev = ($go - 1); 
		$paging_var .= "<a href=\"".$current_page.$chkgo."\"><img src='images/previous.gif' alt='Previous' align='absmiddle' border='0' width='8' height='11' /> </a"; 
	} 
	// Build Paging Link 
	for($i = 1; $i <= $total_pages; $i++){
		$paging_var .=  "<span class='pageNumbers'> (".($offset+1)." - ".$perpage." of ".$totalrecs.")</span>"; 
	} 
	// Build Next Paging Link 
	if($go < $total_pages){ 
		$next = ($go + 1); 
		$paging_var .=  "<a href=\"".$current_page.$chkgo."\"><img src='images/next.gif' alt='Next' align='absmiddle' border='0' width='8' height='11' /></a>"; 
	}
	$presult = $db->query($sql) or die($sql);
	$prow = $db->fetchArray($presult);
	$ifexist = $db->getNumRows($presult);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CRM</title>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
<script>
function showdiv(divid1,divid2,divid3,divid4){
	document.getElementById(divid1).style.display='block';
	document.getElementById(divid2).style.display='none';
	document.getElementById(divid3).style.display='none';
	document.getElementById(divid4).style.display='none';
}
function confirmdel(){
	if(confirm('Are you sure want to delete this Property? It will delete contacts, location, budget and tax information related to this property.'))
		window.location='property.php?id=<?=$prow['id']?>&del=1';
	else
		return false;
}
</script>
</head>

<body>
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
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td align="left" valign="top" class="mar"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="left" valign="top" class="moduleTitle">Property Information </td>
                        <td align="right" valign="top"><? if($ifexist >= 1){?><a href="editproperty.php?id=<?=$prow['id']?>" class="utilsLink">Edit</a><? }?></td>
                      </tr>
                    </table>
                      <? if($ifexist >= 1){?><table width="100%" border="0" cellpadding="0" cellspacing="0" class="bg_borbot">
                        <tr>
                          <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                    <tr class="bodytext">
                                      <td width="30%" align="left" valign="top">Property ID:</td>
                                      <td width="31%" align="left" valign="top">Name:</td>
                                      <td width="39%" align="left" valign="top">Company:</td>
                                    </tr>
                                    <tr class="formtxt">
                                      <td align="left" valign="top"><?=$prow['id']?></td>
                                      <td align="left" valign="top"><?=$prow['name']?></td>
                                      <td align="left" valign="top"><?=$prow['company']?></td>
                                    </tr>
                                    <tr class="bodytext">
                                      <td align="left" valign="top">Address1:</td>
                                      <td align="left" valign="top">Address2:</td>
                                      <td align="left" valign="top">Date Created : </td>
                                    </tr>
                                    <tr class="formtxt">
                                      <td align="left" valign="top"><?=$prow['address']?></td>
                                      <td align="left" valign="top"><?=$prow['address2']?></td>
                                      <td align="left" valign="top"><?=date("m/d/Y",$prow['created_date'])?></td>
                                    </tr>
                                    <tr class="bodytext">
                                      <td align="left" valign="top">State:</td>
                                      <td align="left" valign="top">Zip Code :</td>
                                      <td align="left" valign="top">City:</td>
                                    </tr>
                                    <tr class="formtxt">
                                      <td align="left" valign="top"><?=$prow['statename']?></td>
                                      <td align="left" valign="top"><?=$prow['zipcode']?></td>
                                      <td align="left" valign="top"><?=$prow['city']?></td>
                                    </tr>
                                    <tr class="bodytext">
                                      <td align="left" valign="top">Country:</td>
                                      <td align="left" valign="top">&nbsp;</td>
                                      <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr class="formtxt">
                                      <td align="left" valign="top"><?=$prow['countryname']?></td>
                                      <td align="left" valign="top">&nbsp;</td>
                                      <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td align="left" valign="top">&nbsp;</td>
                                      <td align="left" valign="top">&nbsp;</td>
                                      <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td align="left" valign="top"><div id="contact_detail">
                                  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                                    <tr>
                                      <td align="left" valign="top" bgcolor="#FFFFFF" class="newhead">Contacts Info</td>
                                    </tr>
                                    <tr>
                                      <td align="left" valign="top" bgcolor="#FFFFFF" class="border2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <? 
										  	//Select Contact Inforamtion
											$sqlcontact = "SELECT contact.* FROM contact_to_property inner join contact on contact_to_property.contact_id=contact.id
											 				WHERE contact_to_property.property_id=".$prow['id'];
											$cresult = $db->query($sqlcontact) or die($sqlcontact);
											$cno = 1;
											while($crow = $db->fetchArray($cresult)){
										  ?>
                                          
                                          <tr>
                                            <td class="side-head">Contact
                                              <?=$cno?></td>
                                          </tr>
                                          <tr>
                                            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                                <tr>
                                                  <td width="12%" align="left" valign="top" class="bodytext">First Name:</td>
                                                  <td width="20%" align="left" valign="top" class="formtxt"><?=$crow['firstname']?></td>
                                                  <td width="9%" align="left" valign="top" class="bodytext">Last Name:</td>
                                                  <td width="24%" align="left" valign="top" class="formtxt"><?=$crow['lastname']?></td>
                                                  <td width="8%" align="left" valign="top" class="bodytext">Title:</td>
                                                  <td width="27%" align="left" valign="top" class="formtxt"><?=$crow['title']?></td>
                                                </tr>
                                                <tr class="bodytext">
                                                  <td align="left" valign="top">Spousre Name:</td>
                                                  <td align="left" valign="top" class="formtxt"><?=$crow['spousename']?></td>
                                                  <td align="left" valign="top">Email:</td>
                                                  <td align="left" valign="top" class="formtxt"><?=$crow['email']?></td>
                                                  <td align="left" valign="top">Web Site:</td>
                                                  <td align="left" valign="top" class="formtxt"><?=$crow['website']?></td>
                                                </tr>
                                                <tr>
                                                  <td align="left" valign="top" class="bodytext">Relationship:</td>
                                                  <td align="left" valign="top" class="formtxt"><?=$relationshipArray[$crow['relationship']]?></td>
                                                  <td align="left" valign="top" class="bodytext">Notes:</td>
                                                  <td colspan="3" align="left" valign="top" class="formtxt"><?=$crow['notes']?></td>
                                                  </tr>
                                            </table></td>
                                          </tr>
                                          <tr>
                                            <td align="center" valign="top"><? 
												//Select Phone types information realted to contacts
												$typesql = "SELECT phone.*, types.name as typename FROM phone left join types on phone.type=types.id 
															WHERE phone.contact_id=".$crow['id'];
												$tresult = $db->query($typesql) or die($typesql);
												if($db->getNumRows($tresult) >= 1){
											?>
                                                <table width="99%" border="0" cellpadding="1" cellspacing="0">
                                                  <tr class="bodytext">
                                                    <td width="33%" align="left" valign="top">Phone 1:</td>
                                                    <td width="29%" align="left" valign="top">Ext 1:</td>
                                                    <td width="20%" align="left" valign="top">Type:</td>
                                                    <td width="18%" align="left" valign="top">Priority :</td>
                                                  </tr>
                                                  <? while($trow=$db->fetchArray($tresult)){?>
                                                  <tr class="formtxt">
                                                    <td align="left" valign="top"><?=$trow['number']?></td>
                                                    <td align="left" valign="top"><?=$trow['ext']?></td>
                                                    <td align="left" valign="top"><?=$trow['typename']?></td>
                                                    <td align="left" valign="top"><?=$priorityArray[$trow['priority']]?></td>
                                                  </tr>
                                                  <? }?>
                                                </table>
                                              <? }?></td>
                                          </tr>
                                          <? $cno ++;}?>
                                      </table></td>
                                    </tr>
                                  </table>
                                </div>
								  <? 
									//Select Location information related to property
									$locqry = "SELECT * FROM location WHERE property_id=".$prow['id'];
									$lresult = $db->query($locqry) or die($locqry);
									$lrow = $db->fetchArray($lresult);
								?>
                                  <br />
                                  <div id="location_detail" style="display:"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    
                                    <tr>
                                      <td align="left" valign="top" class="newhead">Location Info</td>
                                    </tr>
                                    <tr>
                                      <td align="left" valign="top" class="border2"><table width="100%" border="0" cellpadding="2" cellspacing="0" class="formtxt">
                <tr>
                  <td align="right" class="bodytext">License:</td>
                  <td class="formtxt"><? if($lrow['license'] == 1) echo 'Yes'; else echo 'No';?></td>
                  <td align="right" class="bodytext">Sq Feet: </td>
                  <td class="formtxt"><?=$lrow['squarefit']?></td>
                  <td align="right" class="bodytext">Areas off limit: </td>
                  <td class="formtxt"><?=$lrow['limitareas']?></td>
                </tr>
                <tr>
                  <td align="right" class="bodytext">Neighbors:</td>
                  <td class="formtxt"><?=$lrow['neighbors']?></td>
                  <td align="right" class="bodytext">Animals:</td>
                  <td class="formtxt"><?=$lrow['animals']?></td>
                  <td align="right" class="bodytext">Date Built: </td>
                  <td class="formtxt"><?=date("m/d/Y",$lrow['datebuilt'])?></td>
                </tr>
                <tr>
                  <td align="right" class="bodytext">Previous Filming:</td>
                  <td class="formtxt"><?=$lrow['previousfilming']?></td>
                  <td align="right" class="bodytext">Parking:</td>
                  <td class="formtxt"><?=$lrow['parking']?></td>
                  <td align="right" class="bodytext">Bedroom Count: </td>
                  <td class="formtxt"><?=$lrow['bedroom']?></td>
                </tr>
                <tr>
                  <td align="right" class="bodytext">Restroom Count: </td>
                  <td class="formtxt"><?=$lrow['restroom']?></td>
                  <td align="right" class="bodytext">Referal Form: </td>
                  <td colspan="3" class="formtxt"><?=$lrow['referalform']?></td>
                  </tr>
                <tr>
                  <td align="right" class="bodytext">Features:</td>
                  <td colspan="5"><label>
                    <input name="txtadultfriendly" type="checkbox" id="txtadultfriendly" <? if($lrow['adultfriendly'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                    Adult Friendly
                    <input name="txtpool" type="checkbox" id="txtpool" value="1" <? if($lrow['pool'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                    Pool
                    <input name="txttennisecourt" type="checkbox" id="txttennisecourt" value="1" <? if($lrow['tennisecourt'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                    Tennis Court
                    <input name="txtcityview" type="checkbox" id="txtcityview" value="1" <? if($lrow['cityview'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                    City View
                    <input name="txtnicebackyard" type="checkbox" id="txtnicebackyard" value="1" <? if($lrow['nicebackyard'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                    Nice Backyard
                    <input name="txthomeassociation" type="checkbox" id="txthomeassociation" value="1" <? if($lrow['homeassociation'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                    Home Association</label></td>
                  </tr>
                <tr>
                  <td align="right" class="bodytext">Architect:</td>
                  <td class="formtxt"><?=$lrow['architect']?></td>
                  <td align="right" class="bodytext">House Name: </td>
                  <td colspan="3" class="formtxt"><?=$lrow['housename']?></td>
                  </tr>
                <tr>
                  <td align="right" class="bodytext">&nbsp;</td>
                  <td colspan="5" class="formtxt"><label>
                    <input name="txtnewpropertyowner" type="checkbox" id="txtnewpropertyowner" <? if($lrow['newpropertyowner'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                    Find New Property Owner
                    <input name="txtfilmingorevents" type="checkbox" id="txtfilmingorevents" <? if($lrow['filmingorevents'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                    No Longer For Filming or Events</label></td>
                  </tr>
                <tr>
                  <td colspan="6" align="left" class="bodytext"><div id="notes1">
				  <? 
				  	$type = 1;
					$property_id = $lrow['id'];
				  ?>
				  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview2">
                    <tr bgcolor="#D0E1F4" height="20">
                      <td width="2%" height="25" nowrap="nowrap" bgcolor="#E1E1E1" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="4" nowrap="nowrap" bgcolor="#E1E1E1" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a"> Notes&nbsp;&nbsp; </div>                        </td>
                      <td width="15%" height="25" nowrap="nowrap" bgcolor="#E1E1E1" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a"> User &nbsp;&nbsp; </div></td>
                      <td width="14%" height="25" nowrap="nowrap" bgcolor="#E1E1E1" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a"> Modified Date &nbsp;&nbsp; </div></td>
                      <td width="2%" height="25" nowrap="nowrap" bgcolor="#E1E1E1" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
					<? 
						$sqlnotes = "SELECT * FROM notes WHERE type=".$type." and property_id=".$lrow['id'];
						$nresult = $db->query($sqlnotes) or die(mysql_error()." ".$sqlnotes);
						if($db->getNumRows($nresult) >= 1){
						while($nrow = $db->fetchArray($nresult)){
							if($nrow['userid'] == '')
								$nrow['userid'] = 1;
					?>
					<tr height="20">
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="4" nowrap="nowrap" class="listViewThS1" scope="col"><?=$nrow['text']?></td>
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><?=ReturnName('user','username','id',$nrow['userid'])?></td>
                      <td width="14%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><?=date("m/d/Y",strtotime($nrow['datentime']))?></td>
                      <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
					<? }
					}else{?>
					<tr height="20">
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="5" nowrap="nowrap" class="listViewThS1" scope="col">No record exists.</td>
                      <td width="14%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"> <a href="#" onclick="window.open('notes.php?property_id=<?=$lrow['id']?>&type=<?=$type?>&op=1','noteswin','height=190,width=310,scrollbars=0');return false;" class="n"></a> </td>
                      <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
					<? }?>
				  </table>
                  </div></td>
                  </tr>
              </table></td>
                                    </tr>
                                </table>
								</div>
								  <? 
									//Select Budget information related to property
									$bdqry = "SELECT * FROM budget WHERE property_id=".$prow['id'];
									$bresult = $db->query($bdqry) or die($bdqry);
									$brow = $db->fetchArray($bresult);
								?>
                                  <br />
                                  <div id="budget_detail" style="display:"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    
                                    <tr>
                                      <td align="left" valign="top" class="newhead">Budget Info</td>
                                    </tr>
                                    <tr>
                                      <td align="left" valign="top" class="border2"><table width="100%" border="0" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF" class="formtxt">
                <tr>
                  <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="10%">&nbsp;</td>
                      <td width="90%" class="formtxt"><label>
                        <input name="txthighbudget" type="checkbox" id="txthighbudget" <? if($brow['highbudget'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                        High Budget
                        <input name="txtmediumbudget" type="checkbox" id="txtmediumbudget" <? if($brow['mediumbudget'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                        Mid Budget
                        <input name="txtlowbudget" type="checkbox" id="txtlowbudget" <? if($brow['lowbudget'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                        Low Budget</label></td>
                    </tr>
                  </table></td>
                  </tr>
                <tr>
                  <td width="16%" align="right" class="bodytext">Budget Notes :</td>
                  <td width="84%" colspan="5" class="formtxt"><?=$brow['budgetnotes']?></td>
                  </tr>
                <tr>
                  <td colspan="6" class="formtxt"><strong>Film Information </strong></td>
                  </tr>
                <tr>
                  <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="10%">&nbsp;</td>
                      <td width="90%" class="formtxt"><label>
                        <input name="txtfilmfriendly" type="checkbox" id="txtfilmfriendly" <? if($brow['filmfriendly'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                        Film Friendly
                        <input name="txtinteriorfilming" type="checkbox" id="txtinteriorfilming" <? if($brow['interiorfilming'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                        Interior Filming
                        <input name="txtexteriorfilming" type="checkbox" id="txtexteriorfilming" <? if($brow['exteriorfilming'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                        Exterior Filming </label></td>
                    </tr>
                  </table></td>
                  </tr>

                <tr>
                  <td colspan="6" align="right" class="bodytext"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="17%" align="right" bgcolor="#FFFFFF" class="bodytext">Avg. Film Budget:</td>
                      <td width="21%" align="left" bgcolor="#FFFFFF" class="formtxt"><?=$brow['filmbudget']?></td>
                      <td width="17%" align="right" bgcolor="#FFFFFF" class="bodytext">Max Head Count:</td>
                      <td width="18%" align="left" bgcolor="#FFFFFF" class="formtxt"><?=$brow['filmmaxheat']?></td>
                      <td width="10%" align="right" bgcolor="#FFFFFF" class="bodytext">Film Parking: </td>
                      <td width="17%" align="left" bgcolor="#FFFFFF" class="formtxt"><?=$brow['filmparking']?></td>
                    </tr>
                  </table></td>
                  </tr>
                <tr>
                  <td colspan="6" align="left" class="bodytext"><div id="notes1">
				  <? 
				  	$type = 2;
					$property_id = $lrow['id'];
				  ?>
				  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview2">
                    <tr bgcolor="#D0E1F4" height="20">
                      <td width="2%" height="25" nowrap="nowrap" bgcolor="#E1E1E1" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="4" nowrap="nowrap" bgcolor="#E1E1E1" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a"> Notes&nbsp;&nbsp; </div></td>
                      <td width="15%" height="25" nowrap="nowrap" bgcolor="#E1E1E1" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a"> User &nbsp;&nbsp; </div></td>
                      <td width="14%" height="25" nowrap="nowrap" bgcolor="#E1E1E1" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a"> Modified Date &nbsp;&nbsp; </div></td>
                      <td width="2%" height="25" nowrap="nowrap" bgcolor="#E1E1E1" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
                    <? 
						$sqlnotes = "SELECT * FROM notes WHERE type=".$type." and property_id=".$lrow['id'];
						$nresult = $db->query($sqlnotes) or die(mysql_error()." ".$sqlnotes);
						if($db->getNumRows($nresult) >= 1){
						while($nrow = $db->fetchArray($nresult)){
							if($nrow['userid'] == '')
								$nrow['userid'] = 1;
					?>
                    <tr height="20">
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="4" nowrap="nowrap" class="listViewThS1" scope="col"><?=$nrow['text']?></td>
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><?=ReturnName('user','username','id',$nrow['userid'])?></td>
                      <td width="14%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><?=date("m/d/Y",strtotime($nrow['datentime']))?></td>
                      <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
                    <? }
					}else{?>
                    <tr height="20">
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="5" nowrap="nowrap" class="listViewThS1" scope="col">No record exists.</td>
                      <td width="14%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><a href="#" onclick="window.open('notes.php?property_id=<?=$lrow['id']?>&amp;type=<?=$type?>&amp;op=1','noteswin','height=190,width=310,scrollbars=0');return false;" class="n"></a> </td>
                      <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
                    <? }?>
                  </table>
                  </div></td>
                  </tr>
                <tr>
                  <td colspan="6" class="formtxt"><strong>Events Information </strong></td>
                </tr>
                <tr>
                  <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="10%">&nbsp;</td>
                      <td width="90%" class="formtxt"><label>
                        <input name="txteventfriendly" type="checkbox" id="txteventfriendly" <? if($brow['eventfriendly'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                        Event Friendly
                        <input name="txtinteriorevent" type="checkbox" id="txtinteriorevent" <? if($brow['interiorevent'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                        Interior Events
                        <input name="txtexteriorevent" type="checkbox" id="txtexteriorevent" <? if($brow['exteriorevent'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                        Exterior Events </label></td>
                    </tr>
                  </table></td>
                  </tr>
                <tr>
                  <td colspan="6" align="right" class="bodytext"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="17%" align="right" bgcolor="#FFFFFF" class="bodytext">Avg. Event Budget:</td>
                      <td width="21%" align="left" bgcolor="#FFFFFF" class="formtxt"><?=$brow['eventbudget']?></td>
                      <td width="16%" align="right" bgcolor="#FFFFFF" class="bodytext">Max Head Count:</td>
                      <td width="18%" align="left" bgcolor="#FFFFFF" class="formtxt"><?=$brow['eventmaxheat']?></td>
                      <td width="11%" align="right" bgcolor="#FFFFFF" class="bodytext">Event Parking: </td>
                      <td width="17%" align="left" bgcolor="#FFFFFF" class="formtxt"><?=$brow['eventparking']?></td>
                    </tr>
                  </table></td>
                  </tr>
                <tr>
                  <td colspan="6" align="left" class="bodytext"><div id="notes1">
				  <? 
				  	$type = 3;
					$property_id = $lrow['id'];
				  ?>
				  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview2">
                    <tr bgcolor="#D0E1F4" height="20">
                      <td width="2%" height="25" nowrap="nowrap" bgcolor="#E1E1E1" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="4" nowrap="nowrap" bgcolor="#E1E1E1" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a"> Notes&nbsp;&nbsp; </div></td>
                      <td width="15%" height="25" nowrap="nowrap" bgcolor="#E1E1E1" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a"> User &nbsp;&nbsp; </div></td>
                      <td width="14%" height="25" nowrap="nowrap" bgcolor="#E1E1E1" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a"> Modified Date &nbsp;&nbsp; </div></td>
                      <td width="2%" height="25" nowrap="nowrap" bgcolor="#E1E1E1" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
                    <? 
						$sqlnotes = "SELECT * FROM notes WHERE type=".$type." and property_id=".$lrow['id'];
						$nresult = $db->query($sqlnotes) or die(mysql_error()." ".$sqlnotes);
						if($db->getNumRows($nresult) >= 1){
						while($nrow = $db->fetchArray($nresult)){
							if($nrow['userid'] == '')
								$nrow['userid'] = 1;
					?>
                    <tr height="20">
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="4" nowrap="nowrap" class="listViewThS1" scope="col"><?=$nrow['text']?></td>
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><?=ReturnName('user','username','id',$nrow['userid'])?></td>
                      <td width="14%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><?=date("m/d/Y",strtotime($nrow['datentime']))?></td>
                      <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
                    <? }
					}else{?>
                    <tr height="20">
                      <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                      <td height="25" colspan="5" nowrap="nowrap" class="listViewThS1" scope="col">No record exists.</td>
                      <td width="14%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><a href="#" onclick="window.open('notes.php?property_id=<?=$lrow['id']?>&amp;type=<?=$type?>&amp;op=1','noteswin','height=190,width=310,scrollbars=0');return false;" class="n"></a> </td>
                      <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                    </tr>
                    <? }?>
                  </table>
                  </div></td>
                  </tr>
              </table></td>
                                    </tr>
                                </table>
								</div>
								  <?
								//Select Tax information related to property
								$tqry = "SELECT * FROM tax WHERE property_id=".$prow['id'];
								$tresult = $db->query($tqry) or die($tqry);
								$trow = $db->fetchArray($tresult);
								?>
                                  <br />
                                  <div id="tax_detail" style="display:"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    
                                    <tr>
                                      <td align="left" valign="top" class="newhead">Tax Info</td>
                                    </tr>
                                    <tr>
                                      <td align="left" valign="top" class="border2"><table width="100%" border="0" cellpadding="2" cellspacing="0" class="formtxt">
											<tr>
											  <td align="right" class="bodytext">Tax Company:</td>
											  <td class="formtxt"><?=$trow['company']?></td>
											  <td align="right" class="bodytext">Taxt First Name: </td>
											  <td class="formtxt"><?=$trow['firstname']?></td>
											  <td align="right" class="bodytext">Tax Last Name: </td>
											  <td class="formtxt"><?=$trow['lastname']?></td>
											</tr>
											<tr>
											  <td align="right" class="bodytext">Tax Address:</td>
											  <td class="formtxt"><?=$trow['address']?></td>
											  <td align="right" class="bodytext">Tax Address2:</td>
											  <td class="formtxt"><?=$trow['address2']?></td>
											  <td align="right" class="bodytext">Tax City: </td>
											  <td class="formtxt"><?=$trow['city']?></td>
											</tr>
											<tr>
											  <td align="right" class="bodytext">Tax State:</td>
											  <td class="formtxt"><?=$trow['state']?></td>
											  <td align="right" class="bodytext">Tax Zipcode:</td>
											  <td class="formtxt"><?=$trow['zipcode']?></td>
											  <td align="right" class="bodytext">&nbsp;</td>
											  <td>&nbsp;</td>
											</tr>
											<tr>
											  <td align="right" class="bodytext">Tax Amount:</td>
											  <td colspan="5" class="formtxt"><label>$ 
												<?=$trow['amount']?>
											  </label></td>
											</tr>
											<tr>
											  <td align="right" class="bodytext">Tax Notes1:</td>
											  <td class="formtxt"><?=$trow['notes1']?></td>
											  <td align="right" class="bodytext">Tax Notes2:  </td>
											  <td colspan="3" class="formtxt"><?=$trow['notes2']?></td>
										    </tr>
											<tr>
											  <td align="right">&nbsp;</td>
											  <td colspan="5" class="formtxt"><label>
												<input name="txtmailtaxes" type="checkbox" id="txtmailtaxes" <? if($trow['mailtaxes'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
												Do Not Mail Taxes
												<input name="txtquestionable" type="checkbox" id="txtquestionable" <? if($trow['questionable'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
												Questionable</label></td>
											</tr>
										  </table></td>
                                    </tr>
                                </table>
								</div>								
                                <br /></td>
                              </tr>
                              <tr>
                                <td height="40" align="left" valign="bottom"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td width="30%" align="left"><table width="80%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td align="left" valign="middle" class="bodttxt"><input type="checkbox" name="checkbox" value="checkbox" <? if($prow['audit1'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                                              Audit 1 </td>
                                            <td align="left" valign="middle" class="bodttxt"><input type="checkbox" name="checkbox" value="checkbox" <? if($prow['audit2'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                                              Audit 2 </td>
                                          </tr>
                                          <tr>
                                            <td align="left" valign="middle" class="bodttxt"><input type="checkbox" name="checkbox" value="checkbox" <? if($prow['audit3'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                                              Audit 3 </td>
                                            <td align="left" valign="middle" class="bodttxt"><input type="checkbox" name="checkbox" value="checkbox" <? if($prow['taxaudit'] == 1){?> checked="checked" <? }?> disabled="disabled"/>
                                              Tax Audit </td>
                                          </tr>
                                      </table></td>
                                      <td width="70%" align="left" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td align="left" valign="top" class="formtxt"><?=$paging_var?></td>
                                            <td width="28%" align="left" valign="top" class="dataLabel">
                                              <input title="Select [Alt+T]" accesskey="T" class="button" value="Add Record" name="btn1222" language="javascript" onclick='window.location="addproperty.php";'type="button" />
                                            </td>
                                            <td width="28%" align="left" valign="top" class="dataLabel">
                                              <input title="Select [Alt+T]" accesskey="T" class="button" value="Delete Record" name="btn12222" language="javascript" onclick="confirmdel();" type="button" />
                                            </td>
                                          </tr>
                                      </table></td>
                                    </tr>
                                </table></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table><? }else{?>
                    <span class="bodttxt">No Property Found.</span>                    <? }?></td>
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
