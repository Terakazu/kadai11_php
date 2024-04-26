<?php
session_start();
$id =$_GET["id"];
//１．PHP

$users_id = $_SESSION["id"];

include("funcs.php");
$pdo = db_conn();

// データ登録SQL作成
$sql = "SELECT * 
        FROM jd_answer_table 
        JOIN jd_question_table
        ON jd_answer_table.q_id = jd_question_table.id 
        WHERE users_id = :users_id";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':users_id', $users_id, PDO::PARAM_INT);
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
<!--
２．HTML
以下にindex.phpのHTMLをまるっと貼り付ける！
理由：入力項目は「登録/更新」はほぼ同じになるからです。
※form要素 input type="hidden" name="id" を１項目追加（非表示項目）
※form要素 action="update.php"に変更
※input要素 value="ここに変数埋め込み"
-->
<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8" />
    <script src="js/jquery-2.1.3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <link rel="stylesheet" href="css/sample2.css">
    <title>ジャーナリングアプリ</title>
    <style>
        body {
            background-image: url(img/background.png);
            font-family:Georgia, 'Times New Roman', Times, serif
            }
        </style>

</head>


<body>
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <?=$_SESSION["name"]?>さん、こんにちは！
      
      <button class="navbar-brand" onclick="window.location.href='logout.php'">ログアウト</button>
      <button class="navbar-brand" onclick="window.location.href='index.php'">日記を書く</button>
      <button class="navbar-brand" onclick="window.location.href='vision.php'">ビジョンを書く</button>
      <button class="navbar-brand" onclick="window.location.href='select4.php'">マイページ</button>
      </div>
    </div>
  </nav>
        <div class=head>
            <h1><i>Journaling Diary</i></h1>
        </div>
    </header>



    <div id="cardContainer">
            <div class="cardContainer">
                <div class="card-content">
                    <div class="question">
                    <form method="post" action="answer_update.php">
                    <input type="hidden" name="q_id" value="<?php echo $card['id']; ?>">
                    <div class="answer-container" id="answerContainer<?php echo $card['id']; ?>" style="display: none;">
                        <textarea name="answers" cols="15" rows="5" style="width: 350px;"></textarea>
                        <button type="submit">送信</button>
                    </div>
                </form>
                </div>
            </div>

    </div>


    <script>
       
   
    </script>
</body>

</html>
