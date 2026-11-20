<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/functions.php');
	$db = new DBConn();

  $loop_array = array();
  
  $result_a = array('lable'=>'Category A');
  $result_a['data'] = $db->query("SELECT category1,
  COUNT(*) AS total_prop from planitlo_locations.sorting WHERE category1 NOT LIKE '%-z' AND category1 NOT LIKE '%-prv' AND category1 NOT LIKE '%-b' AND category1 NOT LIKE '%-c' AND category1 NOT LIKE '%-d' GROUP BY category1 ORDER BY category1 ASC") or die(mysql_error());
  
  $loop_array[] = $result_a;

  $result_b = array('lable'=>'Category B');

  $result_b['data'] = $db->query("SELECT category1,COUNT(*) AS total_prop from planitlo_locations.sorting WHERE category1 LIKE '%-b' GROUP BY category1 ORDER BY category1 ASC") or die(mysql_error());
  $loop_array[] = $result_b;

  $result_c = array('lable'=>'Category C');
  $result_c['data'] = $db->query("SELECT category1,COUNT(*) AS total_prop from planitlo_locations.sorting WHERE category1 LIKE '%-c' GROUP BY category1 ORDER BY category1 ASC") or die(mysql_error());
  $loop_array[] = $result_c;

  $result_d = array('lable'=>'Category D');
  $result_d['data'] = $db->query("SELECT category1,COUNT(*) AS total_prop from planitlo_locations.sorting WHERE category1 LIKE '%-d' GROUP BY category1 ORDER BY category1 ASC") or die(mysql_error());
  $loop_array[] = $result_d;

  $result_z = array('lable'=>'Category Z');
  $result_z['data'] = $db->query("SELECT category1,COUNT(*) AS total_prop from planitlo_locations.sorting WHERE category1 LIKE '%-z' GROUP BY category1 ORDER BY category1 ASC") or die(mysql_error());
  $loop_array[] = $result_z;

  $result_prv = array('lable'=>'Category PRV');
  $result_prv['data'] = $db->query("SELECT category1,COUNT(*) AS total_prop from planitlo_locations.sorting WHERE category1 LIKE '%-prv' GROUP BY category1 ORDER BY category1 ASC") or die(mysql_error());
  $loop_array[] = $result_prv;
 
  


	$current_page = 'property_category.php';
	$criteria = "";
	//$sql_list_query = "SELECT DISTINCT(T.tag_name),T.tag_id,T.color_code,C.category FROM `tags` AS T INNER JOIN `tag_categories` AS C ON C.id=T.category_id  ORDER BY tag_name ASC";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CRM</title>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="css/technocss.css" rel="stylesheet" />	
<link rel="stylesheet" href="simple_color_picker_master/css/colorPicker.css" type="text/css" />
<style type="text/css">
  .listview1
  {
    margin: 3px;
  }
  .listViewThS6
  {
    padding: 0 8px;
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
            <td width="100%" align="left" valign="top" class="lastview"><b style="white-space: nowrap;">All Categories  &nbsp;&nbsp;</b> </td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td align="left" valign="top" class="mar">

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top" class="moduleTitle">All Categories </td>
                      
                    </tr>
                  </table>
                  <?php foreach($loop_array as $key => $value) { ?>
                    <table width="15%" border="0" align="center" cellpadding="0" cellspacing="0" style="float: left;" class="listview listview1">
                      <thead>
                        <tr bgcolor="#D0E1F4" height="20">
                          <th style="padding: 5px">
                            
                              <font face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular">
                                <b><?php echo $value['lable']; ?></b>
                              </font>
                            
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php if(mysql_num_rows($value['data']) > 0){
                                while ($row = mysql_fetch_assoc($value['data'])) {
                              ?>
                              <tr>
                                <td height="25" align="left" nowrap="nowrap" class="listViewThS1 listViewThS6 n" scope="col">
                                  
                                    
                                      <a target="_blank" href="../search/search-and-sortView.php?cat=<?php echo $row['category1']; ?>"><b><?php echo $row['category1']; ?></b> </a>(<?php echo $row['total_prop']; ?>)
                                    
                                  </u>
                                </td>
                              </tr>
                              <?php
                              } 
                            }
                            else
                            {
                              ?>
                              <tr>
                                <td height="25" align="left" nowrap="nowrap" class="listViewThS1 listViewThS6" scope="col">
                                  
                                  <font face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular" style="font-size: 13px;color: red;">
                                    No Records Found
                                  </font>
                                  
                                </td>
                              </tr>
                              <?php
                            }
                          ?>
                      </tbody>
                    </table>
                   <?php } ?>
                  </td>
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
<!-- --------- END  MANAGE TAGS------- !-->
<? include("footer.php");?>
<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery('#filter_result').change(function(){
        window.location.href=jQuery(this).val();
    });
  });
</script>
</body>
</html>
