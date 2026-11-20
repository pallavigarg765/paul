<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
	if($_POST){
		$success = array();
		
		if(isset($_POST['btnUpdate']) && (isset($_POST['txtValue'])) && ($_POST['txtValue'] != "")){
			$value = mysql_real_escape_string($_POST['txtValue']);
			$sql = "UPDATE planitlo_locations.config SET value = '".$value."' WHERE type = 'credits_staging'";
			if($db->query($sql)){
				header("Location: credits-mangement.php?success=1");
			}else{
				header("Location: credits-mangement.php?success=0");
			}
		}
	}

	$sql = "SELECT * FROM planitlo_locations.config WHERE type = 'credits_staging' AND active = '1' ORDER BY id DESC LIMIT 1";
	if($res = $db->query($sql)){
		$row = mysql_fetch_array($res);
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CRM</title>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="css/technocss.css" rel="stylesheet" />	
<link type="text/css" href="css/font-awesome.css" rel="stylesheet" />

<script src="../tinymce/jscripts/tiny_mce/tiny_mce.js" type="text/javascript"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		plugins : "table",
		theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,fontselect,fontsizeselect,formatselect",
		theme_advanced_buttons2 : "cut,copy,paste,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,image,|,code,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "insertdate,inserttime,|,spellchecker,advhr,,removeformat,|,sub,sup,|,charmap,emotions,tablecontrols",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true
	});
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
	
		<div class="popup2">
		<span class="tagTtl">Manage Credits</span><p>View credits page : <a href="https://planitlocations.com/credits" target="_blank">https://planitlocations.com/credits</a></p><br>
		

		<form action="" method="post">
			<div class="formBX">
				
					<textarea name="txtValue" cols="110" rows="25"><?php echo stripslashes(html_entity_decode($row['value'])); ?></textarea>
					<br />
				
			</div>
			<div class="buttonset">
				<input type="submit" value="Update" name="btnUpdate" class="new-Btn button is_enabled addcatAdd" />
				<div class="clear"></div>
			</div>
		</form>
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


</body>
</html>
