<?php

$dsn = "mysql:host=localhost;dbname=fongminb_collect;charset=utf8";
$user = "root";
$password = "";

$link = new PDO($dsn, $user, $password);

date_default_timezone_set("Asia/Taipei");