<?php
session_start();
    
include("funcs.php");
sschk();
$pdo=db_conn();

// ユーザーのIDを取得
$users_id = $_SESSION["id"];

//２．データ登録SQL作成
$sql="SELECT * FROM jd_vision_table 
      WHERE users_id = :users_id;
      ORDER BY indate DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':users_id', $users_id, PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
$values="";
if($status==false) {
 sql_error($stmt);
}

//全データ取得
$values =  $stmt->fetchAll(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC[カラム名のみで取得できるモード]
//JSONに値を渡す場合に使う
$json = json_encode($values,JSON_UNESCAPED_UNICODE);

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

<body?>
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
<h3>My vision</h3>
<div id="cardContainer">

    </div>





<!-- Main[End]  -->

<script>
  //JSON受け取り
const obj = <?=$json?>;
console.log(obj);

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

        const formattedDate = formatDate(data.indate);

        // 日付要素を生成
    const dateElement = document.createElement('div');
    dateElement.classList.add('date');
    dateElement.setAttribute('data-date', data.date);
    dateElement.textContent = formattedDate;

    // cardContainerに日付要素を追加
    cardContainer.appendChild(dateElement);

        const card = document.createElement('div');
        card.classList.add('card2');
    

    
    card.innerHTML = `
        <div class="card-content2">
        <div class="question">${data.indate}</div>
        <p>Q1.もし、2週間、自由に使えるとしたら、あなたは何をしますか？<p>
        <div class="question">${data.question1}</div>
        <p>Q2.Q1の中から一つ選ぶとすると何ですか？</p>
        <div class="question">${data.question2}</div>
        <p>Q3.なぜそれをやりたいのでしょうか？</p>
        <div class="question">${data.question3}</div>
        <p>Q4.それを完了した時にどんな気持ちになりますか？</p>
        <div class="question">${data.question4}</div>
        <p>Q5.3年後、あなたはどんな自分になっていたいですか？</p>
        <div class="question">${data.question5}</div>
          
        
        <button class="links2" onclick="location.href='vision_detail.php?id=${data.id}'">更新</button>
         <button class="links2" onclick="location.href='vision_delete.php?id=${data.id}'">削除</button>
         </div>
         ${data.image_path ? `<img src="${data.image_path}">` : ''}

    `;


     
   
    return card;
}

  // カードを表示する
  const cardContainer = document.getElementById('cardContainer');

// objの配列をループして、カードを生成して表示する
obj.forEach(data => {
    const card = createCard(data);
    cardContainer.appendChild(card);
});







</script>
</body>
</html>