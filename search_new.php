<? 
	include('include/security.php');
	include('include/config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CRM</title>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="js/ajax-dynamic-list.js"></script>

	<style type="text/css">
	
	/* START CSS NEEDED ONLY IN DEMO */
	html{
		height:100%;
	}
	
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:125px;	/* Width of box */
		height:250px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #94C1E8;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:0.9em;
		z-index:100;
	}
	#ajax_listOfOptions div{	/* General rule for both .optionDiv and .optionDivSelected */
		margin:1px;		
		padding:1px;
		cursor:pointer;
		font-size:0.9em;
	}
	#ajax_listOfOptions .optionDiv{	/* Div for each item in list */
		
	}
	#ajax_listOfOptions .optionDivSelected{ /* Selected item in the list */
		background-color:#94C1E8;
		color:#FFF;
	}
	#ajax_listOfOptions_iframe{
		background-color:#F00;
		position:absolute;
		z-index:5;
	}
	
	form{
		display:inline;
	}
	
	</style>
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
                  <td height="400" align="left" valign="top" class="mar"><form id="form1" name="form1" method="post" action="searchresults.php">
                      <table width="100%" border="0" cellspacing="0" cellpadding="1">
                        <tr>
                          <td align="left" valign="top" class="moduleTitle">Search </td>
                          <td align="right" valign="top">&nbsp;</td>
                        </tr>
                      </table>
                      <table width="100%" border="0" align="left" cellpadding="2" cellspacing="0">
                        <tr>
                          <td width="18%" align="right" valign="top" class="formtxt">Property ID: </td>
                          <td align="left" valign="top"><input name="pid" type="text" class="form" id="pid" onkeyup="ajax_showOptions(this,'id',event);"/>
                          </div></td>
                          </tr>
                        <tr>
                          <td align="right" valign="top" class="formtxt">Property Name: </td>
                          <td colspan="3" align="left" valign="top" class="formsmalltext"><input name="pname" type="text" class="form" id="pname" onkeyup="ajax_showOptions(this,'name',event);"/></td>
                        </tr>

                        <tr>
                          <td valign="top">&nbsp;</td>
                          </tr>
                        <tr>
                          <td align="left" valign="top">&nbsp;</td>
                          <td align="left" valign="top"><input name="Submit" type="submit" class="button" value="Search" />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="advancesearch.php" class="utilsLink">Advance Search</a> </td>
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
