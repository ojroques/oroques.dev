<?php
require './credentials.php';
$cell = " <td>%s</td> ";
$months = array(
    '00' => '',
    '01' => 'Jan',
    '02' => 'Feb',
    '03' => 'Mar',
    '04' => 'Apr',
    '05' => 'May',
    '06' => 'Jun',
    '07' => 'Jul',
    '08' => 'Aug',
    '09' => 'Sep',
    '10' => 'Oct',
    '11' => 'Nov',
    '12' => 'Dec',
);

function buildTable($full = False) {
    global $host, $user, $password, $database, $cell, $months;
    $db = new mysqli($host, $user, $password, $database);
    if ($db->connect_error) {
        die("Connection failed: ".$db->connect_error);
    }
    $db->set_charset('utf8');
    if ($full) {
        $query = "SELECT * FROM movies_complete UNION SELECT * FROM movies_incomplete";
    } else {
        $query = "SELECT * FROM movies_complete";
    }
    $table = $db->query($query);

    while ($entry = $table->fetch_assoc()) {
        $release_array =  explode("-", $entry['release_date']);
        $seen_array =  explode("-", $entry['seen_date']);
        $release_date = $months[$release_array[1]].' '.$release_array[0];
        if ($seen_array[0] === '0000') {
            $seen_date = '';
        } else {
            $seen_date = $months[$seen_array[1]].' '.$seen_array[0];
        }
        echo "<tr>";
        printf($cell, $entry['original_title']);
        printf($cell, $entry['french_title']);
        printf($cell, $entry['film_director']);
        printf($cell, $entry['country']);
        printf($cell, $release_date);
        printf($cell, $seen_date);
        if ($full) {
            $note = $entry['rating'];
            printf($cell, '<span class="note'.$note.'">'.$note.'</span>');
        }
        echo "</tr>\n";
    }
    $db->close();
}
