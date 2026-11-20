<?php
include('include/security.php');
include('include/MysqlHelper.php');
include('include/functions.php');

$db = new MysqlHelper();

// Set correct headers for Excel file
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=category-alias.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Sorting
$orderBy = "category1";
$orderDirection = "asc";

if (isset($_GET['sort_result']) && $_GET['sort_result'] != "" && isset($_GET['sort_direction']) && $_GET['sort_direction'] != "") {
    $orderBy = $_GET['sort_result'];
    $orderDirection = $_GET['sort_direction'];
}

// Query
$sql = "SELECT DISTINCT(SRT.category1), CA.created_at
        FROM planitlo_locations.sorting AS SRT
        LEFT JOIN planitlo_crm.category_alias AS CA ON CA.category = SRT.category1
        WHERE SRT.category1 != ''
          AND SRT.category1 NOT LIKE '%prv'
          AND SRT.category1 NOT LIKE '%-z'
          AND SRT.category1 NOT LIKE '%-prv'
          AND SRT.category1 NOT LIKE '%-b'
          AND SRT.category1 NOT LIKE '%-c'
          AND SRT.category1 NOT LIKE '%-d'
        GROUP BY SRT.category1
        ORDER BY $orderBy $orderDirection";

$result = $db->fetchAll($sql);

// Start Excel table
echo "<table border='1'>";
echo "<tr>
        <th>Public</th>
        <th>Category Name</th>
        <th>Created Date</th>
        <th>Alias 1</th>
        <th>Alias 2</th>
        <th>Alias 3</th>
        <th>Alias 4</th>
        <th>Alias 5</th>
      </tr>";

// Loop and output rows
foreach ($result as $row) {
    $where = $db->buildWhere(array('category' => $row['category1']));
    $alias = $db->fetchOne("SELECT * FROM category_alias WHERE $where");

    echo "<tr>";
    echo "<td>" . ($alias['status'] == 1 ? 'Yes' : 'No') . "</td>";
    echo "<td>" . htmlentities($row['category1']) . "</td>";
    echo "<td>" . $row['created_at'] . "</td>";
    echo "<td>" . htmlentities($alias['alias1']) . "</td>";
    echo "<td>" . htmlentities($alias['alias2']) . "</td>";
    echo "<td>" . htmlentities($alias['alias3']) . "</td>";
    echo "<td>" . htmlentities($alias['alias4']) . "</td>";
    echo "<td>" . htmlentities($alias['alias5']) . "</td>";
    echo "</tr>";
}
echo "</table>";
?>
