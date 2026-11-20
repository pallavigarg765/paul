<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	$db = new DBConn();
	$contactid = (int)$_REQUEST['c'];	
?>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
<table width="620" border="0" cellpadding="0" cellspacing="0">
  
  <tr>
    <td width="100%" align="left" valign="top" class="moduleTitle">&nbsp;<b style="white-space: nowrap;">Contact Information&nbsp;&nbsp;</b></td>
  </tr>
  <tr>
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="top" class="mar"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <? 
										  	$sqlcontact = "SELECT contact.* FROM contact WHERE contact.id=".$contactid;
											$cresult = $db->query($sqlcontact) or die($sqlcontact);
											while($crow = $db->fetchArray($cresult)){
										  ?>
                            <tr>
                              <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td width="20%" align="left" valign="top" class="bodytext">First Name:</td>
                                    <td width="16%" align="left" valign="top" class="formtxt"><?php	                                       					                                       			 if($crow['firstname'] != '0') echo $crow['firstname']; ?></td>
                                    <td width="16%" align="left" valign="top" class="bodytext">Last Name:</td>
                                    <td width="19%" align="left" valign="top" class="formtxt"><?php	                                       					                                       			 if($crow['lastname'] != '0') echo $crow['lastname']; ?></td>
                                    <td width="13%" align="left" valign="top" class="bodytext">Title:</td>
                                    <td width="16%" align="left" valign="top" class="formtxt"><?php	                                       					                                       			 if($crow['title'] != '0') echo $crow['title']; ?></td>
                                  </tr>
                                  <tr class="formtxt">
                                    <td align="left" valign="top" class="bodytext">Spouse Name:</td>
                                    <td align="left" valign="top" class="formtxt"><?php	                                       					                                       			 if($crow['spousename'] !='0') echo $crow['spousename']; ?></td>
                                    <td align="left" valign="top" class="bodytext">Email:</td>
                                    <td align="left" valign="top" class="formtxt"><?php	                                       					                                       			 if($crow['email'] != '0') echo $crow['email']; ?></td>
                                    <td align="left" valign="top" class="bodytext">Web Site:</td>
                                    <td align="left" valign="top" class="formtxt"><?php	                                       					                                       			 if($crow['website'] != '0') echo $crow['website']; ?></td>
                                  </tr>
                                  <tr>
                                    <td align="left" valign="top" class="bodytext">Relationship:</td>
                                    <td align="left" valign="top" class="formtxt"><?=$relationshipArray[$crow['relationship']]?></td>
                                    <td align="left" valign="top" class="bodytext">Notes:</td>
                                    <td colspan="3" align="left" valign="top" class="formtxt"><?php	                                       					                                       			 if($crow['notes'] !='0') echo $crow['notes']; ?></td>
                                  </tr>
                               </table></td>
                            </tr>
                            <tr>
                              <td align="center" valign="top"><? 
												$typesql = "SELECT phone.*, types.name as typename FROM phone left join types on phone.type=types.id 
															WHERE phone.contact_id=".$crow['id'];
												$tresult = $db->query($typesql) or die($typesql);
												if($db->getNumRows($tresult) >= 1){
											?>
                                  <table width="100%" border="0" cellpadding="1" cellspacing="0">
                                    <tr class="bodytext">
                                      <td width="33%" align="left" valign="top">Phone:</td>
                                      <td width="29%" align="left" valign="top">Ext:</td>
                                      <td width="20%" align="left" valign="top">Type:</td>
                                      <td width="18%" align="left" valign="top">Priority:</td>
                                    </tr>
                                    <? while($trow=$db->fetchArray($tresult)){?>
                                    <tr class="formtxt">
                                      <td align="left" valign="top"><?php	                                       					                                       			 if($trow['number'] != '0') echo $trow['number']; ?></td>
                                      <td align="left" valign="top"><?php	                                       					                                       			 if($trow['ext'] != '0') echo $trow['ext']; ?></td>
                                      <td align="left" valign="top"><?php	                                       					                                       			 if($trow['typename'] != '0') echo $trow['typename']; ?></td>
                                      <td align="left" valign="top"><?=$priorityArray[$trow['priority']]?></td>
                                    </tr>
                                    <? }?>
                                    </table>
                                <? }?></td>
                            </tr>
                            <? }?>
                          </table></td>
                      </tr>

                    </table></td>
                </tr>
              </table></td>
        </tr>
    </table></td>
  </tr>
</table>
