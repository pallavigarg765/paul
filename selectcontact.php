<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
	
	$nameorder = 'ASC';
	$namearrow = 'arrow';
	$titleorder = 'ASC';
	$titlearrow = 'arrow';
	$spouseorder = 'ASC';
	$spousearrow = 'arrow';
	
	//Insert Selected contact list to the parent contact dropdown list
	if($_REQUEST['hdnaddcontact'] == 1){
		 $_SESSION['addcontacts'] = $_REQUEST['allcontacts'];
		 $allcontacts = "";
		 $inner_html = '<select name="txtcontactdropdown" size="'.count($_SESSION['addcontacts']).'" class="bodttxt" id="txtcontactdropdown">';
         if(isset($_SESSION['addcontacts'])){
			foreach($_SESSION['addcontacts'] as $contact_add_id){
				$contact_qry = "SELECT firstname,lastname FROM contact WHERE id=".$contact_add_id;
				$contact_res = $db->query($contact_qry) or die($contact_qry);
				$contact_row = $db->fetchArray($contact_res);
				$inner_html .= '<option value="'.$contact_add_id.'">'.$contact_row['firstname'].' '.$contact_row['lastname'].'</option>';
				$allcontacts .= '<input name="txtcontacts[]" type="hidden" value="'.$contact_add_id.'" />';
			}
		 }else{
		 		$inner_html .= '<option value="">Select Contacts</option>';
			}
         $inner_html .= '</select>';
		 $inner_html = "'".$inner_html.$allcontacts."'";
		 echo '<script>window.opener.document.getElementById("showcontacts").innerHTML='.$inner_html.';window.close();</script>';
	}
	// Add Contact to DB
	if(isset($_REQUEST['hdncontact']) && $_REQUEST['hdncontact'] == '1'){
		$create_date = strtotime(date("Y-m-d"));
		$sql = "INSERT INTO contact (userid,firstname,lastname,title,spousename,email,website,relationship,notes,create_date) VALUES(
		'".$_SESSION['userid']."','".$_REQUEST['txtfname']."','".$_REQUEST['txtlname']."','".$_REQUEST['txttitle']."','".$_REQUEST['txtspousename']."','".$_REQUEST['txtemail']."',
		'".$_REQUEST['txtwebsite']."','".$_REQUEST['txtrelation']."','".$_REQUEST['txtnotes']."','".$create_date."')";
		$db->query($sql) or die($sql);		
		$contactid = mysql_insert_id();
		$phone = $_REQUEST['txtphone'];
		foreach($phone as $key => $value){
			if($value != ''){
				$sql = "INSERT INTO phone (contact_id,number,ext,type,priority) VALUES(
				'".$contactid."','".$value."','".$_REQUEST['txtext'][$key]."','".$_REQUEST['txttype'][$key]."','".$_REQUEST['txtpriority'][$key]."')";
				$db->query($sql);
			}		
		}
		$inner_html = '<select name="txtcontactdropdown" size="1" class="bodttxt" id="txtcontactdropdown">';
        $inner_html .= '<option value="'.$contactid.'">'.$_REQUEST['txtfname'].' '.$_REQUEST['txtlname'].'</option>';
		$allcontacts .= '<input name="txtcontacts[]" type="hidden" value="'.$contactid.'" />'; 
		$inner_html .= '</select>';
		$inner_html = "'".$inner_html.$allcontacts."'";
		echo '<script>window.opener.document.getElementById("showcontacts").innerHTML='.$inner_html.';window.close();</script>';
	}
	
	//Select Contact information
	$sql_list_query = "SELECT * FROM contact WHERE active = 1";
	if(isset($_REQUEST['orderby']) && isset($_REQUEST['column'])){
		$sql_list_query .= " ORDER BY ".$_REQUEST['column']." ".$_REQUEST['orderby'];
		if($_REQUEST['column'] == 'firstname'){
			if($_REQUEST['orderby'] == 'ASC'){
				$nameorder = 'DESC';
				$namearrow = 'arrow_down';
			}elseif($_REQUEST['orderby'] == 'DESC'){
				$nameorder = 'ASC';
				$namearrow = 'arrow_up';
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
	}else{
		$sql_list_query .= " ORDER BY datentime desc ";
	}
	
	$go = 1;
	$perpage = 5;
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
	$current_page = 'selectcontact.php';
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
<title>CRM - Contacts</title>
<script>
function selectAll(){
	var checkeditems = document.getElementsByTagName('INPUT');
	if(document.getElementById('massall').checked == true){
			for( i=0; i < checkeditems.length; i++){
				if(checkeditems[i].type == 'checkbox' && checkeditems[i].name.substr(0,9) == 'selectdel')
					checkeditems[i].checked = true;
			}
	}else{
		for( i=0; i < checkeditems.length; i++){
				if(checkeditems[i].type == 'checkbox' && checkeditems[i].name.substr(0,9) == 'selectdel')
					checkeditems[i].checked = false;
			}
	}
}
</script>
<script>
function addPhonetypes(){ 
	d_html = document.getElementById("phonetype").innerHTML;
	main_html = document.getElementById("phone").innerHTML;
	document.getElementById("phone").innerHTML = main_html + d_html;
}
function verifcreatecontact(){
	if(document.getElementById('txtfname').value == ""){
		alert('Please Enter First Name');
		document.getElementById('txtfname').focus();
		return false;
	}
	if(document.getElementById('txtlname').value == ""){
		alert('Please Enter Last Name');
		document.getElementById('txtlname').focus();
		return false;
	}
	return true;
}
</script>

<link href="css/crmstyle.css" rel="stylesheet" type="text/css">
<table width="750" border="0" align="center" cellpadding="2" cellspacing="0">

  <tr>
    <td colspan="4" align="left" valign="top" nowrap="nowrap"><form id="form1" name="form1" method="post" action="" onsubmit="return verifcreatecontact();">
        <table width="750" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td width="9%" align="right" valign="top"><span class="formtxt">First Name:</span></td>
            <td width="38%" align="left" valign="top"><input name="txtfname" id="txtfname" type="text" class="formcontact" size="35" maxlength="255" /></td>
            <td width="29%" align="right" valign="top"><span class="formtxt">Last Name:</span></td>
            <td width="24%" align="left" valign="top"><input name="txtlname" id="txtlname" type="text" class="formcontact" size="35" maxlength="255" /></td>
          </tr>
          <tr>
            <td align="right" valign="top"><span class="formtxt">Title:</span></td>
            <td align="left" valign="top"><input name="txttitle" id="txttitle" type="text" class="formcontact" value="" size="35" maxlength="255"/></td>
            <td align="right" valign="top"><span class="formtxt">Spouse Name: </span></td>
            <td align="left" valign="top"><input name="txtspousename" type="text" class="formcontact" size="35" maxlength="255" iid="txtspousename" ize="35" /></td>
          </tr>
          <tr>
            <td align="right" valign="top"><span class="formtxt">Email:</span></td>
            <td align="left" valign="top"><input name="txtemail" type="text" class="formcontact" id="txtemail" size="35" maxlength="255" /></td>
            <td align="right" valign="top"><span class="formtxt">Website:</span></td>
            <td align="left" valign="top"><input name="txtwebsite" id="txtwebsite" type="text" class="formcontact" size="35" maxlength="255" /></td>
          </tr>
          <tr>
            <td align="right" valign="top"><span class="formtxt">Relationship:</span></td>
            <td align="left" valign="top" class="formtxt"><?=listbox_array($relationshipArray,'txtrelation')?></td>
            <td align="right" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td align="right" valign="top"><span class="formtxt">Notes:</span></td>
            <td colspan="3" align="left" valign="top"><textarea name="txtnotes" id="txtnotes" cols="80" rows="4" class="txtarea"></textarea></td>
          </tr>
          <tr>
            <td colspan="4" align="left" valign="top"><div id="phone">
              <div id="phonetype">
                <table width="99%" border="0" cellpadding="0" cellspacing="0">
                  <tr valign="middle">
                    <td width="33%" height="25" align="left" class="formtxt">Phone 1:</td>
                    <td width="29%" height="25" align="left" class="formtxt">Ext 1:</td>
                    <td width="20%" height="25" align="left" class="formtxt">Type:</td>
                    <td width="18%" height="25" align="left" class="formtxt">Priority :</td>
                  </tr>
                  <tr>
                    <td align="left" valign="top"><input name="txtphone[]" type="text" class="formcontact" id="txtphone[]" size="35" maxlength="50" /></td>
                    <td align="left" valign="top"><input name="txtext[]" type="text" class="formcontact" id="txtext[]" size="20" maxlength="10" /></td>
                    <td align="left" valign="top"><?=fillCombo('txttype[]','types','id','name','WHERE active=1','','dropdown','')?></td>
                    <td align="left" valign="top"><?=listbox_array($priorityArray,'txtpriority[]')?>                    </td>
                  </tr>
                </table>
              </div>
            </div></td>
          </tr>
          <tr>
            <td height="35" align="left" valign="top">&nbsp;</td>
            <td height="35" align="left" valign="top">&nbsp;</td>
            <td height="35" align="right" valign="middle"><input name="Button" type="button" class="button" value="Add More" onclick="addPhonetypes();"/></td>
            <td align="right" valign="middle">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" align="left" valign="top"><table width="60%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="7%" align="left" valign="top">&nbsp;</td>
                <td width="36%" align="left" valign="top"><span class="dataField"><span class="dataLabel" style="display: inline;">
                  <input name="hdncontact" type="hidden" value="1" />
                  <input class="button" value="Create Contact" name="createContact" type="submit" />
                </span></span></td>
                <td width="50%" align="left" valign="top">&nbsp;</td>
                <td width="7%" align="left" valign="top">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
    </form></td>
  </tr>
</table>
<form name="form2" method="post" action="">
  <table width="750" border="0" align="center" cellpadding="2" cellspacing="0">
  
    <tr>
      <td align="left" valign="top"><table width="725" border="0" cellpadding="0" cellspacing="0" class="listview">
          <tr>
            <td width="2%" height="18" align="left" valign="top">&nbsp;</td>
            <td colspan="2" align="left" valign="top" class="moduleTitle">Select Contacts </td>
            <td width="20%" align="left" valign="top">&nbsp;</td>
            <td width="19%" align="left" valign="top">&nbsp;</td>
            <td colspan="3" align="right" valign="middle"><span style="padding-right:10px;"><?=$paging_var?></span></span></td>
          </tr>
          <tr bgcolor="#D0E1F4" height="20">
            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="100%" align="left"> <a href='selectcontact.php?orderby=<?=$nameorder?>&column=firstname' class="a">Name&nbsp;&nbsp; <img src="images/<?=$namearrow?>.gif" alt="" align="absmiddle" border="0" height="10" width="8" /> </a> </div></td>
            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="100%" align="left"> <a href='selectcontact.php?orderby=<?=$titleorder?>&column=title' class="a">Title&nbsp;&nbsp;<img src="images/<?=$titlearrow?>.gif" alt="" align="absmiddle" border="0" height="10" width="8" /></a> </div></td>
            <td nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="140px" align="left"> <a href='selectcontact.php?orderby=<?=$spouseorder?>&column=spousename' class="a">Spouse's Name&nbsp;<img src="images/<?=$spousearrow?>.gif" alt="" align="absmiddle" border="0" height="10" width="8" /></a> </div></td>
            <td nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="135px" align="left"> Email </div></td>
            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="100%" align="left"> Website&nbsp;&nbsp;</div></td>
          </tr>
       	<? while($lrow = $db->fetchArray($result)){?>
			<tr class="grid" height="20">
              <td height="20" nowrap="nowrap" class="listViewThS1" scope="col"><input name="allcontacts[]" type="checkbox" id="allcontacts[]" value="<?=$lrow['id']?>"></td>
              <td nowrap="nowrap" scope="col" class="n"><div style="white-space: nowrap;" width="100%" align="left"> <?=$lrow['firstname']?> <?=$lrow['lastname']?></div></td>
              <td height="20" nowrap="nowrap" scope="col" class="n"> <?=$lrow['title']?> </td>
              <td height="20" nowrap="nowrap" scope="col" class="n"> <?=$lrow['spousename']?> </td>
              <td height="20" nowrap="nowrap" scope="col" class="n"> <?=$lrow['email']?> </td>
              <td height="20" nowrap="nowrap" scope="col" class="n"> <?=$lrow['website']?> </td>
            </tr>
			<? }?>
        </table>
    <tr>
      <td id='listViewPaginationButtons' class='listViewPaginationTdS1'><input name="hdnaddcontact" type="hidden" value="1"><input name="Submit" type="submit" class="button" value="Add Contacts"></td>
    </tr>
  </table>
</form>
