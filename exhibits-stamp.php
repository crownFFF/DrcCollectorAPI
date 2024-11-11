<?php

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header("Access-Control-Allow-Origin:*");
  header("Access-Control-Allow-Headers: contenttype, X-Requested-With, Content-Type, Accept");
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  exit;
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type:application/json');

require_once('./connection.php');
$data = json_decode(file_get_contents('php://input'), true);
$id = isset($data['id']) ? $data['id'] : '';
$page = isset($data['page']) ? intval($data['page']) : 1;
$limit = isset($data['limit']) ? intval($data['limit']) : 12;
$all = isset($data['all']) ? $data['all'] : '';

if ($all) {
  $sql = 'SELECT * FROM stamp';
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
}

if ($id) {
  $sql = 'SELECT * FROM stamp where id =' . $id;
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
} else {
  $offset = ($page - 1) * $limit;
  $sql = 'SELECT * FROM stamp LIMIT :limit OFFSET :offset';
  $stmt = $link->prepare($sql);
  $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
  $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();

  $result_num = $stmt->rowCount();

  if ($result_num > 0) {
    $retcode = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $retcode[] = $row;
    }
  } else {
    $retcode = "找不到相關資料";
  }
}

echo json_encode($retcode, JSON_UNESCAPED_UNICODE);
return;
