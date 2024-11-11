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
$typeId = isset($data['typeId']) ? $data['typeId'] : '';
$all = isset($data['all']) ? $data['all'] : '';

$page = isset($data['coverPage']) ? intval($data['coverPage']) : 1;
$limit = isset($data['limit']) ? intval($data['limit']) : 12;

if ($all) {
  $sql = 'SELECT type.type,type.typeEN,cover.id,cover_img.img,cover.theme,cover.time,cover_img.sort,cover.country_from,cover.country_to,cover.remark,type.id as typeId FROM type,cover,cover_img where cover_img.cover_id = cover.id AND cover.type=type.id and cover_img.sort = 1 AND type.id ='.$typeId;
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

  $sql = "SELECT * FROM cover,cover_img where cover_img.cover_id = cover.id AND cover.id =" . $id;
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
  $sql = 'SELECT type.type,type.typeEN,cover.id,cover_img.img,cover.theme,cover.time,cover_img.sort,cover.country_from,cover.country_to,cover.remark,type.id as typeId FROM type,cover,cover_img where cover_img.cover_id = cover.id AND cover.type=type.id and cover_img.sort = 1 AND type.id =' . $typeId . ' LIMIT :limit OFFSET :offset';
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
    $retcode = "找不到關資料";
  }
}

echo json_encode($retcode, JSON_UNESCAPED_UNICODE);
return;
