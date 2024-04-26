<?php
session_start();
    
include("funcs.php");
sschk();
$pdo = db_conn();

// ユーザーのIDを取得
$users_id = $_SESSION["id"];

// データ登録SQL作成
$sql = "SELECT * 
        FROM jd_answer_table 
        JOIN jd_question_table
        ON jd_answer_table.q_id = jd_question_table.id 
        WHERE users_id = :users_id";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':users_id', $users_id, PDO::PARAM_INT);
$status = $stmt->execute();

// データ表示
if($status == false) {
    sql_error($stmt);
}

// 全データ取得
$values = $stmt->fetchAll(PDO::FETCH_ASSOC); // PDO::FETCH_ASSOCを使用して連想配列として取得する


// JSONに値を渡す場合に使う
$json = json_encode($values, JSON_UNESCAPED_UNICODE);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="js/jquery-2.1.3.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<title>Journaling Daiary読み込み</title>
<link rel="stylesheet" href="css/sample2.css">

</head>

<body>
<!-- Head[Start] -->
<header>
    <!-- Head[Start] -->
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <?=$_SESSION["name"]?>さん、こんにちは！
      
      <button class="navbar-brand" onclick="window.location.href='logout.php'">ログアウト</button>
      <button class="navbar-brand" onclick="window.location.href='index.php'">日記を書く</button>
      <button class="navbar-brand" onclick="window.location.href='vision.php'">ビジョンを書く</button>
        <button class="navbar-brand" onclick="window.location.href='answer.php'">問いを探す</button>
      </div>
    </div>
  </nav>
<!-- Head[End] -->
        <h1><i>Journaling Diary</i></h1>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->

<h3>ジャーナリング</h3>
<div id="cardContainer">

    </div>
</div>

 
</div>


<!-- Main[End]  -->

<script>
  //JSON受け取り
const obj = <?=$json?>;
console.log(obj);



// **********************日記カード部分************************
// ***********日付をフォーマットする関数*************
function formatDate(dateString) {
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = date.getMonth() + 1;
        const day = date.getDate();
        const dayOfWeek = ['日', '月', '火', '水', '木', '金', '土'][date.getDay()];
        return `${year}年${month}月${day}日(${dayOfWeek})`;
    }
// ****************日付終わり***************
// カードを生成する関数
function createCard(data) {
  const cardContainer = document.createElement('div');
        cardContainer.classList.add('cardContainer');

        const formattedDate = formatDate(data.date);

        // 日付要素を生成
    const dateElement = document.createElement('div');
    dateElement.classList.add('date');
    dateElement.setAttribute('data-date', data.date);
    dateElement.textContent = formattedDate;

    // cardContainerに日付要素を追加
    cardContainer.appendChild(dateElement);

        const card = document.createElement('div');
        card.classList.add('card');
    
    
    
    card.innerHTML = `
        <div class="card-content3">
        <div class="question2">Q. ${data.question}</div>
        <div class="answer">A. ${data.answers}</div>
        <div class="date">${data.date}</div>
        
        <button class="links" onclick="location.href='answer_detail.php?id=${data.id}'">更新</button>
        <button class="links" onclick="deletePost(${data.id})">削除</button>
         </div>  
    `;
     
   
    return card;
}


  // カードを表示する
const cardContainer = document.getElementById('cardContainer');

// 日付ごとにカードをグループ化するためのオブジェクト
const dateGroups = {};

// カードをグループ化する
obj.forEach(data => {
    const formattedDate = formatDate(data.date);

    // 日付をキーとして、カードをグループに追加する
    if (!dateGroups[formattedDate]) {
        dateGroups[formattedDate] = [];
    }
    dateGroups[formattedDate].push(createCard(data));
});

// グループごとにカードを表示する
Object.keys(dateGroups).forEach(date => {
    const dateElement = document.createElement('div');
    dateElement.classList.add('date');
    dateElement.textContent = date;

    // 日付要素をカードコンテナに追加
    cardContainer.appendChild(dateElement);

    // 日付ごとのカードをカードコンテナに追加
    dateGroups[date].forEach(card => {
        cardContainer.appendChild(card);
    });
});



function deletePost(postId) {
    // ユーザーに確認ダイアログを表示し、OKがクリックされたら削除処理を実行
    if (confirm("投稿を削除しますがよろしいですか？")) {
        // OKがクリックされた場合、削除処理を行う
        location.href = `delete.php?id=${postId}`; // delete.phpにリダイレクト
    }
}



</script>

</body>
</html>



