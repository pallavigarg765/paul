<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

include('include/dbcon.php');

echo '<pre>';

echo "========================================\n";
echo "LINK COGNITO TEST USER\n";
echo "========================================\n\n";

echo "STEP 1: Loading database connection...\n";

$db = new DBConn();

echo "OK\n\n";


/*
|--------------------------------------------------------------------------
| Cognito / CRM Mapping
|--------------------------------------------------------------------------
*/

$crmUserId = 17;
$cognitoEmail = 'Paulzema@gmail.com';


echo "STEP 2: Checking CRM user...\n";

$sql = "
    SELECT id, username, email, active
    FROM user
    WHERE id = " . (int)$crmUserId . "
";

$result = mysql_query($sql);

if (!$result) {
    die(
        "MYSQL ERROR:\n" .
        mysql_error() .
        "\n"
    );
}

echo "Query OK\n\n";


if (mysql_num_rows($result) == 0) {
    die(
        "ERROR: CRM user ID " .
        (int)$crmUserId .
        " was not found.\n"
    );
}

$user = mysql_fetch_assoc($result);

echo "CRM USER FOUND:\n";
echo "ID:       " . $user['id'] . "\n";
echo "Username: " . $user['username'] . "\n";
echo "Email:    " . ($user['email'] ? $user['email'] : '[MISSING]') . "\n";
echo "Active:   " . $user['active'] . "\n\n";


if ((int)$user['active'] != 1) {
    die(
        "ERROR: CRM user ID " .
        (int)$crmUserId .
        " is not active.\n"
    );
}


/*
|--------------------------------------------------------------------------
| Check if Cognito email is already assigned to another CRM user
|--------------------------------------------------------------------------
*/

echo "STEP 3: Checking whether email already exists...\n";

$emailEscaped = mysql_real_escape_string($cognitoEmail);

$emailCheckSql = "
    SELECT id, username, email, active
    FROM user
    WHERE email = '" . $emailEscaped . "'
";

$emailResult = mysql_query($emailCheckSql);

if (!$emailResult) {
    die(
        "MYSQL ERROR:\n" .
        mysql_error() .
        "\n"
    );
}

echo "Query OK\n\n";


if (mysql_num_rows($emailResult) > 0) {

    echo "WARNING: This email already exists in the CRM.\n\n";

    while ($existing = mysql_fetch_assoc($emailResult)) {

        echo "ID:       " . $existing['id'] . "\n";
        echo "Username: " . $existing['username'] . "\n";
        echo "Email:    " . $existing['email'] . "\n";
        echo "Active:   " . $existing['active'] . "\n";
        echo "----------------------------------------\n";
    }

    die("\nNO CHANGES WERE MADE.\n");
}


echo "Email is not currently assigned.\n\n";


/*
|--------------------------------------------------------------------------
| Update CRM user
|--------------------------------------------------------------------------
*/

echo "STEP 4: Updating CRM user...\n";

$updateSql = "
    UPDATE user
    SET email = '" . $emailEscaped . "'
    WHERE id = " . (int)$crmUserId . "
    AND active = 1
";

$updateResult = mysql_query($updateSql);

if (!$updateResult) {
    die(
        "MYSQL UPDATE ERROR:\n" .
        mysql_error() .
        "\n"
    );
}

echo "UPDATE QUERY SUCCESSFUL.\n\n";


/*
|--------------------------------------------------------------------------
| Verify update
|--------------------------------------------------------------------------
*/

echo "STEP 5: Verifying update...\n";

$verifySql = "
    SELECT id, username, email, active
    FROM user
    WHERE id = " . (int)$crmUserId . "
";

$verifyResult = mysql_query($verifySql);

if (!$verifyResult) {
    die(
        "MYSQL VERIFY ERROR:\n" .
        mysql_error() .
        "\n"
    );
}

if (mysql_num_rows($verifyResult) == 0) {
    die(
        "ERROR: Could not find CRM user ID " .
        (int)$crmUserId .
        " after update.\n"
    );
}

$updatedUser = mysql_fetch_assoc($verifyResult);


/*
|--------------------------------------------------------------------------
| Final output
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "SUCCESS\n";
echo "========================================\n\n";

echo "CRM USER ID: " . $updatedUser['id'] . "\n";
echo "Username:    " . $updatedUser['username'] . "\n";
echo "Email:       " . $updatedUser['email'] . "\n";
echo "Active:      " . $updatedUser['active'] . "\n";

echo "\nCognito Email: " . $cognitoEmail . "\n";
echo "CRM User ID:   " . $crmUserId . "\n";

echo "\n========================================\n";
echo "DONE\n";
echo "========================================\n";

?>