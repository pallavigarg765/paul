<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/functions.php');
	$db = new DBConn();
	function show_address($address_Arr)
	{
		$tempArr=array();
		if(isset($address_Arr['address']) && $address_Arr['address']!='')
		{
			$tempArr[] = $address_Arr['address'];
		}
		if(isset($address_Arr['address2']) && $address_Arr['address2']!='')
		{
			$tempArr[] = $address_Arr['address2'];
		}
		if(isset($address_Arr['city']) && $address_Arr['city']!='')
		{
			$tempArr[] = $address_Arr['city'];
		}
		if(isset($address_Arr['zipcode']) && $address_Arr['zipcode']!='')
		{
			$tempArr[] = $address_Arr['zipcode'];
		}

		$tempArr = array_filter($tempArr);

		$return = '';
		if(!empty($tempArr))
		{
			$return = implode(' ',$tempArr);
		}
		return $return;
	}

  if($_POST){
    if(isset($_POST['property_id']) && !empty($_POST['property_id'])){
      $prop_implod = implode(',',$_POST['property_id']);
      $sql_del = "DELETE FROM planitlo_locations.missing_ignore WHERE `property_id` IN (".$prop_implod.")";
      $del__ = $db->query($sql_del);

      if(isset($_POST['ignore_missing_property']) && !empty($_POST['ignore_missing_property']))
      {
        foreach ($_POST['ignore_missing_property'] as $key => $miss_prop_id) {
          $sql_ins = "INSERT INTO planitlo_locations.missing_ignore (`property_id`) VALUES('".$miss_prop_id."')";
          $db->query($sql_ins);
        }
      }
    }
    header("Location: ".$_SERVER['HTTP_REFERER']);
  }
  


	$current_page = 'missing-property.php';
	$criteria = "";
	//$sql_list_query = "SELECT DISTINCT(T.tag_name),T.tag_id,T.color_code,C.category FROM `tags` AS T INNER JOIN `tag_categories` AS C ON C.id=T.category_id  ORDER BY tag_name ASC";
	$sql_list_query="SELECT *,(SELECT count(prop_loc) from planitlo_locations.sorting where sorting.prop_loc = property.property_id GROUP BY sorting.prop_loc) as total_records, (SELECT id from planitlo_locations.missing_ignore where property_id = property.id) as ignore_property FROM  planitlo_crm.property WHERE property.property_id NOT IN (SELECT prop_loc FROM planitlo_locations.sorting)";
	$result = $db->query($sql_list_query) or die($sql_list_query);
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
      <td colspan="4" align="left" valign="top" nowrap="nowrap"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
        <tbody>
          <tr>
            <td width="100%" align="left" valign="top" class="lastview"><b style="white-space: nowrap;">Missing Properties Without Web Images  &nbsp;&nbsp;</b> </td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td align="left" valign="top" class="mar"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top" class="moduleTitle">Missing Properties Without Web Images </td>
                      <td align="right" valign="top">
                        Filter : <select name="filter_result" id="filter_result" style="margin-bottom: 10px">
                          <option value="missing-property.php">Show All</option>
                          <option value="missing-property.php?filter=hide_ignored" <?php echo isset($_GET['filter']) && $_GET['filter']=='hide_ignored' ? 'selected' : ''; ?>>Hide Ignored Property</option>
                          <option value="missing-property.php?filter=only_ignored" <?php echo isset($_GET['filter']) && $_GET['filter']=='only_ignored' ? 'selected' : ''; ?>>Show Only Ignored Property</option>
                        </select>
                      </td>
                    </tr>
                  </table>
                  <form method="post" action="" name="ignore_list_form">
                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview">
                          
                          <tr bgcolor="#D0E1F4" height="20">
                            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="100%" align="left"> <strong>Sl No&nbsp;</strong>&nbsp; </div></td>
                            <td width="180" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> Property Id</strong>&nbsp;</td>
                            
                            <td height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Company</strong></td>
                            <td  height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Address</strong></td>
                            <td  height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Ignore</strong></td>
                            <td width="21" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                          </tr>
                          <? 
						$numrows = $db->getNumRows($result); 
							$c=1; $cc=0;
						while($lrow = $db->fetchArray($result)){
							             if(isset($_GET['filter']) && $_GET['filter']=='hide_ignored' && $lrow['ignore_property'] != '')
                           {
                              continue;
                           }
                           if(isset($_GET['filter']) && $_GET['filter']=='only_ignored' && $lrow['ignore_property'] =="")
                           {
                              continue;
                           }
                           $cc = 1;
							?>
                          <tr class="grid" height="20">
                            <td  height="20" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                            <td  nowrap="nowrap" scope="col" class="n"><?php echo $c; ?></td>
                            <td height="25" nowrap="nowrap" class="n" scope="col"><?php echo $lrow['property_id']; ?></td>
                          
                            <td height="25" nowrap="nowrap" class="n" scope="col"><?php echo $lrow['company']; ?></td>
                            <td height="25" nowrap="nowrap" class="n" scope="col"><?php echo show_address($lrow); ?></td>
              							<td height="25" nowrap="nowrap" class="n" scope="col">
              								<input type="checkbox" name="ignore_missing_property[]" value="<?php echo $lrow['id']; ?>" <?php echo $lrow['ignore_property'] > 0 ? 'checked' : ''; ?>>
                              <input type="hidden" name="property_id[]" value="<?php echo $lrow['id']; ?>">
              							</td>						
						  </tr>							 
                          <? $c=$c+1; }

                          if($numrows < 1 || $cc==0){ echo '<td class="formtxt" colspan="6" height="30" align="center"> No records found.</td>';}?>
                          <?php if($cc==1) {?>
                          <tr class="grid" height="20">
                              <td colspan="7" align="right">
                                <div class="buttonset">
        <input type="submit" value="Update" name="btnUpdate" class="new-Btn button is_enabled addcatAdd">
        <div class="clear"></div>
      </div>
                              </td> 
                            <td width="21" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>

                          </tr>
                          <?php } ?>
                        </table></td>
                      </tr>
                    </table>
                    </form>
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
