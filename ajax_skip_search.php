<?php
mysql_connect('localhost', 'root', 'Securel0gi!12aa');
mysql_select_db("planitlo_locations");
extract($_POST);

$query = mysql_query("UPDATE prop_info SET skip_search=$skip_search WHERE prop_loc='".$id."'");

echo 1;
die;