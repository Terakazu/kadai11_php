<?php

session_start();

// ユーザーのIDを取得
$users_id = $_SESSION["id"];


//1. POSTデータ取得
$answers = $_POST["answers"];
$q_id    = $_POST["q_id"];


// 2. DB接続します
include("funcs.php");
$pdo = db_conn();

//３．データ登録SQL作成
$sql = "INSERT INTO jd_answer_table(answers,q_id,users_id,date) VALUES(:answers,:q_id,:users_id,sysdate())";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':answers', $answers, PDO::PARAM_STR);  //String型としてバインド
$stmt->bindValue(':users_id', $users_id, PDO::PARAM_INT);  //Integer型としてバインド
$stmt->bindValue(':q_id', $q_id, PDO::PARAM_INT);  //Integer型としてバインド
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
  //*** function化する！*****************
  sql_error($stmt);
} else {
  //*** function化する！*****************
  // redirect 関数の定義が必要です。適切な関数を使ってページのリダイレクトを行ってください。
  // 例えば、header 関数を使う方法：
  header("Location: answer.php");
  exit;
}

?>
