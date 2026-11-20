<?php

declare(strict_types=1);

include('include/dbcon.php');

echo '<pre>';

echo "========================================\n";
echo "CRM USER EMAIL CHECK\n";
echo "========================================\n\n";

$db = new DBConn();

$sql = "
    SELECT
        id,
        username,
        email,
        active
    FROM user
    WHERE active = 1
    ORDER BY id
";

$result = mysql_query($sql);

if (!$result) {
    exit(
        "ERROR:\n" .
        mysql_error() .
        "\n"
    );
}

echo "ACTIVE USERS\n";
echo "------------\n\n";

while ($row = mysql_fetch_assoc($result)) {

    $email = isset($row['email']) && $row['email'] !== ''
        ? $row['email']
        : '[MISSING]';

    echo "ID:       " . $row['id'] . "\n";
    echo "Username: " . $row['username'] . "\n";
    echo "Email:    " . $email . "\n";
    echo "Active:   " . $row['active'] . "\n";

    echo "----------------------------------------\n";
}

echo "\n";
echo "========================================\n";
echo "END\n";
echo "========================================\n";

echo '</pre>';