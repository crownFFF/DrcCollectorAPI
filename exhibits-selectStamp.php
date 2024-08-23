<?php

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
  header('Access-Control-Allow-Headers: Content-Type, Authorization');
  exit;
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type:application/json');

require_once('./connection.php');

$data = json_decode(file_get_contents('php://input'), true);
$country = isset($data['country']) ? $data['country'] : '';
$year = isset($data['year']) ? $data['year'] : '';

if ($country && !$year) {
  $sql = 'SELECT * FROM stamp where country_id=' . $country;
} elseif ($year && !$country) {
  $sql = 'SELECT * FROM stamp where year=' . $year;
} elseif ($country && $year) {
  $sql = 'SELECT * FROM stamp where country_id=' . $country . ' AND year =' . $year;
} elseif (!$country && !$year) {
  $sql = 'SELECT * FROM stamp';
}
$result = $link->query($sql);
$result_num = $result->rowCount();

if ($result_num > 0) {
  $retcode = [];
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $retcode[] = $row;
  }
} else {
  $retcode = false;
}
echo json_encode($retcode, JSON_UNESCAPED_UNICODE);
return;
