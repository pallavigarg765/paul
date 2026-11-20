<?php
$bridgeSecret = 'generated-auth-bridge-secret';

$expectedIssuer = 'login.planitlocations.com';

$loginUrl = 'https://login.planitlocations.com/login.php';

$crmHome = '/crm/index.php';

session_start();


if (
    !isset($_SERVER['HTTPS']) ||
    $_SERVER['HTTPS'] == '' ||
    strtolower($_SERVER['HTTPS']) == 'off'
) {

    header('HTTP/1.1 403 Forbidden');

    exit('HTTPS is required.');
}

if (
    isset($_SESSION['userid']) &&
    $_SESSION['userid'] != '' &&
    isset($_SESSION['username']) &&
    $_SESSION['username'] != ''
) {

    header(
        'Location: ' . $crmHome,
        true,
        302
    );

    exit;
}


if (!isset($_GET['assertion'])) {

    ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="Secure PlanIt Locations CRM login"
    >

    <title>
        Sign In | PlanIt Locations
    </title>

    <link
        rel="stylesheet"
        href="/crm/css/auth.css"
    >

</head>

<body>

<div class="auth-page">

    <section class="brand-panel">

        <div class="brand-content">

            <div class="brand-logo">

                <div class="logo-mark">
                    P
                </div>

                <span>
                    PlanIt Locations
                </span>

            </div>


            <div class="brand-copy">

                <span class="eyebrow">
                    CRM PLATFORM
                </span>

                <h1>
                    Everything you need,
                    <span>in one place.</span>
                </h1>

                <p>
                    Securely access your PlanIt Locations
                    workspace and continue managing your
                    business from one centralized platform.
                </p>

            </div>


            <div class="feature-list">

                <div class="feature">

                    <div class="feature-icon">
                        ✓
                    </div>

                    <div>

                        <strong>
                            Secure access
                        </strong>

                        <span>
                            Enterprise-grade authentication
                        </span>

                    </div>

                </div>


                <div class="feature">

                    <div class="feature-icon">
                        ✓
                    </div>

                    <div>

                        <strong>
                            Centralized workspace
                        </strong>

                        <span>
                            Everything your team needs
                        </span>

                    </div>

                </div>


                <div class="feature">

                    <div class="feature-icon">
                        ✓
                    </div>

                    <div>

                        <strong>
                            Built for your team
                        </strong>

                        <span>
                            Simple and secure access
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         LOGIN PANEL
         ===================================================== -->

    <main class="login-panel">

        <div class="login-container">


            <div class="mobile-logo">

                <div class="logo-mark">
                    P
                </div>

                <span>
                    PlanIt Locations
                </span>

            </div>


            <div class="login-header">

                <h2>
                    Welcome back
                </h2>

                <p>
                    Sign in securely with your company
                    account to continue.
                </p>

            </div>


            <a
                href="<?php echo htmlspecialchars(
                    $loginUrl,
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
                class="sign-in-button"
            >

                <span>
                    Sign in
                </span>

                <span class="button-arrow">
                    →
                </span>

            </a>


            <div class="security-note">

                <span class="security-icon">

                    <svg
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >

                        <rect
                            x="3"
                            y="11"
                            width="18"
                            height="10"
                            rx="2"
                        ></rect>

                        <path
                            d="M7 11V7a5 5 0 0 1 10 0v4"
                        ></path>

                    </svg>

                </span>

                <span>
                    Secure authentication powered by
                    Amazon Cognito
                </span>

            </div>


            <div class="login-footer">

                © <?php echo date('Y'); ?>
                PlanIt Locations.
                All rights reserved.

            </div>


        </div>

    </main>


</div>

</body>

</html>
<?php

    exit;
}


$assertion = trim(
    $_GET['assertion']
);

if ($assertion == '') {

    header('HTTP/1.1 400 Bad Request');

    exit('Missing authentication assertion.');
}

$parts = explode(
    '.',
    $assertion
);

if (count($parts) != 2) {

    header('HTTP/1.1 400 Bad Request');

    exit('Invalid authentication assertion.');
}


$encodedPayload = $parts[0];

$encodedSignature = $parts[1];


if (
    $encodedPayload == '' ||
    $encodedSignature == ''
) {

    header('HTTP/1.1 400 Bad Request');

    exit('Invalid authentication assertion.');
}

$signatureBase64 = strtr(
    $encodedSignature,
    '-_',
    '+/'
);


$signatureLength = strlen(
    $signatureBase64
);


$signatureRemainder =
    $signatureLength % 4;


if ($signatureRemainder > 0) {

    $signatureBase64 .= str_repeat(
        '=',
        4 - $signatureRemainder
    );
}


$providedSignature = base64_decode(
    $signatureBase64
);


if ($providedSignature === false) {

    header('HTTP/1.1 400 Bad Request');

    exit('Invalid authentication signature.');
}

$expectedSignature = hash_hmac(
    'sha256',
    $encodedPayload,
    $bridgeSecret,
    true
);


if (
    strlen($expectedSignature) !=
    strlen($providedSignature)
) {

    header('HTTP/1.1 403 Forbidden');

    exit('Invalid authentication signature.');
}

$signatureDifference = 0;

$signatureLength = strlen(
    $expectedSignature
);


$i = 0;

while ($i < $signatureLength) {

    $signatureDifference |= (
        ord($expectedSignature[$i]) ^
        ord($providedSignature[$i])
    );

    $i++;
}


if ($signatureDifference != 0) {

    header('HTTP/1.1 403 Forbidden');

    exit('Invalid authentication signature.');
}

$payloadBase64 = strtr(
    $encodedPayload,
    '-_',
    '+/'
);


$payloadLength = strlen(
    $payloadBase64
);


$payloadRemainder =
    $payloadLength % 4;


if ($payloadRemainder > 0) {

    $payloadBase64 .= str_repeat(
        '=',
        4 - $payloadRemainder
    );
}


$payloadJson = base64_decode(
    $payloadBase64
);


if ($payloadJson === false) {

    header('HTTP/1.1 400 Bad Request');

    exit('Invalid authentication payload.');
}

$payload = json_decode(
    $payloadJson,
    true
);


if (!is_array($payload)) {

    header('HTTP/1.1 400 Bad Request');

    exit('Invalid authentication payload.');
}

if (!isset($payload['iss'])) {

    header('HTTP/1.1 403 Forbidden');

    exit('Invalid authentication issuer.');
}


if (
    $payload['iss'] !=
    $expectedIssuer
) {

    header('HTTP/1.1 403 Forbidden');

    exit('Invalid authentication issuer.');
}


if (!isset($payload['iat'])) {

    header('HTTP/1.1 403 Forbidden');

    exit('Invalid authentication timestamps.');
}


if (!isset($payload['exp'])) {

    header('HTTP/1.1 403 Forbidden');

    exit('Invalid authentication timestamps.');
}


$issuedAt = (int)$payload['iat'];

$expiresAt = (int)$payload['exp'];

$now = time();


if ($issuedAt <= 0) {

    header('HTTP/1.1 403 Forbidden');

    exit('Invalid authentication timestamps.');
}


if (
    $issuedAt >
    ($now + 30)
) {

    header('HTTP/1.1 403 Forbidden');

    exit('Invalid authentication time.');
}


if ($expiresAt <= 0) {

    header('HTTP/1.1 403 Forbidden');

    exit('Invalid authentication timestamps.');
}


if ($expiresAt < $now) {

    header('HTTP/1.1 403 Forbidden');

    exit('Authentication session has expired.');
}


if ($expiresAt <= $issuedAt) {

    header('HTTP/1.1 403 Forbidden');

    exit('Invalid authentication timestamps.');
}


if (!isset($payload['sub'])) {

    header('HTTP/1.1 403 Forbidden');

    exit('Cognito user ID is missing.');
}


$cognitoSub = trim(
    $payload['sub']
);


if ($cognitoSub == '') {

    header('HTTP/1.1 403 Forbidden');

    exit('Cognito user ID is missing.');
}


if (!isset($payload['email'])) {

    header('HTTP/1.1 403 Forbidden');

    exit('Cognito email is missing.');
}


$cognitoEmail = trim(
    strtolower(
        $payload['email']
    )
);


if ($cognitoEmail == '') {

    header('HTTP/1.1 403 Forbidden');

    exit('Cognito email is missing.');
}


if (
    !filter_var(
        $cognitoEmail,
        FILTER_VALIDATE_EMAIL
    )
) {

    header('HTTP/1.1 403 Forbidden');

    exit('Invalid Cognito email address.');
}

include(
    'include/dbcon.php'
);


$db = new DBConn();


$emailEscaped = mysql_real_escape_string($cognitoEmail);

/*
 * STEP 1: Search for a CRM account using email.
 *
 * Important: Do NOT filter by active = 1 here.
 * We need to detect inactive accounts too, otherwise
 * an inactive account could accidentally get duplicated.
 */

$sql = "
    SELECT
        id,
        username,
        email,
        active,
        type
    FROM user
    WHERE LOWER(TRIM(email)) = '" . $emailEscaped . "'
    LIMIT 2
";

$result = mysql_query($sql);

if (!$result) {
    error_log('Cognito CRM user lookup failed: ' . mysql_error());

    header('HTTP/1.1 500 Internal Server Error');
    exit('Unable to complete authentication.');
}

$userCount = mysql_num_rows($result);

/*
 * STEP 2: If no account exists, create one.
 */

if ($userCount === 0) {

    /*
     * Generate a CRM username from the email address.
     * Example: john.doe@example.com -> john.doe
     */

    $usernameBase = strtolower(
        pathinfo($cognitoEmail, PATHINFO_FILENAME)
    );

    // Use the email prefix instead of the filename.
    $emailParts = explode('@', $cognitoEmail);
    $usernameBase = $emailParts[0];

    $usernameBase = preg_replace(
        '/[^a-zA-Z0-9._-]/',
        '',
        $usernameBase
    );

    if ($usernameBase === '') {
        $usernameBase = 'employee';
    }

    $usernameBase = substr($usernameBase, 0, 220);

    /*
     * Ensure the username is unique.
     */

    $username = $usernameBase;
    $counter = 1;

    while (true) {

        $usernameEscaped = mysql_real_escape_string($username);

        $usernameCheck = mysql_query("
            SELECT id
            FROM user
            WHERE username = '" . $usernameEscaped . "'
            LIMIT 1
        ");

        if (!$usernameCheck) {
            error_log('CRM username lookup failed: ' . mysql_error());

            header('HTTP/1.1 500 Internal Server Error');
            exit('Unable to complete authentication.');
        }

        if (mysql_num_rows($usernameCheck) === 0) {
            break;
        }

        $counter++;
        $username = $usernameBase . $counter;
    }

    /*
     * STEP 3: Create the CRM account.
     *
     * Assumptions:
     * - type = 0: regular user
     * - active = 1: active account
     * - allow_property_change = 0: least-privilege default
     * - password = NULL: Cognito handles authentication
     *
     * Confirm these defaults against the existing
     * admin user-creation logic before production.
     */

    $usernameEscaped = mysql_real_escape_string($username);

    $insertSql = "
    INSERT INTO user (
        username,
        password,
        created_date,
        type,
        active,
        allow_property_change,
        created_at,
        updated_at,
        email
    ) VALUES (
        '" . $usernameEscaped . "',
        NULL,
        " . time() . ",
        1,
        1,
        0,
        NOW(),
        NOW(),
        '" . $emailEscaped . "'
    )
";

    $insertResult = mysql_query($insertSql);

    if (!$insertResult) {
        error_log('Cognito CRM account creation failed: ' . mysql_error());

        header('HTTP/1.1 500 Internal Server Error');
        exit('Unable to create your CRM account. Please contact your administrator.');
    }

    $newUserId = mysql_insert_id();

    if (!$newUserId) {
        error_log('Cognito CRM account creation returned no user ID.');

        header('HTTP/1.1 500 Internal Server Error');
        exit('Unable to complete authentication.');
    }

    /*
     * STEP 4: Retrieve the newly created user.
     */

    $newUserSql = "
        SELECT
            id,
            username,
            email,
            active,
            type
        FROM user
        WHERE id = " . (int)$newUserId . "
        LIMIT 1
    ";

    $newUserResult = mysql_query($newUserSql);

    if (!$newUserResult) {
        error_log('Unable to retrieve newly created CRM user: ' . mysql_error());

        header('HTTP/1.1 500 Internal Server Error');
        exit('Unable to complete authentication.');
    }

    $row = mysql_fetch_assoc($newUserResult);

    if (!$row) {
        header('HTTP/1.1 500 Internal Server Error');
        exit('Unable to complete authentication.');
    }

} else {

    /*
     * STEP 5: Existing account found.
     */

    if ($userCount > 1) {
        header('HTTP/1.1 403 Forbidden');

        exit(
            'Multiple CRM users are linked to this email. ' .
            'Please contact your administrator.'
        );
    }

    $row = mysql_fetch_assoc($result);

    if (!$row) {
        header('HTTP/1.1 500 Internal Server Error');
        exit('Unable to complete authentication.');
    }
}

/*
 * STEP 6: Check account status for both existing
 * and newly created users.
 */

if (
    !isset($row['active']) ||
    (int)$row['active'] !== 1
) {
    header('HTTP/1.1 403 Forbidden');

    exit(
        'Your CRM account is inactive. ' .
        'Please contact your administrator.'
    );
}

/*
 * STEP 7: Validate the CRM user ID and username.
 */

if (
    !isset($row['id']) ||
    $row['id'] == '' ||
    !isset($row['username']) ||
    $row['username'] == ''
) {
    header('HTTP/1.1 500 Internal Server Error');
    exit('Unable to complete authentication.');
}

/*
 * Continue with your existing session creation code below.
 */



$result = mysql_query(
    $sql
);


if (!$result) {

    header('HTTP/1.1 500 Internal Server Error');

    exit('Unable to complete authentication.');
}

$userCount = mysql_num_rows(
    $result
);

if ($userCount == 0) {

    header('HTTP/1.1 403 Forbidden');

    exit(
        'Your Cognito account is authenticated, but it is ' .
        'not linked to an active CRM user.'
    );
}

if ($userCount > 1) {

    header('HTTP/1.1 403 Forbidden');

    exit(
        'Multiple CRM users are linked to this email. ' .
        'Please contact your administrator.'
    );
}

$row = mysql_fetch_assoc(
    $result
);


if (!$row) {

    header('HTTP/1.1 500 Internal Server Error');

    exit('Unable to complete authentication.');
}

if (
    !isset($row['active']) ||
    $row['active'] != 1
) {

    header('HTTP/1.1 403 Forbidden');

    exit(
        'Your CRM account is inactive. ' .
        'Please contact your administrator.'
    );
}


if (
    !isset($row['id']) ||
    $row['id'] == ''
) {

    header('HTTP/1.1 500 Internal Server Error');

    exit('Unable to complete authentication.');
}


if (
    !isset($row['username']) ||
    $row['username'] == ''
) {

    header('HTTP/1.1 500 Internal Server Error');

    exit('Unable to complete authentication.');
}

session_regenerate_id(
    true
);

$_SESSION['userid'] =
    $row['id'];

$_SESSION['username'] =
    $row['username'];

$_SESSION['auth_method'] =
    'cognito';

$_SESSION['cognito_sub'] =
    $cognitoSub;

$_SESSION['cognito_email'] =
    $cognitoEmail;

$_SESSION['cognito_authenticated_at'] =
    time();

header(
    'Location: ' . $crmHome,
    true,
    302
);

exit;

?>
