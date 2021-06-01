<?php

$km = mysqli_connect('srv-webreport','webreport','webreport','kontrola_modulov');
mysqli_query($km,"set names 'utf8'");
error_reporting(0);


$query="SELECT * FROM moduly WHERE id = '1'";
$apply_zaznamy=mysqli_query($km,$query);
$result_zaznamy=mysqli_fetch_array($apply_zaznamy);
echo $result_zaznamy['vp'];
?>