<?php

declare(strict_types=1);

include('include/dbcon.php');

echo '<pre>';

echo "========================================\n";
echo "CRM USER EMAIL MIGRATION\n";
echo "========================================\n\n";

$db = new DBConn();

/*
 * Check whether email column already exists.
 */
$checkSql = "
    SHOW COLUMNS FROM user LIKE 'email'
";

$checkResult = mysql_query($checkSql);

if (!$checkResult) {
    exit(
        "ERROR checking user table:\n" .
        mysql_error() .
        "\n"
    );
}

if (mysql_num_rows($checkResult) > 0) {
    echo "The email column already exists.\n";
    echo "No database change was required.\n";
    echo '</pre>';
    exit;
}

/*
 * Add nullable email column.
 *
 * We intentionally do NOT make it UNIQUE yet.
 * First we will populate and validate all existing users.
 */
$sql = "
    ALTER TABLE user
    ADD COLUMN email VARCHAR(255) NULL
";

$result = mysql_query($sql);

if (!$result) {
    exit(
        "ERROR adding email column:\n" .
        mysql_error() .
        "\n"
    );
}

echo "SUCCESS\n\n";
echo "The email column was added to the user table.\n";
echo "Type: VARCHAR(255)\n";
echo "Nullable: YES\n\n";

echo "Next step:\n";
echo "Populate the email address for each active CRM user.\n";

echo '</pre>';