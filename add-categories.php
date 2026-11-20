<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
	if($_POST){
		$success = array();
		
		$check="SELECT tag_name FROM planitlo_crm.tags WHERE tag_name='".$_REQUEST['tag_name']."'";
		$result_total = $db->query($check) or die(mysql_error()." ".$check);
		$totalrecs = $db->getNumRows($result_total);
		if($totalrecs==0)
		{
			$is_child=($_REQUEST['is_child']!='') ? $_REQUEST['is_child'] : 0;
			$sql = "INSERT INTO `planitlo_crm`.`tags` (`tag_name`,`is_child`,`color_code`) VALUES('".addslashes($_REQUEST['tag_name'])."','".$is_child."','".addslashes($_REQUEST['color_code'])."')";
			if($db->query($sql)){
				$val = 1;
			}
			else 
			{
				$val = 0;
			}
		}
		else 
		{
			$val=2;
		}
		if($val == 0){
			header("Location: add-tag.php?success=0");
		}else if ($val == 1){
			header("Location: tags.php?success=1");
		}else {
			header("Location: add-tag.php?success=2");
		}
		
	}
	/*
	if(isset($_REQUEST['hidSub']) && $_REQUEST['hidSub']==2){
		$success = array();
		
		$check="SELECT tag_name FROM planitlo_crm.tags WHERE tag_name='".$_REQUEST['tag_name']."' AND tag_id!='".$_REQUEST['tag_id']."'";
		$result_total = $db->query($check) or die(mysql_error()." ".$check);
		$totalrecs = $db->getNumRows($result_total);
		if($totalrecs==0)
		{
			//$is_child=($_REQUEST['is_child']!='') ? $_REQUEST['is_child'] : 0;
			$sql = "UPDATE `planitlo_crm`.`tags` SET `tag_name`='".addslashes($_REQUEST['tag_name'])."',`is_child`='".$_REQUEST['is_child']."',`color_code`='".addslashes($_REQUEST['color_code'])."' WHERE tag_id='".$_REQUEST['tag_id']."'";
			if($db->query($sql))
			{
				$val = 1;
			}
			else 
			{
				$val = 0;
			}
		}
		else 
		{
			$val=2;
		}
		if($val == 0){
			header("Location: add-tag.php.php?success=0");
		}else if ($val == 1){
			header("Location: tags.php?success=1");
		}else {
			header("Location: add-tag.php?success=2");
		}
		
	}
	*/
if($_REQUEST['id']!='')
{
	$sel="SELECT * FROM planitlo_crm.tags WHERE tag_id='".$_REQUEST['id']."'";
	$exe = mysql_query($sel) or die(mysql_error()." ".$sel);
	$lrow = mysql_fetch_array($exe);
	
}
?>
<?php 
$t_id=$lrow['tag_id']; 
function is_child1($tag_id)
{
	$sql="SELECT * FROM tags WHERE is_child='".$tag_id."'";
	$ex=mysql_query($sql);	
	
	if(mysql_num_rows($ex)>0)
	{
		echo "<ul>";
		while($rows=mysql_fetch_array($ex))
		{
		?>
			<li class="tags"><span id="tag_<?php echo $rows['tag_id']; ?>"><?php if($GLOBALS['t_id']==$rows['tag_id']) { ?> <img src="https://planitlocations.com/crm/images/right-sign.png" /><?php }  ?><?php echo $rows['tag_name']; ?></span>
			
			<?php 				
				is_child1($rows['tag_id']);									
			?>
			</li>
<?php 
		}
		echo "</ul>";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CRM</title>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	.selCatg{display:block; position:relative; width:320px;}
	.sel_parent{display:block;}
	.sel_parent > span{display:block; font-size:14px; line-height:16px; border:1px solid #aaa; padding:8px 16px 8px 6px; position:relative; cursor:default;}
	.sel_parent > span:after{content:"\f0d7"; font-family: 'FontAwesome'; position:absolute; top:50%; right:6px; font-size:14px; line-height:14px; margin:-7px 0 0 0;}
	.subSelCatg{display:block; margin:8px 0 0 0; width:100%; padding:0; border:1px solid #aaa; background-color:#fff; z-index:99; max-height:240px; overflow:auto;}
	.subSelCatg li{display:block;}
	.subSelCatg li span{display:block; font-size:14px; line-height:16px; padding:5px 16px; cursor:default;}
	.subSelCatg li span:hover{background-color:#f3f3f3;}
	.subSelCatg li ul{display:block; padding:0; margin:0;}
	.subSelCatg li li span{padding-left:40px;}
	.subSelCatg li li li span{padding-left:64px;}
	.subSelCatg li span img{height:16px;}
</style>
<link type="text/css" href="css/technocss.css" rel="stylesheet" />	
<link type="text/css" href="css/font-awesome.css" rel="stylesheet" />
<script>
function validateNumber(event) {
    var key = window.event ? event.keyCode : event.which;

    if (event.keyCode == 8 || event.keyCode == 46
     || event.keyCode == 37 || event.keyCode == 39) {
        return true;
    }
    else if ( key < 48 || key > 57 ) {
        return false;
    }
    else return true;
};
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
            <td width="100%" align="left" valign="top" class="lastview"><b style="white-space: nowrap;">Create Contact </b> </td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td align="left" valign="top" class="mar"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="4" align="left" valign="top" nowrap="nowrap">
	  <?php if($_REQUEST['success']==2) { ?>
	  <label class="fldttl" style="font-color:red !important;">This tag already exists.</label>
	  <?php } ?>
		<div class="popup2">
		<span class="tagTtl">Add new category</span>
		<div class="formBX">
			<div class="fldttl">
				<label>
					<span class="formtxt">
					Tag Category : </span>
				</label>
			</div>
			<div class="autoFill">
				<input type="text"  style="width:250px;margin:0px;" placeholder="Search by tag category" class="txtFld AddtagCategoryID" onkeyup="checkValidation(this.value,'error_category')" autocomplete="off"/>				
			</div>				
			<span class="error" id="error_category"></span>
		</div>
		<div class="buttonset">
			<input type="hidden" name="is_child" id="is_child" class="is_child" value="0" />
			<input type="button" name="sub" id="sub" value="Submit" class="new-Btn button is_enabled addcatAdd" />
			<input type="button" name="sub" value="Cancel" class="new-Btn button red"  onClick="addTaglightbox_close();"/>
			<input type="hidden" id="add_propertyID" />
			<div class="clear"></div>
		</div>
	</div>
      </td>
    </tr>
  </tbody>
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
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("body").click(function(){ 
		jQuery("#tagAutofill, .autofillList").hide();
	});

	jQuery('.addcatAdd').click(function(){				
		var flag=0;
		if(jQuery(".AddtagCategoryID").val()=='')
		{  
			jQuery("#error_category").html("No category name specified. Please try another name.").fadeIn(1000);
			flag=1;
		}
		else 
		{
			jQuery("#error_category").html('');
		}
		
		if(flag==0)
		{
			jQuery.ajax({
				type:"POST",
				url:"ajax_tag.php",
				data:{ 
					'action':'addCategory',
					'category' : jQuery(".AddtagCategoryID").val(), 
				},
				success:function(res){
					var obj=JSON.parse(res);
					if(obj.flag==0)
					{
						jQuery("#error_category").html('This categoy already exists.');
					}
					else 
					{
						jQuery(".AddtagCategoryID").val('');	
					}					
				}
			});	
		}
	});
});			
function addTaglightbox_close()
{
	location.href="https://planitlocations.com/crm/manage-categories.php";	
}
function checkValidation(val,ID)
{ 
	if(val=='')
	{
		jQuery("#"+ID).html('No category name specified. Please try another name.');
	}
	else 
	{
		jQuery("#"+ID).html('');
	}
}
</script>

</body>
</html>
