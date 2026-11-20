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
      <td width="100%" align="left" valign="top" class="moduleTitle">&nbsp;<b style="white-space: nowrap;">Search Property ID's </b> </td>
    </tr>
    <tr>
      <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="top" class="mar"><form id="form1" name="form1" method="post" action="searchpropertyresults.php">
            <table width="300" border="0" cellpadding="5" cellspacing="0" class="listViewThS1">
              <tr>
                <td width="33%" align="right" class="bodytext">Property ID1 </td>
                <td width="67%"><input name="pid1" type="text" class="form" id="pid1" /></td>
              </tr>
              <tr>
                <td align="right" class="bodytext">Property ID2</td>
                <td><input name="pid2" type="text" class="form" id="pid2" /></td>
              </tr>
              <tr>
                <td align="right" class="bodytext">Property ID3</td>
                <td><input name="pid3" type="text" class="form" id="pid3" /></td>
              </tr>
              <tr>
                <td align="right" class="bodytext">Property ID4</td>
                <td><input name="pid4" type="text" class="form" id="pid4" /></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input name="Submit" type="submit" class="button" value="Search" /></td>
              </tr>
            </table>
                    </form>
          </td>
        </tr>
      </table></td>
    </tr>
  </tbody>
</table>

