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
	$current_page = 'tags.php';
	$criteria = "";
	//$sql_list_query = "SELECT DISTINCT(T.tag_name),T.tag_id,T.color_code,C.category FROM `tags` AS T INNER JOIN `tag_categories` AS C ON C.id=T.category_id  ORDER BY tag_name ASC";
	$sql_list_query="SELECT * FROM `tag_categories` AS C ORDER BY C.category ASC";

	
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
	
	$badCategories = array("-a","-b","-c","-d","prv","Z");
?>
<?php 

if($_REQUEST['del']=='delete')
{
	$c_id=$_REQUEST["id"];	

	$tagDel="DELETE FROM tag_categories WHERE id='".$c_id."'";
	mysql_query($tagDel);
	
	header("location:manage-categories.php?success=4");
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
</head>
<body>
<? include("header.php"); ?>
<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="4" align="left" valign="top" nowrap="nowrap"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
        <tbody>
          <tr>
            <td width="100%" align="left" valign="top" class="lastview"><b style="white-space: nowrap;">Manage Categories  &nbsp;&nbsp;</b> </td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td align="left" valign="top" class="mar"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top" class="moduleTitle">Manage Categories </td>
                      <td align="left" valign="top">&nbsp;</td>
                    </tr>
                  </table>
                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview">
                          <tr>
                            <td width="13" height="18" align="left" valign="top">&nbsp;</td>
                            <td width="107" align="left" valign="top">&nbsp;</td>
                            <td colspan="5" align="right" valign="top"><span style="padding-right:10px;">
                              <?=$paging_var?>
                            </span></span></td>
                          </tr>
                          <tr bgcolor="#D0E1F4" height="20">
                            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="100%" align="left"> <strong>Sl No&nbsp;</strong>&nbsp; </div></td>
                            <td width="123" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> Categories</strong>&nbsp;</td>
                            <td width="271" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Tag Count</strong></td>
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
                            <td height="25" nowrap="nowrap" class="n" scope="col"><?php echo $lrow['category']; ?></td>
                            <td height="25" nowrap="nowrap" class="n" scope="col">
							<?php 
							$sql="SELECT * FROM `tags` WHERE category_id='".$lrow['id']."'";
							$ex=mysql_query($sql);
							echo mysql_num_rows($ex);
							?>
							</td>
							<td height="25" nowrap="nowrap" class="n" scope="col">
								<a class="docategoryEdit n" id="edit_<?php echo $lrow['id']; ?>" href="javascript:;">	Edit</a> 										
								&nbsp; | &nbsp; <a href="javascript:if(confirm('Are you sure you want to delete it?')){window.location='manage-categories.php?id=<?php echo $lrow['id']; ?>&del=delete'}" class="n">Delete</a></td>							
						  </tr>							 
                          <? $c=$c+1; }if($numrows < 1){ echo '<tr><td class="formtxt" colspan="6"> No Records against your search criteria.</td></tr>';}?>
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
<script language="javascript" type="text/javascript" src="simple_color_picker_master/js/jquery.colorPicker.js"/></script>
<script type="text/javascript">
jQuery(document).ready(function(){ 
	jQuery(".docategoryEdit").live("click", function(){
		var me=jQuery(this);
		var attr=me.attr('id');
		var sp=attr.split('_');
		//manage_close();
		jQuery.ajax({ 
			type : 'POST',
			url : 'ajax_tag.php',					
			data : 
			{ 
				action : 'categoryEdit',
				c_id : sp[1]						
			},	
			success : function(data)
			{  
				window.scrollTo(0,0);
				jQuery('#edit_light').fadeIn(600);
				jQuery('#fade').fadeIn(600);
				var obj=JSON.parse(data);
				
				jQuery(".edittagCategoryID").val(obj.category_name);
				jQuery("#is_category_id").val(obj.category_id);
				jQuery(".error").html('');						
			}				
		});					
	});
	jQuery('#actionToEdit').live("click", function(){
		var flag=0;
		if(jQuery(".edittagCategoryID").val()=='')
		{  
			jQuery("#error_is_category_id").html("No category name specified. Please try another name.").fadeIn(1000);
			flag=1;
		}
		else 
		{
			jQuery("#error_is_category_id").html('');
		}
		if(flag==0)
		{
			jQuery.ajax({ 
				type : 'POST',
				url : 'ajax_tag.php',					
				data : 
				{ 
					action : 'actionEditCategory',
					category : jQuery(".edittagCategoryID").val(),
					category_id : jQuery("#is_category_id").val(),
				},	
				success : function(data)
				{ 
					if(data==1)
					{
						jQuery("#error_is_category_id").html('This category already exists.');
					}
					else 
					{
						location.href="https://planitlocations.com/crm/manage-categories.php";	
					}
				}				
			});
		}					
	});
	jQuery('.edittagCategoryID').live("keyup", function(){
		if(jQuery(this).length>0)
		{
			jQuery("#error_is_category_id").html('');
		}
		else 
		{
			jQuery("#error_is_category_id").html('No category name specified. Please try another name.');
		}
	});
	jQuery(document).keyup(function(e){ 
		if (e.keyCode == 27) 
		{  
			jQuery(".closeN").trigger("click");
		}
	});
	
	jQuery("body").not('#tagCategoryID , .AddtagCategoryID').click(function(){ 
		jQuery("#tagAutofill, .autofillList").hide();
	});
});
function edit_close()
{
	jQuery('#edit_light').fadeOut(600);
	jQuery('#fade').fadeOut(600);
	jQuery("#manage_light").removeClass('onOffBlock');
	jQuery("#manage_close_button").show();
}
</script>

<!-- --------- MANAGE TAGS------- !-->
<div id="manage_fade"></div>
<div id="edit_light" class="editTagBox1 newEdit_light" style="z-index:1004 !important; ">
<div onClick="edit_close();" id="edit_del_close_button"  style="display:none;" class="closeN">X</div>
	<span class="tagTtl">Edit tag category</span>
	<div class="formBX">
		<label class="fldttl">Tag Category :</label>
		<div class="autoFill">
			<input type="text"  style="width:250px;margin:0px;" placeholder="Search by tag category" class="txtFld AddtagCategoryID edittagCategoryID"/>			
		</div>
		<span class="error" id="error_is_category_id"></span>
	</div>
	<input type="hidden" name="hid_tag_id" id="hidTag_id" />
	<div class="buttonset">
		<input type="hidden" name="is_child" id="is_category_id" class="is_child" />
		<input type="button" name="sub" id="actionToEdit" value="Submit" class="new-Btn button is_enabled" />
		<input type="button" name="sub" id="subx" value="Cancel" onClick="edit_close();" class="new-Btn button red" />
		<input type="hidden" name="hid_category_id" id="hid_category_id"  value=""/>
		<div class="clear"></div>
	</div>			
</div>
<div id="fade"></div>
<script language="javascript" type="text/javascript" src="simple_color_picker_master/js/jquery.colorPicker.js"/></script>
<link rel="stylesheet" href="simple_color_picker_master/css/colorPicker.css" type="text/css" />
<script type="text/javascript">
jQuery(document).ready(function(){    
	jQuery(".simple_color_picker").colorPicker({ 
		onColorChange : function(id, newValue) { 
			jQuery(this).val(newValue);
		} 
	});
});
</script>
<!-- --------- END  MANAGE TAGS------- !-->
<? include("footer.php");?>
</body>
</html>
