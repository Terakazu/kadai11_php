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
  <!-- "signup-modal"というidをつけてください -->
  <!-- <div class="signup-modal-wrapper" id="signup-modal">
    <!-- 新規登録用のモーダル部分を表示してください -->
    <div class="modal">
   
        <div class="close-modal">
        <div id="close-modal">
                    <i class="fa fa-close"></i>
                  </div>
                  <div>
      </div>
      </div>

      <div id="signup-form">
        <h2>ログイン</h2>
        <form name="form1" action="login_act.php" method="post">
        <input class="form-control" type="text" name="lid" placeholder="ログインID">
        <input class="form-control" type="password" name="lpw" placeholder="パスワード">
        <input class="submit" type="submit" value="ログイン">
        </form>
      </div>
      <!--↓↓追加↓↓-->
      <a href="show_request_form.php" class="c-link">パスワードをお忘れの場合</a>
      <a href="user.php" class="registration">新規登録はこちら</a>
    </div>
   
    
    
  </div>
  <!-- <div class="login-modal-wrapper" id="login-modal">
    <div class="modal">
    <div>
        <div class="close-modal">
        <div id="close-modal">
        <i class="fa fa-2x fa-times"></i>
      </div>
      </div> -->

      <!-- <div class=login>
        <h2>ログイン</h2>
        <form name="form1" action="login_act.php" method="post">
          <input class="form-control" type="text" name="lid" placeholder="ログインID">
          <input class="form-control" type="password" name="lpw" placeholder="パスワード">
          <input class="submit" type="submit" value="ログイン">
        </form>
      </div>
    </div>
  </div> -->



<script>
    $(function() {
  $('#login-show').click(function() {
    $('#login-modal').fadeIn();
})
  
  // 「.signup-show」のclickイベントを作成してください
  $('.signup-show').click(function(){
    $('#signup-modal').fadeIn();
  })

  $('#close-modal').click(function(){
    $('#login-modal').fadeOut();
  })
});

</script>
</body>
</html>