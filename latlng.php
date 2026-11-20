<?php
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
<link type="text/css" href="css/technocss.css" rel="stylesheet" />	
<link rel="stylesheet" href="simple_color_picker_master/css/colorPicker.css" type="text/css" />
</head>
<body>
<? include("header.php"); ?>
<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
        <td colspan="4" align="left" valign="top" nowrap="nowrap">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
                <tbody>
                <tr>
                    <td width="100%" align="left" valign="top" class="lastview"><b style="white-space: nowrap;">Property Report </b> </td>
                </tr>
                <tr>
                    <td align="left" valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                                <td align="left" valign="top" class="mar">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td align="left" valign="top" class="moduleTitle">Property Report</td>
                                                <td align="left" valign="top">&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br/>
                                    <?php
                                        $query = mysql_query("SELECT COUNT(*) FROM property") or die(mysql_error());
                                        $rs = mysql_fetch_array($query);

                                        $query1 = mysql_query("SELECT COUNT(*) FROM property WHERE lat IS NOT NULL AND lng IS NOT NULL") or die(mysql_error());
                                        $rs1 = mysql_fetch_array($query1);

                                        $query2 = mysql_query("SELECT COUNT(*) FROM property WHERE lat IS NULL AND lng IS NULL") or die(mysql_error());
                                        $rs2 = mysql_fetch_array($query2);
                                        
                                    ?>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td style="padding-bottom: 10px;">
                                                    Total Properties: <?php echo $rs[0] ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 10px;">
                                                    Properties having geo location: <?php echo $rs1[0] ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 10px;">
                                                    Properties doesn't have geo location: <?php echo $rs2[0] ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
  </tbody>
</table>

<? include("footer.php");?>

</body>
</html>