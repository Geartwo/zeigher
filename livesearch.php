<?php
include 'all.php';
$dbquery = $db->query("SELECT * FROM files WHERE name LIKE '%1%'");
echo '<hl>';
while ($row = $dbquery->fetch_assoc()){
echo'<li>'.$row['name'].'</li>';
}
echo '</hl>';
