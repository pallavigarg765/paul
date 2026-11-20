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
	
	//Select ALL Properties
	$sql_list_query = "SELECT property.* FROM property WHERE property.active=1  ";
	// Paging Details
	$current_page = 'property.php';
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
            <td width="100%" align="left" valign="top" class="lastview"><b style="white-space: nowrap;">Property Listing &nbsp;&nbsp;</b> </td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td align="left" valign="top" class="mar"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="left" valign="top" class="moduleTitle">Property Listing <?php	                                       					                                       			 if($_GET['success'] == '0'){ echo "-- There was an error while saving property. Please try again and fill all required fields."; } else if($_GET['success'] == '1') { echo "-- Property successfully saved."; } ?></td>
                        <td align="left" valign="top">&nbsp;</td>
                      </tr>
                    </table>
                      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview">
                              <tr>
                                <td width="2%" height="18" align="left" valign="top">&nbsp;</td>
                                <td width="13%" align="left" valign="top">&nbsp;</td>
                                <td width="17%" align="left" valign="top">&nbsp;</td>
                                <td width="19%" align="left" valign="top">&nbsp;</td>
                                <td colspan="4" align="right" valign="top"><span style="padding-right:10px;">
                                  <?=$paging_var?>
                                </span></td>
                              </tr>
                              <tr bgcolor="#D0E1F4" height="20">
                                <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                                <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="100%" align="left"> Property ID&nbsp;&nbsp; </div></td>
                                <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="100%" align="left"> Contact Name &nbsp;&nbsp; </div></td>
                                <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="135px" align="left"> Company </div></td>
                                <td width="19%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="100%" align="left"> Address &nbsp;&nbsp;</div></td>
                                <td width="10%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="100%" align="left"> Action&nbsp;&nbsp;</div></td>
                                <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                              </tr>
                            </table>
                              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview">
                                <? 
			$numrows = $db->getNumRows($result);
			while($lrow = $db->fetchArray($result)){
					$lrow['cid'] = ReturnName('contact_to_property','contact_id','property_id',$lrow['id']);
					$lrow['firstname'] = ReturnName('contact','firstname','id',$lrow['cid']);
					$lrow['lastname'] = ReturnName('contact','lastname','id',$lrow['cid']);
			?>
                                <tr class="grid" height="20">
                                  <td width="10" height="20" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                                  <td width="100" nowrap="nowrap" scope="col"><div style="white-space: nowrap;" width="100%" align="left"> <a href="propertydetail.php?id=<?=$lrow['id']?>" class="s">
                                    <?=$lrow['property_id']?>
                                  </a></div></td>
                                  <td width="131" height="20" nowrap="nowrap" scope="col" class="n"><a href="propertydetail.php?id=<?=$lrow['id']?>" class="s">
                                    <?=$lrow['firstname']?>
                                    <?=$lrow['lastname']?>
                                  </a></td>
                                  <td width="141" height="20" nowrap="nowrap" scope="col" class="n"><?=$lrow['company']?>                                  </td>
                                  <td width="129" height="20" nowrap="nowrap" scope="col" class="n"><?=$lrow['address']?>
                                    </a></td>
                                  <td width="92" height="20" nowrap="nowrap" scope="col"><a href="propertydetail.php?id=<?=$lrow['id']?>" class="n"> View </a>| <a href="editproperty.php?id=<?=$lrow['id']?>" class="n"> Edit </a> |<a href="property.php?id=<?=$lrow['id']?>&del=1" onclick="if(confirm('Are you sure want to delete this Property? It will delete contacts, location, budget and tax information related to this property.')){return true;}else{return false};" class="n"> Delete </a></td>
                                </tr>
                                <? }if($numrows < 1){ echo '<tr><td class="formtxt"> No Records in the Database.</td></tr>';}?>
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
