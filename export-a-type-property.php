<?php

// ===========================================================
// Type A property CSV export
//
// Production-safe:
//  - In the browser, each request works for at most $timeBudget
//    seconds, saves its position and redirects itself to continue,
//    so it never hits the PHP / IIS / proxy timeout (504).
//    If a request is interrupted, reload the page to resume.
//  - From the command line (php export-a-type-property.php) it runs in one go.
// ===========================================================

$isCli = (PHP_SAPI === 'cli');

@set_time_limit(0);
ignore_user_abort(true);
ini_set('memory_limit', '512M');

// ===========================
// Settings
// ===========================
$batchSize  = 1000;  // properties per query
$timeBudget = 20;    // seconds of work per web request (keep below server timeout)
$filePrefix = 'typea_properties_export_';

// ===========================
// Database Configuration
// ===========================
$host        = 'localhost';
$username    = 'root';
$password    = 'Securel0gi!12aa';
$database    = 'planitlo_crm';        // property, state, country, county_new
$locationsDb = 'planitlo_locations';  // sorting

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

// ===========================
// Export File / State
// ===========================
if (!is_dir($exportDir) && !@mkdir($exportDir, 0775, true)) {
    fail("Unable to create export directory. Give the web server user (IIS: IUSR / IIS_IUSRS) write permission.");
}

// State is carried in the URL between web requests
$file   = isset($_GET['file']) ? basename($_GET['file']) : '';
$lastId = isset($_GET['last']) ? (int) $_GET['last'] : 0;
$total  = isset($_GET['total']) ? (int) $_GET['total'] : 0;
$pos    = isset($_GET['pos']) ? (int) $_GET['pos'] : 0;

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
    // ===========================
    fputcsv($fp, array(
        'property_id',
        'address',
        'address2',
        'city',
        'state_name',
        'countyname',
        'countryname',
        'zipcode',
        'categories'
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

while (true) {

    $sql = "
        SELECT
            p.id,
            p.property_id,
            p.address,
            p.address2,
            p.city,
            st.name AS state_name,
            cnty.name as countyname,
            cntry.printable_name as countryname,
            p.zipcode,

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

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        fail(mysqli_error($conn));
    }

    $count = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($fp, array(
            $row['property_id'],
            $row['address'],
            $row['address2'],
            $row['city'],
            $row['state_name'],
            $row['countyname'],
            $row['countryname'],
            $row['zipcode'],
            $row['categories']
        ));

        $lastId = (int) $row['id'];
        $total++;
        $count++;
    }
    mysqli_free_result($result);

    // Checkpoint: everything up to $lastId is on disk
    fflush($fp);
    $pos = ftell($fp);

    if ($isCli) {
        echo "Exported {$total} properties...\n";
    }

    if ($count < $batchSize) {
        $finished = true;
        break;
    }

    // Web: stop and continue in a fresh request before hitting a timeout
    if (!$isCli && (microtime(true) - $startTime) >= $timeBudget) {
        break;
    }
}

fclose($fp);
mysqli_close($conn);

finishRequest($isCli, $finished, $file, $fileName, $lastId, $total, $pos);

// ===========================================================
// Helpers
// ===========================================================

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
function finishRequest($isCli, $finished, $file, $fileName, $lastId, $total, $pos)
{
    if ($isCli) {
        echo "Export completed successfully.\n";
        echo "Total Properties Exported: {$total}\n";
        echo "CSV File: {$fileName}\n";
        exit;
    }

    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: no-store');

    $self = basename($_SERVER['SCRIPT_NAME']);

    if (!$finished) {
        $next = htmlspecialchars($self . '?' . http_build_query(array(
            'file'  => $file,
            'last'  => $lastId,
            'total' => $total,
            'pos'   => $pos,
        )), ENT_QUOTES);

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
