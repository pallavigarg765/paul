<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
	$id = (int)$_REQUEST['id'];
	$sql = "SELECT * FROM contact WHERE id=".$id;
	$res = $db->query($sql) or die($sql);
	$crow = $db->fetchArray($res);
?>




<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="100%" align="left" valign="top" class="moduleTitle">&nbsp;<b style="white-space: nowrap;">Edit Contact </b> </td>
    </tr>
    <tr>
      <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="top" class="mar"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td colspan="4" align="left" valign="top" nowrap="nowrap"><form id="form1" name="form1" method="post" action="" onSubmit="return verifcreatecontact();">
                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td width="12%" height="22" align="right"><span class="formtxt">First Name:</span></td>
                      <td width="24%" height="22" align="left"><input tabindex="1" name="txtfname" id="txtfname" type="text" class="formcontact" size="35" maxlength="255" value="<?php if($crow['firstname'] != '0') echo $crow['firstname']; ?>"/></td>
                      <td width="23%" height="22" align="right"><span class="formtxt">Last Name:</span></td>
                      <td width="41%" height="22" align="left"><input tabindex="5" name="txtlname" id="txtlname" type="text" class="formcontact" size="35" maxlength="255" value="<?php	if($crow['lastname'] != '0') echo $crow['lastname']; ?>"/></td>
                    </tr>
                    <tr>
                      <td height="18" align="right"><span class="formtxt">Title:</span></td>
                      <td height="18" align="left"><input tabindex="2" name="txttitle" id="txttitle" type="text" class="formcontact" size="35" maxlength="255" value="<?php	                                       					                                       			 if($crow['title'] != '0') echo $crow['title']; ?>"/></td>
                      <td height="18" align="right"><span class="formtxt">Spouse Name: </span></td>
                      <td height="18" align="left"><input tabindex="6" name="txtspousename" type="text" class="formcontact" iid="txtspousename" ize="35" maxlength="255" value="<?php if($crow['spousename'] != '0') echo $crow['spousename']; ?>"/></td>
                    </tr>
                    <tr>
                      <td height="18" align="right"><span class="formtxt">Email:</span></td>
                      <td height="18" align="left"><input tabindex="3" name="txtemail" type="text" class="formcontact" id="txtemail" size="35" maxlength="255" value="<?php if($crow['email'] != '0') echo $crow['email']; ?>"/></td>
                      <td height="18" align="right"><span class="formtxt">Website:</span></td>
                      <td height="18" align="left"><input tabindex="7" name="txtwebsite" id="txtwebsite" type="text" class="formcontact" size="35" maxlength="255" value="<?php	if($crow['website'] != '0') echo $crow['website']; ?>"/></td>
                    </tr>
                    <tr>
                      <td height="18" align="right"><span class="formtxt">Relationship:</span></td>
                      <td height="18" align="left" valign="top" class="formtxt" tabindex="4"><?=listbox_array($relationshipArray,'txtrelation',$relationshipArray[$crow['relationship']])?></td>
                      <td height="18" align="left" valign="top">&nbsp;</td>
                      <td height="18" align="left" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                      <td height="18" align="right"><span class="formtxt">Note:</span></td>
                      <td colspan="3" align="left" valign="top"><textarea tabindex="8" name="txtnotes" id="txtnotes" cols="80" rows="4" class="txtarea"><? if($crow['notes'] != '0') echo $crow['notes']; ?></textarea></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="4" align="left" valign="top">
					   <div id="phone">
                        <div id="phonetype">
                          <table width="100%"  border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td width="33%" height="18" align="left" class="formtxt">Phone: (format: 1234567890)</td>
                              <td width="29%" height="18" align="left" class="formtxt">Ext:</td>
                              <td width="20%" height="18" align="left" class="formtxt">Type:</td>
                              <td width="18%" height="18" align="left" class="formtxt">Priority:</td>
                            </tr>
                            <?php
							$sql_query_phone = "SELECT * FROM phone WHERE contact_id=".$crow['id']." ORDER BY id";
							$pres = $db->query($sql_query_phone) or die($sql_query_phone);
							while($prow = $db->fetchArray($pres)){
							?>
                            <tr>
                              <td align="left" valign="top"><input tabindex="9" name="txtphone[]" type="text" class="formcontact phone" id="txtphone[]" size="35" maxlength="50" value="<?php if($prow['number'] != '0') echo $prow['number']; ?>"/></td>
                              <td align="left" valign="top"><input tabindex="10" name="txtext[]" type="text" class="formcontact" id="txtext[]" size="20" maxlength="10" value="<?php if($prow['ext'] != '0') echo $prow['ext']; ?>"/></td>
                              <td align="left" valign="top" tabindex="11">
									<?=fillCombo('txttype[]','types','id','name','WHERE active=1 AND (id = 1 OR id = 12 OR id = 23)',$prow['type'],'dropdown','')?>
							  </td>
                              <td align="left" valign="top" tabindex="12"><?=listbox_array($priorityArray,'txtpriority[]',$prow['priority'])?>
                              </td>
                            </tr>
                            <input name="phoneid[]" type="hidden" value="<?=$prow['id']?>" />
                            <? }?>
                          </table>
                        </div>
						
                      </div>
					 </td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="center" valign="top"><input tabindex="13" name="Button" type="button" class="button" value="Add More" onclick="editPhonetypes();"/></td>
                    </tr>
                    <tr>
                      <td colspan="4" align="left" valign="top"><table width="60%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="6%" align="left" valign="top">&nbsp;</td>
                          <td width="37%" align="left" valign="top"><span class="dataField"><span class="dataLabel" style="display: inline;">
                            <input name="cid" type="hidden" value="<?=$crow['id']?>" />
                            <input name="hdnpropertycontact" id="hdnpropertycontact" type="hidden" value="1" />
                            <input class="button" tabindex="14" value="Save Contact" name="createContact" type="submit" />
                          </span></span></td>
                          <td width="50%" align="left" valign="top">&nbsp;</td>
                          <td width="7%" align="left" valign="top">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                  </table>
                </form></td>
              </tr>
            </tbody>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </tbody>
</table>
