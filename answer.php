<?php
session_start();
include("funcs.php");
sschk();
$pdo = db_conn();

// ユーザーのIDを取得
$users_id = $_SESSION["id"];

// データ取得SQL作成
$sql = "SELECT * FROM jd_question_table";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

// データ表示
if ($status == false) {
    sql_error($stmt);
}

// 全データ取得
$cards =  $stmt->fetchAll(PDO::FETCH_ASSOC);

// ランダムに3つのカードを選択
function selectRandomCards($cards) {
    $selected_indexes = array_rand($cards, 3);
    $selected_cards = array_intersect_key($cards, array_flip($selected_indexes));
    return $selected_cards;
}

// 初回表示時にランダムに3つのカードを選択
$selected_cards = selectRandomCards($cards);
?>

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
            font-family: Georgia, 'Times New Roman', Times, serif
        }

        .cardContainer {
            width: 30%; /* カードの幅を設定 */
            margin: 0 10px 20px 0; /* カードの間隔を設定 */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: white;
        }

        #cardContainer {
            display: flex; /* Flexbox を使用してカードを横並びにする */
            flex-wrap: wrap; /* カードが横幅を超えた場合に折り返す */
            justify-content: flex-start; /* 左寄せにする */
        }

        #showAnotherBtn {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: rgba(255, 99, 132);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .card-content {
            width:350px;
            text-align:left;
        }

        .answer-container {
            display: none;
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


    <h3><?=$_SESSION["name"]?>さんへの問い</h3>
    <div id="cardContainer">
        <?php foreach ($selected_cards as $card) : ?>
            <div class="cardContainer">
                <div class="card-content">
                    <div class="question" data-question-id="<?php echo $card['id']; ?>"><?php echo $card['question']; ?></div>
                    <!-- 質問IDを送信するための hidden フィールドを追加 -->
                    <form method="post" action="answer_insert.php">
                    <input type="hidden" name="q_id" value="<?php echo $card['id']; ?>">
                    <div class="answer-container" id="answerContainer<?php echo $card['id']; ?>" style="display: none;">
                        <textarea name="answers" cols="15" rows="5" style="width: 350px;"></textarea>
                        <button type="submit">送信</button>
                    </div>
                </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <button id="showAnotherBtn">別の問いを表示する</button>

    <script>
       
    // ランダムに3つのカードを選択する関数
    function selectRandomCards(cards) {
        const selectedIndexes = Array.from({ length: 3 }, () => Math.floor(Math.random() * cards.length));
        return selectedIndexes.map(index => cards[index]);
    }

    // カードを生成して表示する関数
    function displayCards(cards) {
        const cardContainer = document.getElementById('cardContainer');
        cardContainer.innerHTML = ''; // カードを一旦クリア

        cards.forEach(card => {
            const cardElem = document.createElement('div');
            cardElem.classList.add('cardContainer');
            cardElem.innerHTML = `
                <div class="card-content">
                    <div class="question" data-question-id="${card.id}">${card.question}</div>
                    <div class="answer-container" id="answerContainer${card.id}" style="display: none;">
                        <form method="post" action="answer-insert.php">
                            <textarea name="answers" cols="15" rows="5" style="width: 350px;"></textarea>
                            <button type="submit">送信</button>
                        </form>
                    </div>
                </div>
            `;
            cardContainer.appendChild(cardElem);
        });

        // カード要素にクリックイベントリスナーを再度追加
        const cardElements = document.querySelectorAll('.question');
        cardElements.forEach(card => {
            card.addEventListener('click', cardClickHandler);
        });
    }

    // カードをクリックしたときの処理
    function cardClickHandler(event) {
        // クリックされたカードの問いのIDを取得
        const questionId = event.currentTarget.dataset.questionId;
        // クリックされたカードに対応する回答欄を表示
        const answerContainer = document.querySelector(`#answerContainer${questionId}`);
        answerContainer.style.display = 'block';
    }

    // 「別の問いを表示する」ボタンのクリックイベントリスナー
    document.getElementById('showAnotherBtn').addEventListener('click', function() {
        // PHPで取得したカードデータをJavaScriptに渡す
        const cardsData = <?php echo json_encode($cards); ?>;
        // ランダムに3つのカードを選択
        const selectedCards = selectRandomCards(cardsData);
        // 選択されたカードを表示
        displayCards(selectedCards);
    });

    // 初回表示時にカード要素にクリックイベントリスナーを追加
    const cardElements = document.querySelectorAll('.question');
    cardElements.forEach(card => {
        card.addEventListener('click', cardClickHandler);
    });

    </script>
</body>

</html>
