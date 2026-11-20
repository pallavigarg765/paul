<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
	if(isset($_REQUEST['hidSub']) && $_REQUEST['hidSub']==1){
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
if($_REQUEST['id']!='')
{
	$sel="SELECT * FROM planitlo_crm.tags WHERE tag_id='".$_REQUEST['id']."'";
	$exe = mysql_query($sel) or die(mysql_error()." ".$sel);
	$lrow = mysql_fetch_array($exe);
	
}
?>
<?php 
$t_id=$lrow['tag_id']; 
function is_child($tag_id)
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
				is_child($rows['tag_id']);									
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
	  <form id="form1" name="form1" method="post" action="add-tag.php">
        <?php 
		if($_REQUEST['success']==2)
		{
			echo "This tag already exists.";
		}
		?>
		<table width="62% border="0" cellspacing="0" cellpadding="0">

          <tr>
            <td width="31%" align="left" valign="top"><span class="formtxt">Please Enter Tag Name :</span></td>
            <td width="21%" align="left" style="width: 20px;" valign="top">&nbsp;</td>
			 <td valign="top" align="left" style="width: 30px;">&nbsp;</td>
          </tr>
       
          <tr>
            <td valign="top" align="left"><input type="text" maxlength="255" size="35" class="form" value="<?php if($lrow['tag_name']!='') { echo $lrow['tag_name']; } ?>" id="tag_name" name="tag_name" tabindex="1"></td>
            <td valign="top" align="left" style="width: 30px;">&nbsp;</td>
			 <td valign="top" align="left" style="width: 30px;">&nbsp;</td>
          </tr>
         <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
		  <tr>
            <td align="left" valign="top" colspan="2"><span class="formtxt">
			<?php 
			$is_child=($lrow['is_child']!=0) ? $lrow['is_child'] : 0;
			?>
			<input type="checkbox" name="is_nested" id="is_nested" <?php if($is_child!=0) { echo 'checked'; } ?>/>Nest label under : </span></td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
		    <tr>
            <td valign="top" align="left">			
				<?php 
				$sel="SELECT * FROM tags WHERE  is_child=0";
				$exe=mysql_query($sel);
				?>
				<ul class="subSelCatg">
				<?php 
				while($rows=mysql_fetch_array($exe))
				{
				?>
					<li class="tags"><span id="tag_<?php echo $rows['tag_id']; ?>"><?php if($lrow['tag_id']==$rows['tag_id']) { ?> <img src="https://planitlocations.com/crm/images/right-sign.png" /><?php }  ?><?php echo $rows['tag_name']; ?></span>
					<?php 
						$sql="SELECT * FROM tags WHERE  is_child='".$rows['tag_id']."'";
						$ex=mysql_query($sql);
						if(mysql_num_rows($ex)>0)
						{
							is_child($rows['tag_id']);
						}
					?>
					</li>	
				<?php 
				}
				?>
				</ul>					
			</td>
            <td valign="top" align="left" style="width: 30px;">&nbsp;</td>
			 <td valign="top" align="left" style="width: 30px;">&nbsp;</td>
          </tr>
		  <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>		  
		  <tr>
            <td width="31%" align="left" valign="top"><span class="formtxt">Please choose color code :</span></td>
            <td width="21%" align="left" style="width: 20px;" valign="top">&nbsp;</td>
			 <td valign="top" align="left" style="width: 30px;">&nbsp;</td>
          </tr>
		
		   <tr>
            <td valign="top" align="left"><input class='simple_color_picker' type="text" name="color_code" value="<?php if($lrow['color_code']!='') { echo $lrow['color_code']; } else { echo '#F5F5F5'; } ?>"/></td>
            <td valign="top" align="left" style="width: 30px;">&nbsp;</td>
			 <td valign="top" align="left" style="width: 30px;">&nbsp;</td>
          </tr>
		    <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" align="left" valign="top"><table width="60%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="7%" align="left" valign="top">&nbsp;</td>
                  <td width="36%" align="left" valign="top"><span class="dataField"><span class="dataLabel" style="display: inline;">
                    <input name="hdncontact" type="hidden" value="1" /><input tabindex="14" class="button" value="<?php echo ($_REQUEST['id']!='') ? 'Update Tags' : 'Create Tags' ?>" name="createTags" id="createTags" type="button" />
					<input type="hidden" name="is_child" id="is_child" value="<?php echo ($lrow['is_child']!='') ? $lrow['is_child'] : 0; ?>" />
					<?php if($_REQUEST['id']!='') { ?>
						<input type="hidden" name="tag_id" value="<?php echo $lrow['tag_id']; ?>" />
						<input type="hidden" name="hidSub" value="2" />
					<?php } else {  ?>
							<input type="hidden" name="hidSub" value="1" />
					<?php } ?>
				  </span></span></td>
                  <td width="50%" align="left" valign="top"><input tabindex="15" name="Button2" type="button" class="button" value="Cancel" onclick="history.back();"/></td>
                  <td width="7%" align="left" valign="top">&nbsp;</td>
                </tr>
            </table></td>
          </tr>
        </table>
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

<script type="text/javascript">
jQuery(document).ready(function() {
	
	jQuery('#createTags').click(function(){
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
</script>

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
	jQuery(".tags span").click(function(){ 
		var tagID=jQuery(this).attr('id');
		var split=tagID.split('_');
		jQuery("#is_child").val(split[1]);
		jQuery("#is_nested").attr('checked','checked');
		return;
	});
	jQuery("#is_nested").click(function(){ 
		if(jQuery("#is_child").val()!=0)
		{
			jQuery("#is_nested").removeAttr("checked");
			jQuery("#is_child").val(0);
		}
		else 
		{
			alert('Please select nest label');
			return false;
		}
	});
});
</script>
