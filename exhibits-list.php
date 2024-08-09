<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type:application/json');

require_once('./connection.php');

$sql = 'SELECT * FROM type';
$result = $link->query($sql);

$result_num = $result->rowCount();

if ($result_num > 0) {
  $retcode = [];
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $retcode[] = $row;
  }
} else {
  $retcode = "找不到相關資料";
}

echo json_encode($retcode, JSON_UNESCAPED_UNICODE);
return;