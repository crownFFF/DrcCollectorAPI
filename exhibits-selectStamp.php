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
$yearlist = isset($data['yearlist']) ? $data['yearlist'] : '';

$sql = 'SELECT * FROM stamp where 1=1';
$params = []; 

if (!empty($yearlist)) {
  if (!empty($yearlist[0]) && !empty($yearlist[1])) {
    $sql .= ' AND year >= :year_start AND year <= :year_end';
    $params[':year_start'] = $yearlist[0];
    $params[':year_end'] = $yearlist[1];
  } elseif (!empty($yearlist[0])) {
    $sql .= ' AND year >= :year_start';
    $params[':year_start'] = $yearlist[0];
  } elseif (!empty($yearlist[1])) {
    $sql .= ' AND year <= :year_end';
    $params[':year_end'] = $yearlist[1];
  }
} else {
  $sql .= '';
}

if (!empty($country)) {
  $sql .= ' AND country_id = :country_id';
  $params[':country_id'] = $country;
}

$stmt = $link->prepare($sql);
$stmt->execute($params);

$result_num = $stmt->rowCount();

if ($result_num > 0) {
    $retcode = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $retcode = false;
}

echo json_encode($retcode, JSON_UNESCAPED_UNICODE);
return;