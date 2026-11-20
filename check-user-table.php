<?php

include('include/dbcon.php');

$db = new DBConn();

echo "========================================\n";
echo "USER TABLE STRUCTURE\n";
echo "========================================\n\n";

$result = $db->query("DESCRIBE user");

if (!$result) {
    die("Unable to read user table structure.");
}

while ($row = $db->fetchArray($result)) {

    echo "Field: " . $row['Field'] . "\n";
    echo "Type: " . $row['Type'] . "\n";
    echo "Null: " . $row['Null'] . "\n";
    echo "Key: " . $row['Key'] . "\n";
    echo "Default: " . (string)$row['Default'] . "\n";
    echo "Extra: " . $row['Extra'] . "\n";
    echo "----------------------------------------\n";
}

echo "\n";
echo "========================================\n";
echo "USER TABLE RECORDS (SAFE FIELDS ONLY)\n";
echo "========================================\n\n";

/*
 * Get column names first so we can exclude sensitive fields.
 */
$columnsResult = $db->query("DESCRIBE user");

$columns = array();

while ($column = $db->fetchArray($columnsResult)) {
    $field = $column['Field'];

    // Never display password or other credential fields.
    if (
        strtolower($field) !== 'password' &&
        strtolower($field) !== 'pass' &&
        strtolower($field) !== 'passwd'
    ) {
        $columns[] = $field;
    }
}

if (count($columns) === 0) {
    die("No safe columns available to display.");
}

$sql = "SELECT `" . implode("`,`", $columns) . "` FROM user";

$result = $db->query($sql);

if (!$result) {
    die("Unable to read user records.");
}

while ($row = $db->fetchArray($result)) {

    foreach ($columns as $column) {
        echo $column . ": " . (string)$row[$column] . "\n";
    }

    echo "----------------------------------------\n";
}

$db->close();

echo "\n";
echo "========================================\n";
echo "END\n";
echo "========================================\n";