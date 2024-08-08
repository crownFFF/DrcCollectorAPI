<?php
// 允许来自任何来源的请求（可以根据需要限制特定的来源）
header('Access-Control-Allow-Origin: *');

// 允许的请求方法
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

// 允许的请求头
header('Access-Control-Allow-Headers: Content-Type, Authorization');

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