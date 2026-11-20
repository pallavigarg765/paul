<?php
include('include/security.php');
include('include/MysqlHelper.php');
include('include/functions.php');

$db = new MysqlHelper();

// Fix for magic quotes if enabled (mostly legacy, but just in case)
if (get_magic_quotes_gpc()) {
    function stripslashes_deep($value) {
        return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
    }
    $_POST = stripslashes_deep($_POST);
}

if (isset($_POST['category']) && !empty($_POST['category'])) {
    foreach ($_POST['category'] as $key => $categoryName) {
        $isCategoryActive = (isset($_POST['active'][$key]) && strtolower($_POST['active'][$key]) == '1') ? 1 : 0;

        $insertArr = array (
            'category' => $categoryName,
            'alias1' => $_POST['alias1'][$key],
            'alias2' => $_POST['alias2'][$key],
            'alias3' => $_POST['alias3'][$key],
            'alias4' => $_POST['alias4'][$key],
            'alias5' => $_POST['alias5'][$key],
            'status' => $isCategoryActive,
            'updated_at' => date('Y-m-d H:i:s')
        );

        // Debug: Log data before insert/update to verify apostrophes etc.

        // Check if category exists
        $whereClos = $db->buildWhere(array(
            'category' => $categoryName
        ));
        $checkQuery2 = "SELECT * FROM category_alias WHERE $whereClos";
        $categoryAliases = $db->fetchOne($checkQuery2);

        if (isset($categoryAliases['id'])) {
            // UPDATE existing
            $whereClose = array(
                'category' => $categoryName
            );
            $db->update('category_alias', $insertArr, $whereClose);
        } else {
            $insertArr['created_at'] = date('Y-m-d H:i:s');
            // INSERT new
            $db->insert('category_alias', $insertArr);
        }
    }
}

$orderBy = "category1";
$orderDirection = "asc";
if (isset ($_GET['sort_result']) && $_GET['sort_result'] !="" && isset ($_GET['sort_direction']) && $_GET['sort_direction'] !="") {
    $orderBy = $_GET['sort_result'];
    $orderDirection = $_GET['sort_direction'];
}
$sql_list_query = "SELECT DISTINCT(SRT.category1),CA.created_at,
  COUNT(*) AS total_prop from planitlo_locations.sorting as SRT LEFT JOIN planitlo_crm.category_alias as CA ON CA.category = SRT.category1  WHERE SRT.category1 != '' AND SRT.category1 NOT LIKE '%prv' AND SRT.category1 NOT LIKE '%-z' AND SRT.category1 NOT LIKE '%-prv' AND SRT.category1 NOT LIKE '%-b' AND SRT.category1 NOT LIKE '%-c' AND SRT.category1 NOT LIKE '%-d' GROUP BY SRT.category1 ORDER BY $orderBy $orderDirection";
$result = $db->fetchAll($sql_list_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
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

</head>
<body>
<?php include("header.php"); ?>
<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="4" align="left" valign="top" nowrap="nowrap">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
          <tbody>
            <tr>
              <td width="100%" align="left" valign="top" class="lastview"><b style="white-space: nowrap;">Category Alias  &nbsp;&nbsp;</b> </td>
            </tr>
            <tr>
              <td align="left" valign="top">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center" valign="top" class="side-nav"><?php include('sidenav.php');?></td>
                    <td align="left" valign="top" class="mar">
                      
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td align="left" valign="top" class="moduleTitle">Category Alias </td>
                          <td align="right" valign="top">
                            <a href="export_category_alias.php" style="padding: 4px 9px;
    background: #2776b5;
    color: white;
    font-size: 12px;
    text-decoration: none;
    border: 1px solid #2776b5;
    border-radius: 2px;" target="_blank">Download Excel</a>
                            
                            <button style="margin-bottom: 7px;padding: 2px 10px;" type="button" onclick="submitMyForm()">Save</button>
                            </td>
                        </tr>
                      </table>
                    <form method="GET" action="category_alias.php">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 11px">
                            <tr>
                                <td align="left" valign="top" width="200">
                                Sort By : 
                                <select name="sort_result" id="sort_result" style="margin: 10px 0;">
                                    <option value="category1" <?php echo isset($_GET) && $_GET['sort_result']=='category1' ? 'selected' : ''; ?>>Category name</option>
                                    <option value="alias1" <?php echo isset($_GET) && $_GET['sort_result']=='alias1' ? 'selected' : ''; ?>>Category Alias 1</option>
                                    <option value="alias2" <?php echo isset($_GET) && $_GET['sort_result']=='alias2' ? 'selected' : ''; ?>>Category Alias 2</option>
                                    <option value="alias3" <?php echo isset($_GET) && $_GET['sort_result']=='alias3' ? 'selected' : ''; ?>>Category Alias 3</option>
                                    <option value="alias4" <?php echo isset($_GET) && $_GET['sort_result']=='alias4' ? 'selected' : ''; ?>>Category Alias 4</option>
                                    <option value="alias5" <?php echo isset($_GET) && $_GET['sort_result']=='alias5' ? 'selected' : ''; ?>>Category Alias 5</option>
                                    <option value="status" <?php echo isset($_GET) && $_GET['sort_result']=='status' ? 'selected' : ''; ?>>Public</option>
                                    <option value="created_at" <?php echo isset($_GET) && $_GET['sort_result']=='created_at' ? 'selected' : ''; ?>>Created Date</option>
                                </select>
                                <select name="sort_direction" id="sort_direction" style="margin: 10px 0;">
                                    <option value="asc" <?php echo isset($_GET) && $_GET['sort_direction']=='asc' ? 'selected' : ''; ?>>Ascending</option>
                                    <option value="desc" <?php echo isset($_GET) && $_GET['sort_direction']=='desc' ? 'selected' : ''; ?>>Descending</option>
                                </select>
                                <input style="margin: 7px 0;padding: 2px 10px;" type="submit" name="submit_btn" value="Submit">
                                </td>
                                
                            </tr>
                        </table>
                    </form>
                    <form id="property-reports-form" method="post" action="category_alias.php">
                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                          <tr>
                            <td align="left" valign="top">
                              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview">
                                <tr bgcolor="#D0E1F4" height="20">
                                  <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">
                                    <div style="white-space: nowrap;" width="100%" align="center"> <strong>Public&nbsp;</strong>&nbsp; </div>
                                  </td>
                                  <td width="123" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> Category name</strong></td>
                                  <td width="123" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Created date</strong></td>
                                  <?php 
                                    for($i=1; $i <=5 ; $i++) { 
                                  ?>
                                  <td width="50" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> Category Alias <?php echo $i; ?></strong></td>
                                  <?php } ?>
                                </tr>
                                <?php
                                $c=0;
                                foreach ($result as $lrow) {
                                    $whereClos = $db->buildWhere(array(
                                      'category' => $lrow['category1']
                                    ));
                                    $checkQuery2 = "SELECT * FROM category_alias WHERE $whereClos";
                                    $categoryAliases = $db->fetchOne($checkQuery2);
                                ?>
                                <tr class="grid" height="20">
                                  <input type="hidden" name="category[<?php echo $c; ?>]" value='<?php echo htmlspecialchars($lrow['category1'], ENT_QUOTES); ?>'>
                                  <td nowrap="nowrap" align="center" width="100" scope="col" class="n"><input style="width:100px" type="checkbox" value="1" name="active[<?php echo $c; ?>]" <?php if($categoryAliases['status'] == "1") echo "checked"; ?>/></td>
                                  <td nowrap="nowrap" width="150" scope="col" class="n"><?php echo htmlspecialchars($lrow['category1'], ENT_QUOTES); ?></td>
                                  <td nowrap="nowrap" width="150" scope="col" class="n"><?php echo $lrow['created_at']; ?></td>
                                  <td height="25" nowrap="nowrap" class="n" scope="col"><input style="width:100px" type="text" name="alias1[<?php echo $c; ?>]" value="<?php echo htmlspecialchars($categoryAliases['alias1'], ENT_QUOTES); ?>"></td>
                                  <td height="25" nowrap="nowrap" class="n" scope="col"><input style="width:100px" type="text" name="alias2[<?php echo $c; ?>]" value="<?php echo htmlspecialchars($categoryAliases['alias2'], ENT_QUOTES); ?>"></td>
                                  <td height="25" nowrap="nowrap" class="n" scope="col"><input style="width:100px" type="text" name="alias3[<?php echo $c; ?>]" value="<?php echo htmlspecialchars($categoryAliases['alias3'], ENT_QUOTES); ?>"></td>
                                  <td height="25" nowrap="nowrap" class="n" scope="col"><input style="width:100px" type="text" name="alias4[<?php echo $c; ?>]" value="<?php echo htmlspecialchars($categoryAliases['alias4'], ENT_QUOTES); ?>"></td>
                                  <td height="25" nowrap="nowrap" class="n" scope="col"><input style="width:100px" type="text" name="alias5[<?php echo $c; ?>]" value="<?php echo htmlspecialchars($categoryAliases['alias5'], ENT_QUOTES); ?>"></td>
                                </tr>                             
                                <?php $c++; } ?>
                              </table>
                              
                            </td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 11px">
                          <tr>
                            <td align="left" valign="top" width="200">
                        <button style="margin: 7px 0;padding: 2px 10px;" type="button" onclick="submitMyForm()">Save</button>
                    </td>
                          </tr>
                        </table>
                      </form>
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
<?php include("footer.php"); ?>

<script src="css/popover/demo-mock.js"></script> 
<script>
    function submitMyForm() {
    document.getElementById("property-reports-form").submit();
  }
</script>
</body>
</html>
