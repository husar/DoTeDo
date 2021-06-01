<?php
include "../connect.ini.php";

$query="SELECT * FROM labels WHERE author=9";
$apply=mysqli_query($nicelabel,$query);
print_r(mysqli_fetch_array($apply));
?>