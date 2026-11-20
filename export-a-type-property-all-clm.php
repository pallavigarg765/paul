<?php

// ===========================================================
// Type A property CSV export, with Google reverse-geocoded address
//
// Production-safe:
//  - In the browser, each request works for at most $timeBudget
//    seconds, saves its position and redirects itself to continue,
//    so it never hits the PHP / IIS / proxy timeout (504).
//    If a request is interrupted, reload the page to resume.
//  - From the command line (php index.php) it runs in one go.
//  - Contacts, phones, notes and photo links are loaded once per
//    batch instead of with several queries per property.
// ===========================================================

$isCli = (PHP_SAPI === 'cli');

@set_time_limit(0);
ignore_user_abort(true);
ini_set('memory_limit', '512M');

// ===========================
// Settings
// ===========================
$batchSize     = 100;  // properties per query
$timeBudget    = 20;   // seconds of work per web request (keep below server timeout)
$googleTimeout = 10;   // seconds to wait for one Google lookup
$filePrefix    = 'typea_properties_export_';

// ===========================
// Database Configuration
// ===========================
$host        = 'localhost';
$username    = 'root';
$password    = 'Securel0gi!12aa';
$database    = 'planitlo_crm';        // property, state, country, county_new, contact, contact_to_property, phone, types, notes
$locationsDb = 'planitlo_locations';  // prop_info, sorting

// Same Google Maps key used in editproperty.php (Places Autocomplete)
$googleApiKey = 'AIzaSyAFdZnHYzzmdWtQtvqBi5KobK2HzmpyrOQ';

// Cache reverse-geocode results so duplicate lat/lng pairs are not requested twice
$googleAddressCache = array();

$exportDir = __DIR__ . '/exports2';

// ===========================
// Download a finished export
// (IIS returns 404 for .csv files, so the file is sent through PHP)
// ===========================
if (!$isCli && isset($_GET['download'])) {
    sendExport($exportDir, $_GET['download'], $filePrefix);
}

// ===========================
// Connect to MySQL
// ===========================
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    fail("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
mysqli_query($conn, "SET SESSION group_concat_max_len = 1000000");

if (!ini_get('allow_url_fopen')) {
    fail('PHP setting allow_url_fopen is Off. Enable it in php.ini and restart the web server so Google API calls can work.');
}
if (!in_array('https', stream_get_wrappers())) {
    fail('PHP HTTPS wrapper is not available. Enable the openssl extension in php.ini and restart the web server.');
}

// ===========================
// Export File / State
// ===========================
if (!is_dir($exportDir) && !@mkdir($exportDir, 0775, true)) {
    fail("Unable to create export directory. Give the web server user (IIS: IUSR / IIS_IUSRS) write permission.");
}

// State is carried in the URL between web requests
$file          = isset($_GET['file']) ? basename($_GET['file']) : '';
$lastId        = isset($_GET['last']) ? (int) $_GET['last'] : 0;
$total         = isset($_GET['total']) ? (int) $_GET['total'] : 0;
$pos           = isset($_GET['pos']) ? (int) $_GET['pos'] : 0;
$googleLookups = isset($_GET['google']) ? (int) $_GET['google'] : 0;

$isNew = ($file === '');

if ($isNew) {
    $file = $filePrefix . date('Ymd_His') . '.csv';
} elseif (!isExportFileName($file, $filePrefix)) {
    fail("Invalid export file.");
}

$fileName = $exportDir . '/' . $file;

$fp = @fopen($fileName, $isNew ? 'w' : 'c');

if (!$fp) {
    fail("Unable to create CSV file. Give the web server user (IIS: IUSR / IIS_IUSRS) write permission on exports2.");
}

if ($isNew) {
    // ===========================
    // CSV Header
    // Google columns sit next to CRM fields so you can compare them in the spreadsheet.
    // ===========================
    fputcsv($fp, array(
        'Property ID',
        'Crated date',
        'Address',
        'Address 2',
        'Neighborhood',
        'Google Neighborhood',
        'City',
        'Google City',
        'State',
        'Google State',
        'County',
        'Google County',
        'Country',
        'Google Country',
        'ZIP code',
        'Google ZIP code',
        'Latitude',
        'Longitude',
        'Company',
        'Film permit information',
        'Owner/contact name',
        'Email address',
        'Phone number',
        'Categories',
        'Neighborhood classification',
        'Direct link to the CRM property record',
        'Website photos link',
        'Optional Google Maps link',
        'Audit status or checkbox',
        'Notes',
        'Date reviewed',
        'Reviewed by'
    ));
} else {
    // Drop anything written after the last saved checkpoint
    // (e.g. a previous request was killed mid-batch)
    ftruncate($fp, $pos);
    fseek($fp, $pos);
}

// ===========================
// Export Properties
// ===========================
$startTime = microtime(true);
$finished  = false;
$outOfTime = false;

while (!$outOfTime) {

    $sql = "
        SELECT
            p.id,
            p.property_id,
            p.address,
            p.address2,
            p.neighborhood,
            p.city,
            st.name AS state_name,
            p.zipcode,
            p.lat,
            p.lng,
            p.created_date,
            p.company,
            p.permit_type,
            cnty.name as countyname,
            cntry.printable_name as countryname,

            (
                SELECT GROUP_CONCAT(DISTINCT s.category1 ORDER BY s.category1 SEPARATOR ', ')
                FROM {$locationsDb}.sorting s
                WHERE s.prop_loc = p.property_id
                AND s.category1 NOT LIKE '%-z'
                AND s.category1 NOT LIKE '%-prv'
                AND s.category1 NOT LIKE '%-b'
                AND s.category1 NOT LIKE '%-c'
                AND s.category1 NOT LIKE '%-d'
            ) AS categories

        FROM property p
        LEFT JOIN state st
            ON st.id = p.state
        left join country as cntry on p.country=cntry.id
        left join county_new as cnty on p.county=cnty.id
        WHERE p.id > {$lastId}
        AND p.property_id IN (
            SELECT DISTINCT(prop_loc)
            FROM {$locationsDb}.sorting
            WHERE category1 NOT LIKE '%-z'
            AND category1 NOT LIKE '%-prv'
            AND category1 NOT LIKE '%-b'
            AND category1 NOT LIKE '%-c'
            AND category1 NOT LIKE '%-d'
        )

        ORDER BY p.id ASC
    LIMIT {$batchSize}";

    $rows = fetchAll($conn, $sql);

    if (empty($rows)) {
        $finished = true;
        break;
    }

    // ==========================================
    // Notes per property
    // ==========================================
    $notes = array();
    foreach (fetchAll($conn, "
        SELECT
            property_id,
            GROUP_CONCAT(
                text
                ORDER BY datentime
                SEPARATOR ', '
            ) AS notes
        FROM notes
        WHERE property_id IN (" . idList($rows) . ")
        GROUP BY property_id") as $notesRow) {
        $notes[$notesRow['property_id']] = $notesRow['notes'];
    }

    $photos   = loadPhotoLinks($conn, $rows, $locationsDb);
    $contacts = loadContacts($conn, $rows);

    // ==========================================
    // Write rows
    // ==========================================
    foreach ($rows as $row) {

        $contactName = '';
        $email = '';
        $phone = '';

        if (isset($contacts[$row['id']])) {
            $contactName = $contacts[$row['id']]['name'];
            $email       = $contacts[$row['id']]['email'];
            $phone       = $contacts[$row['id']]['phone'];
        }

        $photoUrlF   = isset($photos[$row['property_id']]) ? $photos[$row['property_id']] : '';
        $notesString = isset($notes[$row['id']]) ? $notes[$row['id']] : '';

        $createdAt = '';
        if (is_numeric($row['created_date']) && $row['created_date'] > 0) {
            $createdAt = date('M/d/Y', (int) $row['created_date']);
        }

        $google = getGoogleAddressFromLatLng($row['lat'], $row['lng'], $googleApiKey, $googleAddressCache, $googleTimeout);
        if ($google['formatted'] !== '') {
            $googleLookups++;
        }

        fputcsv($fp, array(
            $row['property_id'],
            $createdAt,
            $row['address'],
            $row['address2'],
            $row['neighborhood'],
            $google['neighborhood'],
            $row['city'],
            $google['city'],
            $row['state_name'],
            $google['state'],
            $row['countyname'],
            $google['county'],
            $row['countryname'],
            $google['country'],
            $row['zipcode'],
            $google['zipcode'],
            $row['lat'],
            $row['lng'],
            $row['company'],
            $row['permit_type'],
            $contactName,
            $email,
            $phone,
            $row['categories'],
            '',
            'https://planitlocations.com/crm/propertydetail.php?id='.$row['id'],
            $photoUrlF,
            mapLink($row),
            '',
            $notesString,
            '',
            ''
        ));

        $lastId = (int) $row['id'];
        $total++;

        // Web: stop and continue in a fresh request before hitting a timeout
        if (!$isCli && (microtime(true) - $startTime) >= $timeBudget) {
            $outOfTime = true;
            break;
        }
    }

    // Checkpoint: everything up to $lastId is on disk
    fflush($fp);
    $pos = ftell($fp);

    if ($isCli) {
        echo "Exported {$total} properties...\n";
    }

    if (!$outOfTime && count($rows) < $batchSize) {
        $finished = true;
    }

    if ($finished) {
        break;
    }
}

fclose($fp);
mysqli_close($conn);

finishRequest(
    $isCli, $finished, $file, $fileName, $lastId, $total, $pos,
    array('google' => $googleLookups),
    array("Google address lookups with a result: {$googleLookups}")
);

// ===========================================================
// Helpers
// ===========================================================

/**
 * Empty Google address columns (used when lat/lng is missing or lookup fails).
 */
function emptyGoogleAddress() {
    return array(
        'address'      => '',
        'neighborhood' => '',
        'city'         => '',
        'state'        => '',
        'county'       => '',
        'country'      => '',
        'zipcode'      => '',
        'formatted'    => '',
    );
}

/**
 * Reverse-geocode lat/lng via Google Geocoding API.
 * Address parts match editproperty.php Places Autocomplete:
 *   street_number (short) + route (long) = Address
 *   locality (long)                       = City
 *   administrative_area_level_1 (long)   = State
 *   country (long)                        = Country
 *   postal_code (short)                   = ZIP
 * Extra fields for comparison:
 *   neighborhood / sublocality           = Neighborhood
 *   administrative_area_level_2          = County
 */
function getGoogleAddressFromLatLng($lat, $lng, $apiKey, &$cache, $timeout) {
    $result = emptyGoogleAddress();
    $result['status'] = '';
    $result['url'] = '';

    $lat = trim((string) $lat);
    $lng = trim((string) $lng);

    if ($lat === '' || $lng === '' || $lat === '0' || $lng === '0') {
        return $result;
    }

    $cacheKey = $lat . ',' . $lng;
    if (isset($cache[$cacheKey])) {
        return $cache[$cacheKey];
    }

    $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lng . '&key=' . $apiKey;
    $result['url'] = $url;

    $context = stream_context_create(array(
        'http' => array(
            'method'        => 'GET',
            'timeout'       => $timeout,
            'ignore_errors' => true,
            'header'        => "User-Agent: Mozilla/5.0\r\n"
        ),
        'ssl' => array(
            'verify_peer'      => false,
            'verify_peer_name' => false
        )
    ));

    $response = @file_get_contents($url, false, $context);
    $json = json_decode($response, true);

    if ($response === false || $response === '') {
        $result['status'] = 'NO_RESPONSE';
        $cache[$cacheKey] = $result;
        return $result;
    }

    if (!is_array($json)) {
        $result['status'] = 'INVALID_JSON';
        $cache[$cacheKey] = $result;
        return $result;
    }

    $result['status'] = isset($json['status']) ? $json['status'] : '';

    if (empty($json['results'][0])) {
        $cache[$cacheKey] = $result;
        return $result;
    }

    $place = $json['results'][0];
    $components = isset($place['address_components']) ? $place['address_components'] : array();

    // Same name style as editproperty.php componentForm
    $componentForm = array(
        'street_number'               => 'short_name',
        'route'                     => 'long_name',
        'locality'                   => 'long_name',
        'administrative_area_level_1' => 'long_name',
        'country'                    => 'long_name',
        'postal_code'                => 'short_name',
        'neighborhood'               => 'long_name',
        'sublocality'                => 'long_name',
        'sublocality_level_1'        => 'long_name',
        'administrative_area_level_2' => 'long_name',
        'postal_town'                => 'long_name',
    );

    $parsed = array();
    foreach ($components as $component) {
        if (empty($component['types'][0])) {
            continue;
        }
        $addressType = $component['types'][0];
        if (!isset($componentForm[$addressType])) {
            continue;
        }
        $nameKey = $componentForm[$addressType];
        if (!empty($component[$nameKey])) {
            $parsed[$addressType] = $component[$nameKey];
        }
    }

    $street = '';
    if (!empty($parsed['street_number'])) {
        $street = $parsed['street_number'];
    }
    if (!empty($parsed['route'])) {
        $street = trim($street . ' ' . $parsed['route']);
    }

    $city = '';
    if (!empty($parsed['locality'])) {
        $city = $parsed['locality'];
    } elseif (!empty($parsed['postal_town'])) {
        $city = $parsed['postal_town'];
    } elseif (!empty($parsed['sublocality_level_1'])) {
        $city = $parsed['sublocality_level_1'];
    } elseif (!empty($parsed['sublocality'])) {
        $city = $parsed['sublocality'];
    }

    $neighborhood = '';
    if (!empty($parsed['neighborhood'])) {
        $neighborhood = $parsed['neighborhood'];
    } elseif (!empty($parsed['sublocality']) && $parsed['sublocality'] !== $city) {
        $neighborhood = $parsed['sublocality'];
    } elseif (!empty($parsed['sublocality_level_1']) && $parsed['sublocality_level_1'] !== $city) {
        $neighborhood = $parsed['sublocality_level_1'];
    }

    $result['address']      = $street;
    $result['neighborhood'] = $neighborhood;
    $result['city']         = $city;
    $result['state']        = isset($parsed['administrative_area_level_1']) ? $parsed['administrative_area_level_1'] : '';
    $result['county']       = isset($parsed['administrative_area_level_2']) ? $parsed['administrative_area_level_2'] : '';
    $result['country']      = isset($parsed['country']) ? $parsed['country'] : '';
    $result['zipcode']      = isset($parsed['postal_code']) ? $parsed['postal_code'] : '';
    $result['formatted']    = isset($place['formatted_address']) ? $place['formatted_address'] : '';

    $cache[$cacheKey] = $result;

    return $result;
}

function fetchAll($conn, $sql)
{
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        fail(mysqli_error($conn));
    }
    $rows = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    mysqli_free_result($result);
    return $rows;
}

function idList($rows)
{
    $ids = array();
    foreach ($rows as $row) {
        $ids[] = (int) $row['id'];
    }
    return implode(',', $ids);
}

/**
 * Website photo link per property_id (first prop_info match, like the per-row lookup).
 */
function loadPhotoLinks($conn, $rows, $locationsDb)
{
    $propLocs = array();
    foreach ($rows as $row) {
        if ($row['property_id'] !== null && $row['property_id'] !== '') {
            $propLocs[] = "'" . mysqli_real_escape_string($conn, $row['property_id']) . "'";
        }
    }

    $photos = array();
    if (empty($propLocs)) {
        return $photos;
    }

    foreach (fetchAll($conn, "
        SELECT prop_loc, prop_domain_loc
        FROM {$locationsDb}.prop_info
        WHERE prop_loc IN (" . implode(',', array_unique($propLocs)) . ")") as $rowThumbs) {

        if (isset($photos[$rowThumbs['prop_loc']])) {
            continue;
        }
        $photos[$rowThumbs['prop_loc']] = '';
        if ($rowThumbs['prop_domain_loc'] != "" && $rowThumbs['prop_loc'] != "") {
            $photos[$rowThumbs['prop_loc']] = $rowThumbs['prop_domain_loc'].$rowThumbs['prop_loc'].'/index.php?id='.$rowThumbs['prop_loc'].'&id2=&id3=&id4=&id5=&id6=&id7=&id8=&id9=&id10=';
        }
    }

    return $photos;
}

/**
 * Latest contact per property, with that contact's highest priority phone.
 * Returns array(property id => array('name', 'email', 'phone')).
 */
function loadContacts($conn, $rows)
{
    $latest = array();
    foreach (fetchAll($conn, "
        SELECT cp.property_id,
               c.id,
               c.firstname,
               c.lastname,
               c.email
        FROM contact_to_property cp
        INNER JOIN contact c
            ON c.id = cp.contact_id
        WHERE cp.property_id IN (" . idList($rows) . ")
        ORDER BY cp.property_id, c.id DESC") as $contact) {

        if (!isset($latest[$contact['property_id']])) {
            $latest[$contact['property_id']] = $contact;
        }
    }

    if (empty($latest)) {
        return array();
    }

    $contactIds = array();
    foreach ($latest as $contact) {
        $contactIds[] = (int) $contact['id'];
    }

    $phones = array();
    foreach (fetchAll($conn, "
        SELECT
            p.contact_id,
            p.number,
            p.ext,
            t.name AS phone_type
        FROM phone p
        LEFT JOIN types t
            ON t.id = p.type
        WHERE p.contact_id IN (" . implode(',', array_unique($contactIds)) . ")
          AND p.number <> ''
        ORDER BY p.contact_id, p.priority ASC") as $phoneRow) {

        if (isset($phones[$phoneRow['contact_id']])) {
            continue;
        }

        $phone = '';

        if (!empty($phoneRow['phone_type'])) {
            $phone .= $phoneRow['phone_type'] . ': ';
        }

        $phone .= $phoneRow['number'];

        if (!empty($phoneRow['ext']) && $phoneRow['ext'] != '0') {
            $phone .= ' Ext ' . $phoneRow['ext'];
        }

        $phones[$phoneRow['contact_id']] = $phone;
    }

    $contacts = array();
    foreach ($latest as $propertyId => $contact) {
        $contacts[$propertyId] = array(
            'name'  => trim($contact['firstname'].' '.$contact['lastname']),
            'email' => $contact['email'],
            'phone' => isset($phones[$contact['id']]) ? $phones[$contact['id']] : '',
        );
    }

    return $contacts;
}

function mapLink($row)
{
    $mapLink = '';
    $addressArr = array();
    if ($row['address']!='0' && trim($row['address']) != '') {
        $addressArr[] = $row['address'];
        $addressArr[] = $row['city'];
        $addressArr[] = $row['state_name'];
        $addressArr = array_filter($addressArr);
        $addressTxt = implode(', ', $addressArr);
        $mapLink = 'https://www.google.com/maps/dir/'.urlencode($addressTxt).'/@';
        if(!empty($row['lat']) && !empty($row['lng'])){
            $mapLink = $mapLink.$row['lat'].','.$row['lng'].',17z';
        }
    }
    return $mapLink;
}

function isExportFileName($file, $prefix)
{
    return (bool) preg_match('/^' . preg_quote($prefix, '/') . '\d{8}_\d{6}\.csv$/', $file);
}

function sendExport($exportDir, $file, $prefix)
{
    $file = basename($file);
    $path = $exportDir . '/' . $file;

    if (!isExportFileName($file, $prefix) || !is_file($path)) {
        header('HTTP/1.1 404 Not Found');
        die('Export file not found.');
    }

    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $file . '"');
    header('Content-Length: ' . filesize($path));
    header('Cache-Control: no-store');
    readfile($path);
    exit;
}

/**
 * CLI: print the summary. Web: redirect to the next chunk, or show the download link.
 */
function finishRequest($isCli, $finished, $file, $fileName, $lastId, $total, $pos, $extraState, $extraLines)
{
    if ($isCli) {
        echo "Export completed successfully.\n";
        echo "Total Properties Exported: {$total}\n";
        foreach ($extraLines as $line) {
            echo $line . "\n";
        }
        echo "CSV File: {$fileName}\n";
        exit;
    }

    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: no-store');

    $self = basename($_SERVER['SCRIPT_NAME']);

    if (!$finished) {
        $next = htmlspecialchars($self . '?' . http_build_query(array_merge(array(
            'file'  => $file,
            'last'  => $lastId,
            'total' => $total,
            'pos'   => $pos,
        ), $extraState)), ENT_QUOTES);

        echo "<!doctype html><html><head><meta charset=\"utf-8\">";
        echo "<meta http-equiv=\"refresh\" content=\"0;url={$next}\">";
        echo "<title>Exporting...</title></head><body>";
        echo "Exported {$total} properties... continuing.<br>";
        echo "If this page does not continue automatically, <a href=\"{$next}\">click here</a>.";
        echo "</body></html>";
        exit;
    }

    $fileHtml     = htmlspecialchars($file, ENT_QUOTES);
    $downloadHtml = htmlspecialchars($self . '?download=' . urlencode($file), ENT_QUOTES);

    echo "<!doctype html><html><head><meta charset=\"utf-8\"><title>Export complete</title></head><body>";
    echo "<strong>Export completed successfully.</strong><br>";
    echo "Total Properties Exported: {$total}<br>";
    foreach ($extraLines as $line) {
        echo htmlspecialchars($line, ENT_QUOTES) . "<br>";
    }
    echo "CSV File: <a href=\"{$downloadHtml}\">{$fileHtml}</a>";
    echo "</body></html>";
    exit;
}

function fail($message)
{
    if (PHP_SAPI !== 'cli') {
        $message = htmlspecialchars($message, ENT_QUOTES);
    }
    die($message);
}
