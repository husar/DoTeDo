<?php

$connect = mysqli_connect('localhost','root','','objednavky');
mysqli_query($connect,"set names 'utf8'");
error_reporting(0);

$km = mysqli_connect('srv-webreport','webreport','webreport','kontrola_modulov');
mysqli_query($km,"set names 'utf8'");
error_reporting(0);

$nicelabel = mysqli_connect('srv-nicelabel','nicelabel','nicelabel','packing_app');
mysqli_query($nicelabel,"set names 'utf8'");
error_reporting(0);

?>