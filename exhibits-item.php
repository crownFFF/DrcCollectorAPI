<?php
// 允许来自任何来源的请求（可以根据需要限制特定的来源）
header('Access-Control-Allow-Origin: *');

// 允许的请求方法
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

// 允许的请求头
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once('./connection.php');

$data = json_decode(file_get_contents('php://input'), true);
$id = isset($data['id']) ? $data['id'] : '';

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
  $sql = 'SELECT type.type,type.typeEN,cover.id,cover_img.img,cover.theme,cover.time,cover_img.sort FROM type,cover,cover_img where cover_img.cover_id = cover.id AND cover.type=type.id and cover_img.sort = 1';
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
}

echo json_encode($retcode, JSON_UNESCAPED_UNICODE);
return;
