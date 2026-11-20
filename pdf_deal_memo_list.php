<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/functions.php');
	$db = new DBConn();
	

	$current_page = 'pdf_deal_memo_list.php';
	$criteria = "";
	//$sql_list_query = "SELECT DISTINCT(T.tag_name),T.tag_id,T.color_code,C.category FROM `tags` AS T INNER JOIN `tag_categories` AS C ON C.id=T.category_id  ORDER BY tag_name ASC";
	$sql_list_query="SELECT C.*, P.property_id as property_code  FROM `pdf_deal_memo` AS C LEFT JOIN property as P ON P.id = C.property_id ORDER BY C.id DESC";

	
	// Paging Details
	$go = 1;
	$perpage = 20;
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
<?php 

if($_REQUEST['del']=='delete')
{
	$c_id=$_REQUEST["id"];	

	$tagDel="DELETE FROM pdf_deal_memo WHERE id='".$c_id."'";
	mysql_query($tagDel);
	
	header("location:pdf_deal_memo_list.php?success=4");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CRM</title>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="css/technocss.css" rel="stylesheet" />	
<link rel="stylesheet" href="simple_color_picker_master/css/colorPicker.css" type="text/css" />
<style type="text/css">
	label.fldttl22 {
    color: green;
    margin-top: 10px;
    display: block;
    margin-bottom: 10px;
    font-size: 12px;
}
</style>
</head>
<body>
<? include("header.php"); ?>
<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="4" align="left" valign="top" nowrap="nowrap"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
        <tbody>
          <tr>
            <td width="100%" align="left" valign="top" class="lastview"><b style="white-space: nowrap;">Manage Deal Memo  &nbsp;&nbsp;</b></td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td align="left" valign="top" class="mar"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top" class="moduleTitle">Manage Deal Memo
                      <?php if($_REQUEST['success']==4) { ?>
                  			<br>
					  		<label class="fldttl22" style="font-color:green !important;">Record deleted successfully.</label>
					  	<?php } elseif ($_REQUEST['success']==2) { ?>
					  		<br>
					  		<label class="fldttl22" style="font-color:green !important;">Record created successfully.</label>
					  	<?php } elseif ($_REQUEST['success']==3) { ?>
					  		<br>
					  		<label class="fldttl22" style="font-color:green !important;">Record updated successfully.</label>
					  	<?php } ?>
					  </td>
                      <td align="left" valign="top"><input onclick="location.href='pdf_deal_memo.php'" type="button" class="button" value="Add New" style="float: right;margin-bottom: 10px;height: 25px;"><br></td>
                    </tr>
                  </table>
                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview">
                          <tr>
                            <td width="13" height="18" align="left" valign="top">&nbsp;</td>
                            <td width="107" align="left" valign="top">&nbsp;</td>
                            <td colspan="10" align="right" valign="top"><span style="padding-right:10px;">
                              <?=$paging_var?>
                            </span></span></td>
                          </tr>
                          <tr bgcolor="#D0E1F4" height="20">
                            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="100%" align="left"> <strong>Sl No&nbsp;</strong>&nbsp; </div></td>
							<td width="123" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Property</strong>&nbsp;</td>

                            <td width="123" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Project Type</strong>&nbsp;</td>
                            <td width="123" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Producer / Event Organizer</strong></td>
                            <td width="123" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Location Owner</strong></td>
                            <td width="123" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Location Rental Date</strong></td>
                            <td width="123" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Creation Date</strong></td>
                            <td width="123" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Last Updated Date</strong></td>
                            <td width="123" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Status</strong></td>
							<td width="85" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Action</strong>&nbsp;&nbsp;</td>
                            <td width="21" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                          </tr>
                          <? 
						$numrows = $db->getNumRows($result); 
							$c=1;
						while($lrow = $db->fetchArray($result)){
							
							?>
                          <tr class="grid" height="20">
                            <td width="13" height="20" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                            <td width="107" nowrap="nowrap" scope="col" class="n"><?php echo (($perpage*($go-1))+$c); ?></td>
                            <td height="25" nowrap="nowrap" class="n" scope="col"><?php
							if ($lrow['property_id']!="") {
								?>
								<a target="_blank" href="propertydetail.php?id=<?php echo $lrow['property_id'] ?>"><?php echo $lrow['property_code'] ?></a>
								<?php
							}  else {
								echo '';	
							}
							?></td>
							<td height="25" nowrap="nowrap" class="n" scope="col"><?php echo $lrow['project_type']; ?></td>
                            <td height="25" nowrap="nowrap" class="n" scope="col"><?php echo $lrow['producer']; ?></td>
                            <td height="25" nowrap="nowrap" class="n" scope="col"><?php echo $lrow['location_owner']; ?></td>
                            <td height="25" nowrap="nowrap" class="n" scope="col"><?php echo $lrow['location_rental_date']; ?></td>
                           
                            <td height="25" nowrap="nowrap" class="n" scope="col"><?php echo date('m-d-Y h:i A',strtotime($lrow['created_at'])); ?></td>
                            <td height="25" nowrap="nowrap" class="n" scope="col"><?php echo date('m-d-Y h:i A',strtotime($lrow['updated_at'])); ?></td>
                             <td height="25" nowrap="nowrap" class="n" scope="col"><?php echo ucfirst($lrow['status']); ?></td>
							<td height="25" nowrap="nowrap" class="n" scope="col">
								<?php if($lrow['pdf_name']!=""){ ?>
								<a class="docategoryEdit n" href="/crm/pdfs/<?php echo $lrow['pdf_name']; ?>" target="_blank">PDF</a> 										
								&nbsp; | &nbsp;
								<?php } else { ?>
									<a class="docategoryEdit n" href="javascript:;" style="color:#FFF;cursor: default;">PDF &nbsp; | &nbsp;</a>
								<?php } ?>
								<?php if ($lrow['property_id']!="") { ?>
								<a class="docategoryEdit n" href="/search/searchid.php?id=<?php echo $lrow['property_code'] ?>" target="_blank">Thumbnails</a> 										
								&nbsp; | &nbsp;
								<?php } ?>
								<a class="docategoryEdit n" href="pdf_deal_memo.php?edit_id=<?php echo $lrow['id']; ?>">Edit</a> 										
								&nbsp; | &nbsp; <a href="javascript:if(confirm('Are you sure you want to delete it?')){window.location='pdf_deal_memo_list.php?id=<?php echo $lrow['id']; ?>&del=delete'}" class="n">Delete</a></td>							
						  </tr>							 
                          <? $c=$c+1; }if($numrows < 1){ echo '<tr><td class="formtxt" colspan="6"> No records found.</td></tr>';}?>
                        </table></td>
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


<!-- --------- END  MANAGE TAGS------- !-->
<? include("footer.php");?>
</body>
</html>
