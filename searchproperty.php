<? 
	include('include/security.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CRM</title>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
<script>
<!--
function addpid_div(){
	var divval = document.getElementById('pidlist').innerHTML;
	coma = "'";
	newpidivval = '<table width="300" border="0" cellpadding="5" cellspacing="0" class="listViewThS1"><tr><td width="33%" align="right" class="bodytext">Property ID </td><td width="67%"><input name="pid[]" type="text" class="form" id="pid[]" onkeyup="ajax_showOptions(this,'+coma+'id'+coma+',event);"/></td></tr></table>';
	document.getElementById('pidlist').innerHTML = divval + newpidivval;	
}

//-->
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
            <td width="100%" align="left" valign="top" class="lastview">Search Criteria </td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td height="400" align="left" valign="top" class="mar"><form id="form1" name="form1" method="post" action="searchpropertyresults.php">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td colspan="2" align="left" valign="top"><div id="pidlist">
                          <table width="300" border="0" cellpadding="5" cellspacing="0" class="listViewThS1">
                            <tr>
                              <td width="33%" align="right" class="bodytext">Property ID </td>
                              <td width="67%"><input name="pid[]" type="text" class="form" id="pid[]" onkeyup="ajax_showOptions(this,'id',event);"/></td>
                            </tr>
                          </table>
                        </div></td>
                        </tr>
                      <tr>
                        <td colspan="2" align="center" valign="top" height="8"></td>
                        </tr>
                      <tr>
                        <td width="25%" align="right" valign="top"><input name="Submit2" type="submit" class="button" value="Search" /></td>
                        <td width="75%" align="left" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="Submit22" type="button" class="button" value="Add more" onclick="addpid_div();"/></td>
                      </tr>
                    </table>
                  </form>
                  </td>
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
