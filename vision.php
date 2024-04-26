<?php
session_start();
include("funcs.php");
sschk();
$pdo=db_conn();

// ユーザーのIDを取得
$users_id = $_SESSION["id"];
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
<nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <?=$_SESSION["name"]?>さん、こんにちは！
      
      <button class="navbar-brand" onclick="window.location.href='logout.php'">ログアウト</button>
      <button class="navbar-brand" onclick="window.location.href='index.php'">日記を書く</button>
      <button class="navbar-brand" onclick="window.location.href='vision_select.php'">ビジョンを見る</button>
      <button class="navbar-brand" onclick="window.location.href='answer.php'">問いを探す</button>
      </div>
    </div>
  </nav>
<h2><i>Vision Making</i></h2>

<form action="vision_insert.php" method="post" enctype="multipart/form-data">

  <div class="question">
    もし、2週間、自由に使えるとしたら、あなたは何をしますか？<br>
    思いつくことをたくさんあげてみましょう！（1/5）<br>
    <textarea name="question1" cols="20" rows="10"></textarea>
    <button type="button" class="next-btn">次へ</button>
  </div>
  <div class="question">
    今書いた中から一つ選ぶとすると何ですか？（2/5）<br>
    <textarea name="question2" cols="20" rows="3"></textarea>
    <button type="button" class="prev-btn">前へ</button>
    <button type="button" class="next-btn">次へ</button>
  </div>
  <div class="question">
    なぜそれをやりたいのでしょうか？（3/5）<br>
    <textarea name="question3" cols="20" rows="10"></textarea>
    <button type="button" class="prev-btn">前へ</button>
    <button type="button" class="next-btn">次へ</button>
  </div>
  <div class="question">
    それを完了した時のことを想像してみてください。<br>
    どんな気持ちですか？（4/5）<br>
    <textarea name="question4" cols="20" rows="10"></textarea>
    <button type="button" class="prev-btn">前へ</button>
    <button type="button" class="next-btn">次へ</button>
  </div>
  <div class="question">
    3年後、あなたはどんな自分になっていたいですか？（5/5）<br>
    <textarea name="question5" cols="20" rows="10" placeholder="仕事、プライベート、健康面、経済面など"></textarea>
    <button type="button" class="prev-btn">前へ</button>
    <button type="button" class="next-btn">次へ</button>
  </div>
  <input type="file" name="image" id="fileinput"> <!-- 画像をアップロードするためのinput -->
    <img id="preview" src="" style="display: none;"> 
  <button type="submit" class="send" id="save3">送信する</button>
</form>


<script>
    // "fileinput" の変更イベントを監視
$("#fileinput").on("change", async function() {
    // 選択されたファイルを取得
    const file = this.files[0];
    
    // ファイルをプレビューするための処理
    const reader = new FileReader();
    reader.onload = function(e) {
        // プレビュー用の要素に画像を表示
        $("#preview").attr("src", e.target.result);
    };
    reader.readAsDataURL(file);
    $("#preview").show();
});
</script> 
</body>
</html>