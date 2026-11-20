<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/functions.php');
	$db = new DBConn();
	
	/*$nameorder = 'ASC';
	$nameorder = 'arrow';
	$idorder = 'ASC';
	$idarrow = 'arrow';
	$propertynameorder = 'ASC';
	$propertynamearrow = 'arrow';
*/	
$select_cb_notes="SELECT * FROM `planitlo_crm`.`options` WHERE name='cb_notes'";
$select_cb_notes = $db->query($select_cb_notes) or die(mysql_error()." ".$select_cb_notes);
$cb_notes_result = $db->fetchArray($select_cb_notes);
if(isset($_POST) && !empty($_POST))
{
  $value=$_POST['value'];
  $coumn=$_POST['name'];
  $cbnotes = array();
    if(isset($cb_notes_result['value']) && $cb_notes_result['value']!='')
    {
      $cbnotes = json_decode($cb_notes_result['value'],true);
    }
    $cbnotes[$coumn]=$value;
    $cbnotesjson = json_encode($cbnotes);
    $sqlUpdateProperty = "UPDATE `planitlo_crm`.`options` SET value = '".$cbnotesjson."' WHERE name = 'cb_notes'";
    $db->query($sqlUpdateProperty) or die(mysql_error()." ".$sqlUpdateProperty);
    echo 'ok';
    exit;
}
	$current_page = 'property-reports.php';
  $link_url ='property-reports.php';
  $pram_arr = isset($_GET) ? $_GET : array();
	$criteria = "";
	$sql_list_query="SELECT * FROM `planitlo_crm`.`property` AS N WHERE 1 ";

	$sql_list_query .=" AND N.property_id !='0' AND N.property_id !='' ";
  

  if(isset($_GET['filter_by_pid']) && $_GET['filter_by_pid']!='')
  {
    $sql_list_query .= ' AND N.property_id ="'.$_GET['filter_by_pid'].'" ';
  }

  if(isset($_GET['filter_result']) && !empty($_GET['filter_result']))
  {
    $temp_qry = ' AND ( ';
    foreach ($_GET['filter_result'] as $key => $value) {
      $temp_qry .=" N.".$value." ='1' "; 
      if(($key+1) < count($_GET['filter_result']))
      {
        $temp_qry .= ' OR ';
      }
    }
    $temp_qry .= ' ) ';
    $sql_list_query .= $temp_qry;
  }

  if(isset($_GET['sort_result']) && $_GET['sort_result']=='asc')
  {
    $sql_list_query .=" ORDER BY N.property_id ASC";  
  }
  else if(isset($_GET['sort_result']) && $_GET['sort_result']=='desc')
  {
    $sql_list_query .=" ORDER BY N.property_id DESC";
  }
  else
  {
    $sql_list_query .=" ORDER BY N.property_id ASC"; 
  }

  // if(isset($pram_arr) && !empty($_GET))
  // {
  //   $link_url = $current_page.'?'.http_build_query($pram_arr);
  // }

	
	// Paging Details
	$go = 1;
	$perpage = isset($_GET) && $_GET['no_of_record']!='' ? $_GET['no_of_record'] : 20 ;
	if(isset($_REQUEST['go']) && $_REQUEST['go'] != '')
		$go = $_REQUEST['go'];
	if($go > 1){
		$chkgo = "?go=".$go;
	}
	$offset = (($go * $perpage) - $perpage);
	$result_total = $db->query($sql_list_query) or die(mysql_error()." ".$sql_list_query);
	$totalrecs = $db->getNumRows($result_total); 
	$sql_list_query .= " limit $offset,$perpage";
	$result = $db->query($sql_list_query) or die(mysql_error()." ".$sql_list_query);
	$count=$db->getNumRows($result);
	$total_pages = ceil($totalrecs / $perpage);
	$start_var = $offset + 1;
	$end_var_records = $count + (($go-1)*$perpage);
	$end_var = $start_var + $perpage - 1;
	$paging_var = "";
	// Build Previous Pagin Link 
	if($go > 1){ 
		$prev = ($go - 1); 

    $pram_arr['go'] = $prev;
    $link_url = $current_page.'?'.http_build_query($pram_arr);

		$paging_var .= "<a href='".$link_url."' style='text-decoration:none'><img src='images/previous.gif' alt='Previous' align='absmiddle' border='0' width='8' height='11' /> </a>"; 
	} 
	// Build Paging Link 
	//for($i = 1; $i <= $total_pages; $i++){
		$paging_var .=  "<span> (".($offset+1)." - ".($offset+$perpage)." of ".$totalrecs.") </span>"; 
	//} 
	// Build Next Paging Link 
	if($go < $total_pages){ 
		$next = ($go + 1); 
    $pram_arr['go'] = $next;
    $link_url = $current_page.'?'.http_build_query($pram_arr);
		$paging_var .=  "<a href='".$link_url."'  style='text-decoration:none'><img src='images/next.gif' alt='Next' align='absmiddle' border='0' width='8' height='11' /> </a>"; 
	}
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
<link rel="stylesheet" href="css/multi_select_plugin/styles/multiselect.css" type="text/css" />
<script type="text/javascript" src="css/multi_select_plugin/scripts/multiselect.min.js"></script>

<script src="css/popover/jquery-1.9.1.min.js"></script>  
<script src="css/popover/jquery.mockjax.js"></script>
<link href="css/popover/tip-yellowsimple.css" rel="stylesheet">
<script src="css/popover/jquery.poshytip.js"></script>
<link href="css/popover/jquery-editable.css" rel="stylesheet">
<script src="css/popover/jquery-editable-poshytip.js"></script>


<style>
    /* example of setting the width for multiselect */
    #filter_result_multiSelect {
      width: 130px;
    }
    .multiselect-input-div input {
      border: 1px solid #ababab;
      background: #fff;
      margin: 7px 0 9px 0;
      padding: 3px;
      vertical-align: middle;
    }
    .button1 {
      font: bold 11px Arial;
      text-decoration: none;
      background-color: #EEEEEE;
      color: #333333;
      padding: 2px 6px 2px 6px;
      border-top: 1px solid #CCCCCC;
      border-right: 1px solid #333333;
      border-bottom: 1px solid #333333;
      border-left: 1px solid #CCCCCC;
    }
  </style>
  <script>
    var f = 'plain';
    var c = window.location.href.match(/c=inline/i) ? 'inline' : 'popup';
    $.fn.editable.defaults.mode = c === 'inline' ? 'inline' : 'popup';
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
            <td width="100%" align="left" valign="top" class="lastview"><b style="white-space: nowrap;">Checkbox Report  &nbsp;&nbsp;</b> </td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td align="left" valign="top" class="mar">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top" class="moduleTitle">Checkbox Report </td>
                      <td align="left" valign="top">&nbsp;</td>
                    </tr>
                  </table>

                  <form class="" id="property-reports-form" action="property-reports.php">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 11px">
                      <tr>
                        <td align="left" valign="top" width="200">
                          Sort By : 
                          <select name="sort_result" id="sort_result" style="margin: 10px 0;">
                            <option value="asc" <?php echo isset($_GET) && $_GET['sort_result']=='asc' ? 'selected' : ''; ?>>Property ID A-Z</option>
                            <option value="desc" <?php echo isset($_GET) && $_GET['sort_result']=='desc' ? 'selected' : ''; ?>>Property ID Z-A</option>
                          </select>
                        </td>

                        <td align="left" valign="top" width="440">
                          Filter By :         
                          <input type="text" name="filter_by_pid" value="<?php echo isset($_GET) && $_GET['filter_by_pid']!='' ? $_GET['filter_by_pid'] : ''; ?>" placeholder="Find by property id" onkeyup="ajax_showOptions(this, 'id', event, document.getElementById('chkIncludeCh').checked);"> 
                          <select name="filter_result[]" id="filter_result" style="margin: 10px 0;" multiple>
                            <?php for($i=1; $i <=10 ; $i++) { 
                              $selected = '';
                              if(isset($_GET) && isset($_GET['filter_result']) && !empty($_GET['filter_result']))
                              {
                                $textto = "cb".$i;
                                $aa = $_GET['filter_result'];

                                if(in_array($textto,$aa))
                                {
                                  $selected = 'selected';
                                }
                              }
                            ?>
                              <option value="cb<?php echo $i; ?>" <?php echo  $selected; ?>>CB<?php echo $i; ?></option>  
                            <?php } ?>
                          </select>
                        </td>
                        <td align="left" valign="top" width="200"><input style="margin: 7px 0;padding: 2px 10px;" type="submit" name="submit_btn" value="Submit"> <button type="button" onclick="window.location.href='property-reports.php'">Clear Filter</button></td>
                        <td align="right" valign="top"><div style="margin: 10px 0;">
                          Records Per Page : 
                          <select name="no_of_record" id="no_of_record" style="margin: 10px 0;">
                              <option value="20" <?php echo isset($_GET) && $_GET['no_of_record']=='20' ? 'selected' : ''; ?>>20</option>
                              <option value="50" <?php echo isset($_GET) && $_GET['no_of_record']=='50' ? 'selected' : ''; ?>>50</option>
                              <option value="100" <?php echo isset($_GET) && $_GET['no_of_record']=='100' ? 'selected' : ''; ?>>100</option>
                              <option value="250" <?php echo isset($_GET) && $_GET['no_of_record']=='250' ? 'selected' : ''; ?>>250</option>
                              <option value="500" <?php echo isset($_GET) && $_GET['no_of_record']=='500' ? 'selected' : ''; ?>>500</option>
                          </select>
                          <?=$paging_var?></div></td>
                      </tr>
                    </table>
                  </form>

                  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview">

                          <tr bgcolor="#D0E1F4" height="20">
                            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="100%" align="left"> <strong>Sl No&nbsp;</strong>&nbsp; </div></td>
                            <td width="123" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> Property ID</strong>&nbsp;</td>
                            <td width="123" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> CRM Link</strong>&nbsp;</td>
                            <td width="123" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> Website Link</strong>&nbsp;</td>


                            <?php 
                             $cb_notes_arr = !empty($cb_notes_result['value']) ? json_decode($cb_notes_result['value'],true) : array();
                            	for($i=1; $i <=10 ; $i++) { 
                                  $textto = "cb".$i;
                                  $cb_note = (isset($cb_notes_arr[$textto]) && $cb_notes_arr[$textto]!='') ? $cb_notes_arr[$textto] : '';
                            ?>
                                  <td width="50" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><br><strong> <?php echo strtoupper($textto); ?></strong>&nbsp;
                                  <a href="javascript:;" data-type="textarea" data-value="<?php echo $cb_note; ?>" autotext="never" data-placeholder="Your comments here..." data-title="Enter comments" data-name="<?php echo $textto;?>" data-pk="1" class="comments editable editable-pre-wrapped editable-click" data-url="https://planitlocations.com/crm/property-reports.php">Alias</a>
                                  <br><br><a href="reset_property_checkbox.php?checkbox=<? echo $textto; ?>" class="button1" onclick="return confirm('Are you sure ?')">RESET</a><br><br>
                                  </td>
                            <?php } ?>
                           <!--  <td width="50" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> CB1</strong>&nbsp;</td>
                            <td width="50" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> CB2</strong>&nbsp;</td>
                            <td width="50" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> CB3</strong>&nbsp;</td>
                            <td width="50" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> CB4</strong>&nbsp;</td>
                            <td width="50" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> CB5</strong>&nbsp;</td>
                            <td width="50" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> CB6</strong>&nbsp;</td>
                            <td width="50" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> CB7</strong>&nbsp;</td>
                            <td width="50" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> CB8</strong>&nbsp;</td>
                            <td width="50" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> CB9</strong>&nbsp;</td>
                            <td width="50" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> CB10</strong>&nbsp;</td> -->
                          
							
                            <td width="21" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                          </tr>
                          <? 
						$numrows = $db->getNumRows($result); 
							$c=1;
						while($lrow = $db->fetchArray($result)){
							
							?>
                          <tr class="grid" height="20">
                            <td width="13" height="20" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                            <td width="107" nowrap="nowrap" scope="col" class="n"><?php echo (($perpage*($go-1))+$c); ?></td>
                            <td height="25" nowrap="nowrap" class="n" scope="col"><?php echo $lrow['property_id'];?></td>
                            <td height="25" nowrap="nowrap" class="n" scope="col"><a target="_blank" href="propertydetail.php?id=<?php echo $lrow['id']; ?>">View</a></td>
                            <td height="25" nowrap="nowrap" class="n" scope="col"><a target="_blank" href="https://planitlocations.com/search/searchid.php?id=<?php echo $lrow['property_id']; ?>">View</a></td>

                            <?php for($i=1; $i <=10 ; $i++) { 
                                  $textto = "cb".$i; ?>
                                  <td height="25" nowrap="nowrap" class="n" scope="col"><input type="checkbox" <?php	if($lrow[$textto] == "1") echo "checked"; ?>/></td>
                            <?php } ?>
                            
							<td width="13" height="20" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
						  </tr>							 
                          <? $c=$c+1; }if($numrows < 1){ echo '<tr><td class="formtxt" colspan="6"> No Records against your search criteria.</td></tr>';}?>
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
<script src="css/popover/demo-mock.js"></script> 
<script>
  $(function(){
      //defaults
     $.fn.editable.defaults.url = '/post'; 
     $.fn.editable.defaults.ajaxOptions = {type: "POST"};
      //enable / disable
     $('.comments').editable({
          showbuttons: 'bottom',
          url: '/post',
          title: 'Enter username',
          display: function(value) {
            $(this).text('Alias'); 
          }    
      }); 
      $('#no_of_record').change(function(){
          $('#property-reports-form').submit();
      });
  });
  document.multiselect('#filter_result')
    .setCheckBoxClick("checkboxAll", function(target, args) {
      console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
    })
    .setCheckBoxClick("1", function(target, args) {
      console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
    });
    $('#filter_result_input').attr('placeholder','Filter by checkbox');
</script>
</body>
</html>
