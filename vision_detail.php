<?php
session_start();
$id =$_GET["id"];
//１．PHP

$users_id = $_SESSION["id"];

include("funcs.php");
$pdo = db_conn();

//２．データ登録SQL作成
$sql="SELECT * FROM jd_vision_table 
      WHERE id = :id;
ORDER BY indate DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id',  $id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

//３．データ表示
$values = "";
if($status==false) {
  sql_error($stmt);
}

//1行のデータ取得
$v    =  $stmt->fetch(); //PDO::FETCH_ASSOC[カラム名のみで取得できるモード]
// var_dump($v);
// $json = json_encode($values,JSON_UNESCAPED_UNICODE);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/jquery-2.1.3.min.js"></script>
    <title>vision making</title>
    <link rel="stylesheet" href="css/sample2.css">
</head>
<body>
<h2><i>Vision Making</i></h2>
<button id="showView" onclick="window.location.href='vision_select.php'">ビジョンを見る</button><br>

<form action="vision_update.php" method="post" enctype="multipart/form-data">
<!-- <label for="datepicker">select the date:</label>
    <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>"> -->
  <div class="question">
    もし、2週間、自由に使えるとしたら、あなたは何をしますか？<br>
    思いつくことをたくさんあげてみましょう！（1/5）<br>
    <textarea name="question1" cols="20" rows="10"><?=h($v["question1"])?></textarea>
    <button type="button" class="next-btn">次へ</button>
  </div>
  <div class="question">
    今書いた中から一つ選ぶとすると何ですか？（2/5）<br>
    <textarea name="question2" cols="20" rows="3"><?=h($v["question2"])?></textarea>
    <button type="button" class="prev-btn">前へ</button>
    <button type="button" class="next-btn">次へ</button>
  </div>
  <div class="question">
    なぜそれをやりたいのでしょうか？（3/5）<br>
    <textarea name="question3" cols="20" rows="10"><?=h($v["question3"])?></textarea>
    <button type="button" class="prev-btn">前へ</button>
    <button type="button" class="next-btn">次へ</button>
  </div>
  <div class="question">
    それを完了した時のことを想像してみてください。<br>
    どんな気持ちですか？（4/5）<br>
    <textarea name="question4" cols="20" rows="10"><?=h($v["question4"])?></textarea>
    <button type="button" class="prev-btn">前へ</button>
    <button type="button" class="next-btn">次へ</button>
  </div>
  <div class="question">
    3年後、あなたはどんな自分になっていたいですか？（5/5）<br>
    <textarea name="question5" cols="20" rows="10" placeholder="仕事、プライベート、健康面、経済面など"><?=h($v["question5"])?></textarea>
    <button type="button" class="prev-btn">前へ</button>
    <button type="button" class="next-btn">次へ</button>
  </div>

    <input type="file" name="image" > <!-- 画像をアップロードするためのinput -->
                <img src="<?= $v['image_path'] ?>"
                <input type="hidden" name="delete_image" value="<?=h($v["image_path"])?>"> <!-- 画像削除のためのフラグ -->
               <button type="submit" name="delete_image_button">写真を削除</button> <!-- 写真削除ボタン -->
    <input type="hidden" name="id" value="<?=h($v["id"])?>"> 
  <button type="submit" name="update_button" id="save3">更新する</button>
</form>


</body>
</html>