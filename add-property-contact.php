<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
?>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="100%" align="left" valign="top" class="moduleTitle">&nbsp;<b style="white-space: nowrap;">Create Contact </b> </td>
    </tr>
    <tr>
      <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="top" class="mar"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td colspan="4" align="left" valign="top" nowrap="nowrap"><form id="form1" name="form1" method="post" action="" onSubmit="return verifcreatecontact();">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="31%" align="left" valign="top"><span class="formtxt">First Name:</span></td>
                      <td width="21%" align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top"><span class="formtxt">Last Name:</span></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top"><input tabindex="1" name="txtfname" id="txtfname" type="text" class="form" size="35" maxlength="255" /></td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top"><input tabindex="5" name="txtlname" id="txtlname" type="text" class="form" size="35" maxlength="255" /></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top"><span class="formtxt">Title:</span></td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top"><span class="formtxt">Spouse Name: </span></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top"><input tabindex="2" name="txttitle" id="txttitle" type="text" class="form" size="35" maxlength="255"/></td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top"><input tabindex="6" name="txtspousename" type="text" class="form" iid="txtspousename" ize="35" maxlength="255" /></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top"><span class="formtxt">Email:</span></td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top"><span class="formtxt">Website:</span></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top"><input tabindex="3" name="txtemail" type="text" class="form" id="txtemail" size="35" maxlength="255" /></td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top"><input tabindex="7" name="txtwebsite" id="txtwebsite" type="text" class="form" size="35" maxlength="255" /></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top"><span class="formtxt">Relationship:</span></td>
                      <td align="left" valign="top" class="formtxt"></td>
                      <td align="left" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="left" valign="top" tabindex="4"><?=listbox_array($relationshipArray,'txtrelation')?></td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="left" valign="top"><span class="formtxt">Note:</span></td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2" align="left" valign="top"><textarea tabindex="8" name="txtnotes" id="txtnotes" cols="80" rows="4" class="txtarea"></textarea>
                      </td>
                      <td align="left" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="3" align="left" valign="top">
					   <div id="phone">
                        <div id="phonetype">
                          <table width="99%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td width="33%" align="left" valign="top" class="formtxt">Phone: (format: 1234567890)</td>
                              <td width="29%" align="left" valign="top" class="formtxt">Ext:</td>
                              <td width="20%" align="left" valign="top" class="formtxt">Type:</td>
                              <td width="18%" align="left" valign="top" class="formtxt">Priority:</td>
                            </tr>
                            <tr>
                              <td align="left" valign="top"><input tabindex="10" name="txtphone[]" type="text" class="form phone" id="txtphone[]" size="35" maxlength="50" /></td>
                              <td align="left" valign="top"><input tabindex="11" name="txtext[]" type="text" class="form" id="txtext[]" size="20" maxlength="10" /></td>
                              <td align="left" valign="top" tabindex="12"><?=fillCombo('txttype[]','types','id','name','WHERE active=1 AND (id = 1 OR id = 12 OR id = 23)','','dropdown','')?></td>
                              <td align="left" valign="top" tabindex="13"><?=listbox_array($priorityArray,'txtpriority[]')?>
                              </td>
                            </tr>
                          </table>
                        </div>
						<div id="phonetype1"></div>
                      </div></td>
                    </tr>
                    <tr>
                      <td height="35" align="left" valign="top">&nbsp;</td>
                      <td height="35" align="left" valign="top">&nbsp;</td>
                      <td height="35" align="right" valign="middle">&nbsp;
						<input name="Button" type="button" class="button" value="Add More" onClick="addPhonetypes();"/>
					  </td>
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
                            <input name="hdnaddcontact" id="hdnaddcontact" type="hidden" value="1" />
                            <input class="button" tabindex="14" value="Add Contact" name="createContact" type="submit" />
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

