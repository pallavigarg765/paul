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
        $isCategoryFeature = (isset($_POST['feature'][$key]) && strtolower($_POST['feature'][$key]) == '1') ? 1 : 0;
        

        $insertArr = array (
            'category' => $categoryName,
            'alias1' => $_POST['alias1'][$key],
            'alias2' => $_POST['alias2'][$key],
            'alias3' => $_POST['alias3'][$key],
            'alias4' => $_POST['alias4'][$key],
            'alias5' => $_POST['alias5'][$key],
            'status' => $isCategoryActive,
            'feature' => $isCategoryFeature,
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
    echo 'success';
    exit;
}
