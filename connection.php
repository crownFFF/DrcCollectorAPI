<?php
    $dsn = "mysql:host=localhost; dbname=drc; charset=utf8";
    $user = "drc";
    $password = "123456";
    $link = new PDO($dsn, $user, $password);
    date_default_timezone_set ("asia/taipei");
?>