<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type:application/json');

require_once('./connection.php');

$data = json_decode(file_get_contents('php://input'), true);
$country_from = isset($data['country_from']) ? $data['country_from'] : '';
$country_to = isset($data['country_to']) ? $data['country_to'] : '';


$sql = "SELECT id,c_name FROM country WHERE id =" . $country_from;

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
if (!empty($country_to)) {
  $sql = "SELECT id,c_name FROM country WHERE id = " . $country_to;

  $result = $link->query($sql);
  if ($result) {
    $result_num = $result->rowCount();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      array_push($retcode, $row);
    }
  } else {
    $result = "";
    array_push($retcode, $result);
  }
}

echo json_encode($retcode, JSON_UNESCAPED_UNICODE);
return;
