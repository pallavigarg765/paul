<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
	

	if($_POST){
		$success = array();
		
		$check="SELECT tag_name FROM planitlo_crm.tags WHERE tag_name='".$_REQUEST['tag_name']."' AND tag_id!='".$_REQUEST['tag_id']."'";
		$result_total = $db->query($check) or die(mysql_error()." ".$check);
		$totalrecs = $db->getNumRows($result_total);
		if($totalrecs==0)
		{
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
	
if($_REQUEST['id']!='')
{
	$sel="SELECT * FROM planitlo_crm.tags WHERE tag_id='".$_REQUEST['id']."'";
	$exe = mysql_query($sel) or die(mysql_error()." ".$sel);
	$lrow = mysql_fetch_array($exe);
	
	$parent="SELECT * FROM planitlo_crm.tags WHERE tag_id='".$lrow['is_child']."'";
	$p_exe = mysql_query($parent) or die(mysql_error()." ".$parent);
	$pl_row = mysql_fetch_array($p_exe);
	$color_code=($lrow['color_code']!='') ? $lrow['color_code'] : "#F5F5F5";
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
	  <form id="form1" name="form1" method="post" action="edit-tag.php">
		<div id="edit_tag_light" class="editTagBox relative autuResize nwForm">
		<span class="tagTtl">Edit tag</span>
		<label class="fldttl">Edit tag name:</label>
		<input type="text" name="tag_name" id="tag_name" maxlength="50" value="<?php echo $lrow['tag_name']; ?>" autocomplete="off" class="txtFld" />
		<label class="fldttl">Please choose color code :</label>
		<input class='simple_color_picker txtFld' type="text" name="color_code" value="<?php echo $color_code; ?>"/>
		<label class="fldttl">
			<input type="checkbox" name="is_nested" id="is_nestedEdit" class="is_nested"/>Nest label under : </label>
			<div id="hierarchicalEdit">
				
			</div>
		<input type="hidden" name="tag_id" id="hidTag_id" value="<?php echo $lrow['tag_id']; ?>" />
		<div class="buttonset">
			<input type="hidden" name="is_child" id="is_childEdit" class="is_child" value="<?php echo $lrow['is_child']; ?>" />
			<input type="button" name="sub" id="actionToEdit" value="Submit" class="new-Btn button is_enabled" />
			<input type="button" name="sub" id="subx" value="Cancel" onClick="edit_close();" class="new-Btn button red" />
			<div class="clear"></div>
		</div>			
	</div>
      </form>
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
</body>
</html>


<script type="text/javascript" src="simple_color_picker/src/jquery.simple-color.js"></script>

<script  type="text/javascript">
jQuery(document).ready(function(){
 
  jQuery('.simple_color_picker').simpleColor({ displayColorCode: true });
  jQuery('.simple_color_kitchen_sink').simpleColor({
    boxHeight: 40,
    cellWidth: 20,
    cellHeight: 20,
    chooserCSS: { 'border': '1px solid #660033' },
    displayCSS: { 'border': '1px solid red' },
    displayColorCode: true,
    livePreview: true,
    onSelect: function(hex, element) {
      alert("You selected #" + hex + " for input #" + element.attr('class'));
    },
    onCellEnter: function(hex, element) {
      console.log("You just entered #" + hex + " for input #" + element.attr('class'));
    },
    onClose: function(element) {
      alert("color chooser closed for input #" + element.attr('class'));
    }
  });

});
</script>
<script type="text/javascript">
jQuery(document).ready(function(){	
	jQuery.ajax({ 
		type : 'POST',
		url : 'ajax_tag.php',					
		data : 
		{ 
			action : 'hierarchical',
		},
		beforeSend: function()
		{
			jQuery("#hierarchicalEdit").html('<img src="https://planitlocations.com/crm/images/ajax-loader.gif">');	
		},			
		success : function(data)
		{
			jQuery("#hierarchicalEdit").html(data);	
			setTimeout(fnLoad, 500);
				
		}				
	});
	jQuery("#is_nestedEdit").live("click", function(){	
		secondcheckbox();
	});
	jQuery(".sel_parent").live("click", function(){
		if(!jQuery(".subSelCatg").is(":visible")){			
			jQuery(".subSelCatg").show();
		}else{
			jQuery(".subSelCatg").hide();	
		}
	});
	/*
	jQuery(".selTag").click(function(){
		jQuery(".subSelCatg").show();
		jQuery(".selTag").hide();	
	});
	*/
	jQuery(".subSelCatg li").live("click", function(){
		jQuery(".subSelCatg").hide();	
	});
	jQuery(".tags span").live("click", function(){
		var tagID=jQuery(this).attr('id');
		var split=tagID.split('_');
		var thisHtml = jQuery(this).html();
		jQuery(".is_child").val(split[1]);
		jQuery( ".is_nested" ).attr( "checked", "checked" );
		jQuery(".sel_parent").html('<span>' + thisHtml + '</span>');
		jQuery(".is_enabled").removeAttr('disabled');
	});
	jQuery("#actionToEdit").live('click',function(){ 	
		if(jQuery('#tag_name').val()=='')
		{
			alert('Please enter tag name');
			return false;
		}
		else 
		{
			jQuery("#form1").submit();
		}	
	});
});
function secondcheckbox(){
	if(jQuery("#is_nestedEdit").is(':checked')==true){ 
		jQuery(".sel_parent").html('<span>Please select a parent</span>');
		jQuery("#actionToEdit").attr('disabled','disabled');
	}
	else 
	{
		jQuery(".sel_parent").html('<span></span>');
		jQuery("#is_childEdit").val('0');
		jQuery(".subSelCatg").hide();
		jQuery("#is_nestedEdit").removeAttr('checked');
		jQuery("#actionToEdit").removeAttr('disabled');
	}
}
function fnLoad()
{
	jQuery("#sel_parent").html('<span><?php echo $pl_row['tag_name']; ?></span>');
}
function edit_close()
{
	location.href="https://planitlocations.com/crm/tags.php";
}
</script>
