<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/functions.php');
	$db = new DBConn();
	
	$nameorder = 'ASC';
	$namearrow = 'arrow';
	$titleorder = 'ASC';
	$titlearrow = 'arrow';
	$firstnameorder = 'ASC';
	$firstnamearrow = 'arrow';
	$lastnameorder = 'ASC';
	$lastnamearrow = 'arrow';
	$spouseorder = 'ASC';
	$spousearrow = 'arrow';
	$current_page = 'contact.php';
	if(isset($_REQUEST['del']) && $_REQUEST['del'] == 1 && isset($_REQUEST['id']) && intval($_REQUEST['id'])){
		$sql = "DELETE FROM phone WHERE contact_id=".$_REQUEST['id'];
		$db->query($sql);
		$sql = "DELETE FROM contact WHERE id=".$_REQUEST['id'];
		$db->query($sql);
	}
	
	$sql_list_query = "SELECT *,(select username from user where user.id=contact.userid) as modified_by_name FROM contact WHERE active = 1 ORDER BY id DESC";
	if(isset($_REQUEST['orderby']) && isset($_REQUEST['column'])){
		$sql_list_query = "SELECT *,(select username from user where user.id=contact.userid) as modified_by_name FROM contact WHERE active = 1";
		$sql_list_query .= " ORDER BY ".$_REQUEST['column']." ".$_REQUEST['orderby'];
		/*if($_REQUEST['column'] == 'firstname'){
			if($_REQUEST['orderby'] == 'ASC'){
				$nameorder = 'DESC';
				$namearrow = 'arrow_down';
			}elseif($_REQUEST['orderby'] == 'DESC'){
				$nameorder = 'ASC';
				$namearrow = 'arrow_up';
			}
		}*/
		if($_REQUEST['column'] == 'firstname'){
			if($_REQUEST['orderby'] == 'ASC'){
				$firstnameorder = 'DESC';
				$firstnamearrow = 'arrow_down';
			}elseif($_REQUEST['orderby'] == 'DESC'){
				$firstnameorder = 'ASC';
				$firstnamearrow = 'arrow_up';
			}
		}if($_REQUEST['column'] == 'lastname'){
			if($_REQUEST['orderby'] == 'ASC'){
				$lastnameorder = 'DESC';
				$lastnamearrow = 'arrow_down';
			}elseif($_REQUEST['orderby'] == 'DESC'){
				$lastnameorder = 'ASC';
				$lastnamearrow = 'arrow_up';
			}
		}elseif($_REQUEST['column'] == 'title'){
			if($_REQUEST['orderby'] == 'ASC'){
				$titleorder = 'DESC';
				$titlearrow = 'arrow_down';
			}elseif($_REQUEST['orderby'] == 'DESC'){
				$titleorder = 'ASC';
				$titlearrow = 'arrow_up';
			}
		}elseif($_REQUEST['column'] == 'spousename'){
			if($_REQUEST['orderby'] == 'ASC'){
				$spouseorder = 'DESC';
				$spousearrow = 'arrow_down';
			}elseif($_REQUEST['orderby'] == 'DESC'){
				$spouseorder = 'ASC';
				$spousearrow = 'arrow_up';
			}
		}
	}
	
	$go = 1;
	$perpage = 50;
	if(isset($_REQUEST['go']) && $_REQUEST['go'] != '')
		$go = $_REQUEST['go'];
	if($go > 1){
		if($search == "")
			$chkgo = "?go=".$go;
		else
			$chkgo = "&go=".$go;
	}
	$offset = (($go * $perpage) - $perpage);
	$result_total = $db->query($sql_list_query);
	$totalrecs = $db->getNumRows($result_total);
	$sql_list_query .= " limit $offset,$perpage";
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
		$paging_var .=  "<span class='pageNumbers'> (".($offset+1)." - ".($offset+$perpage)." of ".$totalrecs.")</span>"; 
	//} 
	// Build Next Paging Link 
	if($go < $total_pages){ 
		$next = ($go + 1); 
		$paging_var .=  "<a href=\"".$current_page."?go=".$next."\" style='text-decoration:none'><img src='images/next.gif' alt='Next' align='absmiddle' border='0' width='8' height='11' /></a>"; 
	}
	$result = $db->query($sql_list_query) or die($sql_list_query);
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
            <td width="100%" align="left" valign="top" class="lastview"><b style="white-space: nowrap;">Contact List &nbsp;&nbsp;</b> </td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td align="left" valign="top" class="mar"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top" class="moduleTitle">Contacts <?php	                                       					                                       			 if($_GET['success'] == '0') { echo "-- There was an error while saving contact. Please try again and fill all required fields."; } else if($_GET['success'] == '1') { echo "-- Contact successfully saved."; } ?></td>
                      <td align="left" valign="top">&nbsp;</td>
                    </tr>
                  </table>
                  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  
    <tr>
      <td align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview">
          <tr>
            <td width="4" height="18" align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td width="36" align="left" valign="top">&nbsp;</td>
            <td colspan="4" align="right" valign="middle"><span style="padding-right:10px;"><?=$paging_var?></span></td>
          </tr>
          <tr bgcolor="#D0E1F4" height="20">
          	<td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
            <td width="6%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"> <a href='contact.php?orderby=<?=$titleorder?>&amp;column=title' class="a">Title&nbsp;<img src="images/<?=$titlearrow?>.gif" alt="" align="absmiddle" border="0" height="10" width="8" /></a> </td>
            <td width="7%" nowrap="nowrap" bgcolor="#D0E1F4" class="listViewThS1" scope="col"> <a href='contact.php?orderby=<?=$firstnameorder?>&amp;column=firstname' class="a">First Name&nbsp;<img src="images/<?=$firstnamearrow?>.gif" alt="" align="absmiddle" border="0" height="10" width="8" /></a> </td>
            <td width="7%" height="25" nowrap="nowrap" bgcolor="#D0E1F4" class="listViewThS1" scope="col"> <a href='contact.php?orderby=<?=$lastnameorder?>&amp;column=lastname' class="a">Last Name&nbsp;<img src="images/<?=$lastnamearrow?>.gif" alt="" align="absmiddle" border="0" height="10" width="8" /></a> </td>
            <td width="6%" nowrap="nowrap" class="listViewThS1" scope="col"> Property ID </td>
			<td width="6%" nowrap="nowrap" class="listViewThS1" scope="col"> Phone </td>
            <td width="4%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"> Ext </td>
            <td width="6%" nowrap="nowrap" class="listViewThS1" scope="col"> Priority </td>
            <td width="10%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"> Email </td>
            <td width="15%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"> Notes &nbsp;&nbsp;</td>
            



            <td width="9%" height="25" nowrap="nowrap" scope="col">Created At</td>
            <td width="9%" height="25" nowrap="nowrap" scope="col">Last Updated At</td>
            <td width="9%" height="25" nowrap="nowrap" scope="col">Last Updated By</td>


            <td width="8%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"> Action&nbsp;&nbsp;</td>
            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
          </tr>
		  <? while($lrow = $db->fetchArray($result)){
		  			$psql = "SELECT * FROM phone WHERE contact_id=".$lrow['id']." ORDER by priority asc ";
					$pres = $db->query($psql) or die($psql);
					$prow = $db->fetchArray($pres);
					$pid = ReturnName('contact_to_property','property_id','contact_id',$lrow['id']);
					
					if(strlen($lrow['notes']) > 25){
						$lrow['notes'] = substr($lrow['notes'],0,25)."....";
					}
		  ?>
          <tr class="grid" height="20">
            <td height="25" nowrap="nowrap" scope="col">&nbsp;</td>
            <td width="41" height="25" nowrap="nowrap" scope="col"><span class="n"><?php	                                       					                                       			 if($lrow['title'] != '0') echo $lrow['title']; ?></span></td>
            <td width="74" nowrap="nowrap" scope="col"><a href="viewcontact.php?c=<?=$lrow['id']?>" class="s"><?php	                                       					                                       			 if($lrow['firstname'] != '0') echo $lrow['firstname']; ?></a></td>
            <td width="73" height="25" nowrap="nowrap" scope="col"><a href="viewcontact.php?c=<?=$lrow['id']?>" class="s"><?php	                                       					                                       			 if($lrow['lastname'] != '0') echo $lrow['lastname']; ?></a></td>
            <td width="60" nowrap="nowrap" scope="col"><span class="n"><a href="propertydetail.php?id=<?=$pid?>" class="s"><?php	                                       					                                       			 if($pid != '0') echo $pid; ?></a></span></td>
			<td width="41" nowrap="nowrap" scope="col"><span class="n"><?=CheckUSPhoneNum($prow['number'])?></span></td>
            <td nowrap="nowrap" scope="col"><span class="n"><?php	                                       					                                       			 if($prow['ext'] != '0') echo $prow['ext']; ?></span></td>
            <td nowrap="nowrap" scope="col"><span class="n">&nbsp;<?php	                                       					                                       			 if($prow['priority'] != '0') echo $prow['priority']; ?></span></td>
            <td nowrap="nowrap" scope="col"><span class="n"><?php	                                       					                                       			 if($lrow['email'] != '0') echo $lrow['email']; ?></span></td>
            <td nowrap="nowrap" scope="col"><span class="n"><?php	                                       					                                       			 if($lrow['notes'] != '0') echo $lrow['notes']; ?></span></td>

            <td nowrap="nowrap" scope="col"><span class="n"><?php	echo $lrow['create_date'] ? date('m-d-Y h:i A',$lrow['create_date']) : ''; ?></span></td>
            <td nowrap="nowrap" scope="col"><span class="n"><?php	echo $lrow['modified_date'] ? date('m-d-Y h:i A',$lrow['modified_date']) : ''; ?></span></td>
            <td nowrap="nowrap" scope="col"><span class="n"><?php	echo $lrow['modified_date'] ? $lrow['modified_by_name'] : ''; ?></span></td>

            <td width="63" height="20" nowrap="nowrap" scope="col"><a href="editcontact.php?id=<?=$lrow['id']?>" class="n"> Edit </a> &nbsp;| <a href="contact.php?id=<?=$lrow['id']?>&amp;del=1" onclick="if(confirm('Are you sure want to delete this contact?')){return true;}else{return false};" class="n">Delete </a></td>
            <td width="16" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
          </tr>
		  <? }?>
        </table>
        </td>
      </tr>
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
