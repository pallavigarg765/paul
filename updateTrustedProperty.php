<?php
include('include/dbcon.php');
session_start();

$db = new DBConn();

$checked = $_POST["checked"];
$property_id = $_POST["property_id"];
$user_id = isset($_SESSION['userid']) ? $_SESSION['userid'] : 'NULL';

$checked = ($checked == "true") ? 1 : 0;

if (trim($property_id) != '') {

    // Update property
    $sql = "UPDATE property
            SET trusted = '".$checked."', trusted_updated_at = '".date('Y-m-d H:i:s')."'
            WHERE id = '".$property_id."'";
	
    if (mysql_query($sql)) {

        // Store log
        $logSql = "INSERT INTO property_trusted_log
                    (property_id, value, user_id)
                   VALUES
                    ('".$property_id."', '".$checked."', ".($user_id == 'NULL' ? "NULL" : "'".$user_id."'").")";

        mysql_query($logSql);

        echo "ok";
    } else {
        echo mysql_error();
    }
}
?>