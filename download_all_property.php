<?php
set_time_limit(0);
ini_set('memory_limit', '512M');

include('include/security.php');
include('include/dbcon.php');
include('include/config.php');
include('include/functions.php');

$db = new DBConn();

// Create export directory if it doesn't exist
$exportDir = 'exports';

if (!is_dir($exportDir)) {
    mkdir($exportDir, 0777, true);
}

$fileName = $exportDir . '/properties_export_' . date('Ymd_His') . '.csv';

$fp = fopen($fileName, 'w');

if (!$fp) {
    die('Unable to create CSV file.');
}

// CSV Header
fputcsv($fp, array(
    'Property ID',
    'Address',
    'Address 2',
    'Neighborhood',
    'City',
    'State',
    'ZIP code',
    'Latitude',
    'Longitude',
    'Company',
    'Film permit information',
    'Owner/contact name',
    'Email address',
    'Phone number',
    'Neighborhood classification',
    'Direct link to the CRM property record',
    'Optional Google Maps link',
    'Audit status or checkbox',
    'Notes',
    'Date reviewed',
    'Reviewed by'
));

$limit = 5;
$lastId = 0;
$totalExported = 0;

while (true) {

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
        p.company,
        p.permit_type
    FROM property p
    LEFT JOIN state st ON st.id = p.state
    WHERE p.id > ".$lastId."
    ORDER BY p.id ASC
    LIMIT ".$limit;

    $result = $db->query($sql) or die(mysql_error()."<br>".$sql);

    if ($db->getNumRows($result) == 0) {
        break;
    }

    while ($row = $db->fetchArray($result)) {

        $lastId = $row['id'];
        $totalExported++;

        $contactName = '';
        $email = '';
        $phone = '';
        $notesString = '';

        // Fetch latest contact for this property
        $sqlContact = "
            SELECT c.*
            FROM contact_to_property cp
            INNER JOIN contact c ON cp.contact_id = c.id
            WHERE cp.property_id = ".$row['id']."
            ORDER BY c.id DESC
            LIMIT 1";
        
        $sqlNotes = "SELECT
    GROUP_CONCAT(
        CONCAT('[', datetime, '] ', text)
        ORDER BY datetime
        SEPARATOR ', '
    ) AS notes
FROM notes
WHERE property_id = ".$row['id'];

        $cresult = $db->query($sqlContact);
        $notesResult = $db->query($sqlContact);

        if ($crow = $db->fetchArray($cresult)) {

            $contactName = trim($crow['firstname'].' '.$crow['lastname']);
            $email = $crow['email'];

            // Fetch highest priority phone
            $phoneSql = "
                SELECT
                    phone.number,
                    phone.ext,
                    types.name AS typename
                FROM phone
                LEFT JOIN types ON phone.type = types.id
                WHERE phone.contact_id = ".$crow['id']."
                  AND phone.number <> ''
                ORDER BY phone.priority ASC
                LIMIT 1";

            $phoneResult = $db->query($phoneSql);

            if ($phoneRow = $db->fetchArray($phoneResult)) {

                if ($phoneRow['typename'] != '') {
                    $phone .= $phoneRow['typename'].': ';
                }

                $phone .= $phoneRow['number'];

                if ($phoneRow['ext'] != '' && $phoneRow['ext'] != '0') {
                    $phone .= ' Ext '.$phoneRow['ext'];
                }
            }
        }

        if ($notesRow = $db->fetchArray($notesResult)) {
            $notesString = $notesRow['notes'];
        }
        $createdAt = '';
        if (is_numeric($row['created_date']) && $row['created_date'] > 0) {
            $createdAt = date('M/d/Y', (int) $row['created_date']);
        }

        fputcsv($fp, array(
            $row['property_id'],
            $row['address'],
            $row['address2'],
            $row['neighborhood'],
            $row['city'],
            $row['state_name'],
            $row['zipcode'],
            $row['lat'],
            $row['lng'],
            $row['company'],
            $row['permit_type'],
            $contactName,
            $email,
            $phone,
            $createdAt,
            '', // Neighborhood classification
            '', // Direct link to CRM property record
            '', // Optional Google Maps link
            '', // Audit status or checkbox
            $notesString, // Notes
            '', // Date reviewed
            ''  // Reviewed by
        ));
    }

    echo "Exported: ".$totalExported." properties...<br>";
    flush();
}

fclose($fp);

echo "<br><strong>Export completed successfully.</strong><br>";
echo "Total properties exported: ".$totalExported."<br>";
echo "CSV saved to: ".$fileName;