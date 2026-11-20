<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
	$notes = '';
	$property_id = $_REQUEST['property_id'];
	$type = $_REQUEST['type'];
	$op = $_REQUEST['op'];
	if($_REQUEST['txtnotes'] == '') { $_REQUEST['txtnotes'] = '0'; } 
	if(isset($_REQUEST['id']))
		$id = $_REQUEST['id'];
	if(isset($_REQUEST['hdnnotes']) && $_REQUEST['hdnnotes'] == 1){
		if($_REQUEST['op'] == 1){
			$sql = "INSERT INTO notes (property_id,type,text,userid) VALUES('".$property_id."','".$type."','".addslashes($_REQUEST['txtnotes'])."',
						'".$_SESSION['userid']."')";
			$db->query($sql) or die($sql);
		}elseif($_REQUEST['op'] == 2){
			$sql = "UPDATE notes SET text='".addslashes($_REQUEST['txtnotes'])."',userid=".$_SESSION['userid']." WHERE id=".$_REQUEST['id'];
			if(!$db->query($sql)) echo mysql_error();
		}
		?>
		<script src="js/prototype.js"></script>
		<script>
		url = 'selectnotes.php?type=<?=$type?>&property_id=<?=$property_id?>';
		new Ajax.Request(url, {
		method: 'get',
		onSuccess: function(transport) {
		//	alert(url);
		//	alert(transport.responseText);
			if(window.opener.document.getElementById("notes<?=$type?>")){
				window.opener.document.getElementById("notes<?=$type?>").innerHTML=transport.responseText;
			}
			window.close();
			
		},onFailure: function(){ alert('Error while performing AJAX request.') }
    	});
		</script>
		<?
	}
	if($op == 2){
		$notes = $db->getField('notes','text','id='.$id);
	}
?>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css">
<script>
function verify(){
	if(document.getElementById('txtnotes').value == ""){
		alert('Please Enter Notes');
		document.getElementById('txtnotes').focus();
		return false;
	}
	return true;
}
</script>
<title>CRM Notes</title>
<form name="form1" method="post" action="" onSubmit="return verify();">
  <table width="100%" border="0" cellspacing="0" cellpadding="4">
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center" class="bodytext">Notes:</td>
      <td><textarea name="txtnotes" cols="45" rows="6" class="txtarea" id="txtnotes"><? echo $notes; ?></textarea></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="Submit" type="submit" class="button" value="Save">
      <input name="cancel" type="button" class="button" value="Cancel" onClick="window.close();">
      <input type="hidden" name="property_id" value="<?=$property_id?>">
      <input type="hidden" name="type" value="<?=$type?>">
	  <input type="hidden" name="op" value="<?=$op?>">
	  <input type="hidden" name="id" value="<?=$id?>">
      <input name="hdnnotes" type="hidden" id="hdnnotes" value="1"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
