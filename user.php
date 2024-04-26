l<?php
session_start();
//※htdocsと同じ階層に「includes」を作成してfuncs.phpを入れましょう！
//include "../../includes/funcs.php";
include "funcs.php";
// sschk();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<script src="js/jquery-2.1.3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <link rel="stylesheet" href="css/stylesheet.css">
<title>ログイン</title>
</head>
<body>
  <header>
    <div class="container">
      <div class="header-left">
        <h1><i>Journaling Diary</i></h1>
      </div>
      <!-- <div class="header-right">
        <div class="login" id="login-show">ログイン</div>
      </div> -->
    </div>
  </header>
<!-- Head[End] -->

<!-- Main[Start] -->
<main>


  <div class="jumbotron">
    <form method="post" action="user_insert.php"> 
   <fieldset>
    <legend>新規ユーザー登録</legend>
     <label>名前：<input type="text" name="name"></label><br>
     <label>メールアドレス:<input type="text" name="email"></label><br>
     <label>Login ID：<input type="text" name="lid"></label><br>
     <label>Login PW：<input type="text" name="lpw"></label><br>
     <label>生年月日：<input type="date" name="birthdate"></label><br>
     <label>管理FLG：
      一般<input type="radio" name="kanri_flg" value="0">
      管理者<input type="radio" name="kanri_flg" value="1">
    </label>
    <br>
     <!-- <label>退会FLG：<input type="text" name="life_flg"></label><br> -->
     <input type="submit" value="送信">
    </fieldset>
  </div>
</form>
</main>
<!-- Main[End] -->


</body>
</html>
