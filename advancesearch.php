<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CRM</title>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
<script>
	function add_div(){
		var divno = document.getElementById('divid').value;
		var totaldivno = document.getElementById('totaldivid').value;
		if(totaldivno < 11){
			divno ++;
			totaldivno ++;
			newdivval = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="7%" align="left">Filter '+divno+':</td><td width="19%" align="left"><select name="columns[]"><option value="">-- Select Field --</option><option value="pid">Property ID</option><option value="title">Contact Title</option><option value="name">Contact Name</option><option value="email">Email Address</option><option value="company">Company Name</option><option value="notes">Contact Notes</option><option value="county">County</option><option value="state">State</option>><option value="city">City</option><option value="zipcode">Zipcode</option></select></td><td width="18%" align="left"><select name="conditions[]"><option value="=">equals</option><option value="!=">does not equals</option><option value="like">contains</option><option value="<">less then</option><option value=">">greater then</option></select></td><td width="40%" align="left"><input name="searchkeywords[]" id="searchkeywords[]" type="text" class="form"  style="width:175px"/></td><td width="4%" align="left"><a href="#" onclick="add_div();"><img src="images/add.png" alt="Add New Filter" width="16" height="16" border="0"/></a></td><td width="12%" align="left"><a href="#" onclick="del_div('+divno+');"><img src="images/close_dashboard.gif" alt="Delete Filter" width="13" height="13" border="0"/></a></td></tr></table>';
			if(document.getElementById('filter'+divno)){
				document.getElementById('filter'+divno).innerHTML = newdivval;
				document.getElementById('filter'+divno).style.display = 'block';
			}
			document.getElementById('divid').value = divno;	
			document.getElementById('totaldivid').value = totaldivno;
		}
	}
	function del_div(de_divid){
		var totaldivno = document.getElementById('totaldivid').value;
		totaldivno --;
		document.getElementById('totaldivid').value = totaldivno;	
		document.getElementById('filter'+de_divid).innerHTML='';
		document.getElementById('filter'+de_divid).style.display='none';
	}
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
                  <td height="400" align="left" valign="top" class="mar"><form id="form1" name="form1" method="post" action="advancesearchresults.php">
                      <table width="100%" border="0" cellspacing="0" cellpadding="1">
                        <tr>
                          <td align="left" valign="top" class="moduleTitle">Advance Search </td>
                          <td align="right" valign="top">&nbsp;</td>
                        </tr>
                      </table>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td><table width="100%" border="0" align="left" cellpadding="2" cellspacing="0">
                            <tr>
                              <td colspan="3" align="right" valign="top" class="formtxt"><input name="divid" id="divid" type="hidden" value="1" /><input name="totaldivid" id="totaldivid" type="hidden" value="1" /><div id="searchfilter">
							  <div id="filter1">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td width="7%" align="left">Filter 1:</td>
                                          <td width="19%" align="left"><select name="columns[]">
                                              <option value="">-- Select Field --</option>
                                              <option value="pid" selected="selected">Property ID</option>
											  <option value="title">Contact Title</option>
											  <option value="name">Contact Name</option>
											  <option value="email">Email Address</option>
                                              <option value="company">Company Name</option>
											  <option value="notes">Contact Notes</option>
                                              <option value="county">County</option>
                                              <option value="state">State</option>
											  <option value="city">City</option>
											  <option value="zipcode">Zipcode</option>
                                          </select></td>
                                          <td width="18%" align="left"><select name="conditions[]">
                                              <option value="=">equals</option>
                                              <option value="!=">does not equals</option>
                                              <option value="like">contains</option>
                                              <option value="<">less then</option>
											  <option value=">">greater then</option>
                                          </select></td>
                                          <td width="40%" align="left"><input name="searchkeywords[]" id="searchkeywords[]" type="text" class="form"  style="width:175px"/></td>
                                          <td width="4%" align="left"><a href="#" onclick="add_div();"><img src="images/add.png" alt="Add New Filter" width="16" height="16" border="0"/></a></td>
                                          <td width="12%" align="left"><a href="#" onclick="del_div('1');"><img src="images/close_dashboard.gif" alt="Delete Filter" width="13" height="13" border="0"/></a></td>
                                        </tr>
                                      </table>
                                    </div>
									<div id="filter2" style="display:none"></div>
									<div id="filter3" style="display:none"></div>
									<div id="filter4" style="display:none"></div>
									<div id="filter5" style="display:none"></div>
									<div id="filter6" style="display:none"></div>	
									<div id="filter7" style="display:none"></div>
									<div id="filter8" style="display:none"></div>
									<div id="filter9" style="display:none"></div>
									<div id="filter10" style="display:none"></div>
									<div id="filter11" style="display:none"></div>
								</div></td>
                            </tr>
                            <tr>
                              <td width="16%" valign="top">&nbsp;</td>
                              <td width="42%" valign="top">&nbsp;</td>
                              <td width="42%" valign="top">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="left" valign="top">&nbsp;</td>
                              <td colspan="2" align="left" valign="top"><input name="Submit2" type="submit" class="button" value="Search" />
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
                            </tr>
                          </table></td>
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
