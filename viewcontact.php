<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
	$contactid = (int)$_REQUEST['c'];	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CRM</title>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
</head>

<body>
<? include("header.php"); ?>
<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="4" align="left" valign="top" nowrap="nowrap"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
        <tbody>
          <tr>
            <td width="100%" align="left" valign="top" class="lastview"><b style="white-space: nowrap;">Property Listing &nbsp;&nbsp;</b></td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td align="left" valign="top" class="mar"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="left" valign="top" class="moduleTitle">Contact Information </td>
                        <td align="left" valign="top">&nbsp;</td>
                      </tr>
                    </table>
                      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="bg_borbot">
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
                                            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                                <tr class="bodytext">
                                                  <td width="33%" align="left" valign="top">First Name:</td>
                                                  <td width="29%" align="left" valign="top">Last Name:</td>
                                                  <td width="38%" align="left" valign="top">Title:</td>
                                                </tr>
                                                <tr class="formtxt">
                                                  <td align="left" valign="top"><?php	                                       					                                       			 if($crow['firstname'] != '0') echo $crow['firstname']; ?></td>
                                                  <td align="left" valign="top"><?php	                                       					                                       			 if($crow['lastname'] != '0') echo $crow['lastname']; ?></td>
                                                  <td align="left" valign="top"><?php	                                       					                                       			 if($crow['title'] != '0') echo $crow['title']; ?></td>
                                                </tr>
                                                <tr class="bodytext">
                                                  <td align="left" valign="top">Spouse Name:</td>
                                                  <td colspan="2" align="left" valign="top">Notes:</td>
                                              </tr>
                                                <tr class="formtxt">
                                                  <td align="left" valign="top"><?php	                                       					                                       			 if($crow['spousename'] != '0') echo $crow['spousename']; ?></td>
                                                  <td colspan="2" align="left" valign="top"><?php	                                       					                                       			 if($crow['notes'] != '0') echo $crow['notes']; ?></td>
                                                </tr>
                                                <tr class="bodytext">
                                                  <td align="left" valign="top">Email:</td>
                                                  <td align="left" valign="top">Web Site:</td>
                                                  <td align="left" valign="top">Relationship:</td>
                                                </tr>
                                                <tr class="formtxt">
                                                  <td align="left" valign="top"><?php	                                       					                                       			 if($crow['email'] != '0') echo $crow['email']; ?></td>
                                                  <td align="left" valign="top"><?php	                                       					                                       			 if($crow['website'] != '0') echo $crow['website']; ?></td>
                                                  <td align="left" valign="top"><?=$relationshipArray[$crow['relationship']]?></td>
                                                </tr>
                                            </table></td>
                                          </tr>
                                          <tr>
                                            <td align="center" valign="top">
											<? 
												$typesql = "SELECT phone.*, types.name as typename FROM phone left join types on phone.type=types.id 
															WHERE phone.contact_id=".$crow['id'];
												$tresult = $db->query($typesql) or die($typesql);
												if($db->getNumRows($tresult) >= 1){
											?>	
											<table width="99%" border="0" cellpadding="1" cellspacing="0">
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
                                                  <td align="left" valign="top"><?php	                                       					                                       			 if($priorityArray[$trow['priority']] != '0') echo $priorityArray[$trow['priority']]; ?></td>
                                                </tr>
												<? }?>
											</table>
											<? }?></td>
                                          </tr>
										  <? }?>
                                      </table></td>
                              </tr>
                              <tr>
                                <td height="40" align="left" valign="bottom"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td width="11%" align="left"><span class="dataField"><span class="dataLabel" style="display: inline;">
                                        <input title="Select [Alt+T]" accesskey="T" class="button" value="Back" name="btn12" language="javascript" onclick="history.back();" type="button"/>
                                      </span></span></td>
                                      <td width="89%" align="left" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td align="left" valign="top">&nbsp;</td>
                                          </tr>
                                      </table></td>
                                    </tr>
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
<? include("footer.php");?>
</body>
</html>



