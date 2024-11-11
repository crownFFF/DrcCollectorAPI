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
$countrylist = isset($data['countrylist']) ? $data['countrylist'] : '';
$yearlist = isset($data['yearlist']) ? $data['yearlist'] : '';
$theme = isset($data['theme']) ? $data['theme'] : '';
$typeId = isset($data['typeId']) ? $data['typeId'] : '';

$sql = 'SELECT type.type,type.typeEN,cover.id,cover_img.img,cover.theme,cover.time,cover_img.sort,cover.country_from,cover.country_to,type.id as typeId FROM type,cover,cover_img where cover_img.cover_id = cover.id AND cover.type=type.id and cover_img.sort = 1 AND type.id = '.$typeId.' and 1=1';
$params = [];

if (!empty($countrylist)) {
  if (!empty($countrylist[0]) && !empty($countrylist[1])) {
    $sql .= ' AND country_from =:country_from AND country_to = :country_to';
    $params[':country_from'] = $countrylist[0];
    $params[':country_to'] = $countrylist[1];
  } elseif (!empty($countrylist[0])&& empty($countrylist[1])) {
    $sql .= ' AND country_from =:country_from';
    $params[':country_from'] = $countrylist[0];
  } elseif (empty($countrylist[0])&& !empty($countrylist[1])) {
    $sql .= ' AND country_to =:country_to';
    $params[':country_to'] = $countrylist[0];
  }
}

if (!empty($yearlist)) {
  if (!empty($yearlist[0]) && !empty($yearlist[1])) {
    $sql .= ' AND time >= :year_start AND time <= :year_end';
    $params[':year_start'] = $yearlist[0];
    $params[':year_end'] = $yearlist[1];
  } elseif (!empty($yearlist[0])) {
    $sql .= ' AND time >= :year_start';
    $params[':year_start'] = $yearlist[0];
  } elseif (!empty($yearlist[1])) {
    $sql .= ' AND time <= :year_end';
    $params[':year_end'] = $yearlist[1];
  }
} else {
  $sql .= '';
}

if ($theme == 'null') {
  $sql .= ' AND theme is null';
} elseif (!empty($theme)) {
  $sql .= ' AND theme = :theme';
  $params[':theme'] = $theme;
}

$stmt = $link->prepare($sql);
$stmt->execute($params);

$result_num = $stmt->rowCount();

if ($result_num > 0) {
  $retcode = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
  $retcode = false;
}

error_log($sql);
error_log(print_r($params, true));

echo json_encode($retcode, JSON_UNESCAPED_UNICODE);
return;
