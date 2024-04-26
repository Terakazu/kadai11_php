<?php
//$_SESSION使うよ！
session_start();

// ユーザーのIDを取得
$users_id = $_SESSION["id"];

//1. POSTデータ取得
$question1 = $_POST["question1"];
$question2 = $_POST["question2"];
$question3 = $_POST["question3"];
$question4 = $_POST["question4"];
$question5 = $_POST["question5"];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// 画像ファイルの取得
$image_path = $_FILES["image"]["tmp_name"]; // 一時ファイルのパス
$image_name = $_FILES["image"]["name"]; // アップロードされたファイルの名前

// 画像を指定のパスに移動します
$upload_path = "images/" . $image_name;
if (move_uploaded_file($image_path, $upload_path)) {
    echo "画像の移動が成功しました";
} else {
    echo "画像の移動に失敗しました";
}

//2. DB接続します
include("funcs.php");
$pdo = db_conn();

//３．データ登録SQL作成
$sql = "INSERT INTO jd_vision_table(question1,question2,question3,question4,question5,users_id,indate,image_path)VALUES(:question1,:question2,:question3,:question4,:question5,:users_id,sysdate(),:image_path)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':question1', $question1, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':question2', $question2, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':question3', $question3, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':question4', $question4, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':question5', $question5, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':users_id', $users_id, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':image_path', $upload_path, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

//４．データ登録処理後
if ($status == false) {
    sql_error($stmt);
} else {

  //Login成功時（select.phpへ）
redirect("vision_select.php");
}