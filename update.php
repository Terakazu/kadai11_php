<?php
session_start();

$users_id = $_SESSION["id"];

// 1. 削除ボタンが押されたかどうかを検出
if (isset($_POST['delete_image_button'])) {
  // 2. image_pathをデータベースから削除
  $id = $_POST["id"];
  include("funcs.php");
  $pdo = db_conn();

  // 画像パスをデータベースから削除するSQL文を作成
  $sql_delete_image = "UPDATE jd_an_table2 SET image_path = NULL WHERE id=:id";
  $stmt = $pdo->prepare($sql_delete_image);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  
  // 3. ユーザーに削除が完了したことを通知するメッセージを表示
  echo "画像が削除されました。";
  
  
 // 4. detail.phpにリダイレクト
 header("Location: detail.php?id=" . $id);
 exit; // スクリプトの実行を終了
}

// 画像をアップロードする
$upload_dir = "images/"; // 画像を保存するディレクトリ
$image_name = $_FILES["image"]["name"]; // アップロードされた画像のファイル名


// 画像が選択されているかを確認
if (!empty($image_name)) {
    $image_path = $upload_dir . $image_name; // アップロードされた画像の保存先パス

    // 画像を指定のディレクトリに移動します
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
        echo "画像のアップロードが完了しました。";
    } else {
        echo "画像のアップロードに失敗しました。";
    }
} else {
    // 画像がアップロードされていない場合は、元の画像パスを保持します
    $image_path = null;
}


// 更新処理
if (isset($_POST['update_button'])) {
    // 画像削除フラグがセットされているかを確認し、セットされていない場合は元の画像パスを保持
    if (!isset($_POST['delete_image_flag'])) {
        // 元の画像パスを取得
        $id = $_POST["id"];
        include("funcs.php");
        $pdo = db_conn();
        $sql_get_image_path = "SELECT image_path FROM jd_an_table2 WHERE id=:id";
        $stmt = $pdo->prepare($sql_get_image_path);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $original_image_path = $stmt->fetchColumn();
        // 画像パスがセットされていない場合は、フォームからの値を代入
        $image_path = empty($_POST['image_path']) ? $original_image_path : $_POST['image_path'];
    }
    
    //1. POSTデータ取得
    $date = $_POST["date"];
    $memo = $_POST["memo"];
    $emotion1 =$_POST["emotion1"];
    $emotion2 =$_POST["emotion2"];
    $emotion3 =$_POST["emotion3"];

    // 画像がアップロードされたかどうかをチェックし、適切な変数をバインドする
    if (!empty($image_name)) {
        $upload_path = $upload_dir . $image_name;
        $image_binding = $upload_path;
    } else {
        $image_binding = $image_path;
    }
  
    //３．データ登録SQL作成
    $sql ="UPDATE jd_an_table2 SET memo=:memo, emotion1=:emotion1, emotion2=:emotion2, emotion3=:emotion3, date=:date, image_path=:image_path WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':memo', $memo, PDO::PARAM_STR);
    $stmt->bindValue(':emotion1', $emotion1, PDO::PARAM_STR);
    $stmt->bindValue(':emotion2', $emotion2, PDO::PARAM_STR);
    $stmt->bindValue(':emotion3', $emotion3, PDO::PARAM_STR);
    $stmt->bindValue(':date', $date, PDO::PARAM_STR);
    $stmt->bindValue(':image_path', $image_binding, PDO::PARAM_STR); // ここで適切な変数をバインドする
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  
    $status = $stmt->execute();
  
    //４．データ登録処理後
    if($status==false){
        //*** function化する！*****************
      
        sql_error($stmt);
    }else{
        //*** function化する！*****************
       redirect("select4.php");
    } 
  }
  
?>