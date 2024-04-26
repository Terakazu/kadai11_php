<?php
//$_SESSION使うよ！
session_start();

//※htdocsと同じ階層に「includes」を作成してfuncs.phpを入れましょう！
//include "../../includes/funcs.php";
include "funcs.php";
// sschk();

//1. POSTデータ取得
$name      = filter_input( INPUT_POST, "name" );
$email     = filter_input( INPUT_POST, "email");
$lid       = filter_input( INPUT_POST, "lid" );
$lpw       = filter_input( INPUT_POST, "lpw" );
$birthdate = filter_input( INPUT_POST, "birthdate");
$kanri_flg = filter_input( INPUT_POST, "kanri_flg" );
$lpw       = password_hash($lpw, PASSWORD_DEFAULT);   //パスワードハッシュ化

//2. DB接続します
$pdo = db_conn();

//３．データ登録SQL作成
$sql = "INSERT INTO jd_user_table(name,email,lid,lpw,birthdate,kanri_flg,life_flg)VALUES(:name,:email,:lid,:lpw,:birthdate,:kanri_flg,0)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':email', $email, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':lpw', $lpw, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':birthdate', $birthdate, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':kanri_flg', $kanri_flg, PDO::PARAM_INT); //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

// // 自動返信メール(お客様へ)
// mb_language("Japanese");
// mb_internal_encoding("UTF-8");

// $header = null;
// $auto_reply_subject = null;
// $auto_reply_text = null;
// date_default_timezone_set('Asia/Tokyo');

// // ヘッダー情報を設定
// $header = "MIME-Version: 1.0\n";
// $header .= "From: Jearnaling Diary <kzk.djm.828@gmail.com>\n";
// $header .= "Reply-To:Jearnaling Diary <no-reply@kekechama515.sakura.ne.jp>\n"; 

// // 件名を設定
// $auto_reply_subject = '【Jearnaling Diary】ご登録ありがとうございます。';

// // 本文を設定
// $auto_reply_text = "*注意*Gs課題のテストメールです" . "\n";
// $auto_reply_text .= "$name" . "様" . "\n\n";
// $auto_reply_text .= "登録完了しました。" . "\n";
// $auto_reply_text .= "担当者から連絡します。" . "\n\n";
// $auto_reply_text .= "hogehoge。";

// // メール送信
// mb_send_mail( $_POST['email'], $auto_reply_subject, $auto_reply_text, $header);



//４．データ登録処理後
if ($status == false) {
  sql_error($stmt);
} else {
  // データベースからユーザーの情報を取得
  $val = $stmt->fetch(PDO::FETCH_ASSOC);
  
  $_SESSION["chk_ssid"]  = session_id();
  $_SESSION["name"]      = $val['name'];
  $_SESSION["id"]        = $val['id'];
  
  //Login成功時（select.phpへ）
  redirect("index5.php");
}