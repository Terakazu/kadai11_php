<?php
session_start();

// mb_internal_encoding("UTF-8");

$csrfToken = filter_input(INPUT_POST, '_csrf_token');

// csrf tokenを検証
if (
    empty($csrfToken)
    || empty($_SESSION['_csrf_token'])
    || $csrfToken !== $_SESSION['_csrf_token']
) {
    exit('不正なリクエストです');
}

// 本来はここでemailのバリデーションもかける
$email = filter_input(INPUT_POST, 'email');

// pdoオブジェクトを取得
include("funcs.php");
$pdo=db_conn();

// emailがusersテーブルに登録済みか確認
$stmt = $pdo->prepare("SELECT * FROM jd_user_table WHERE email=:email AND life_flg=0"); 
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch(\PDO::FETCH_OBJ);

// 未登録のメールアドレスであっても、送信完了画面を表示
// 「未登録です」と表示すると、万が一そのメールアドレスを知っている別人が入力していた場合、「このメールアドレスは未登録である」と情報を与えてしまう
if (!$user) {
    require_once './views/email_sent.php';
    exit();
}

// 既にパスワードリセットのフロー中（もしくは有効期限切れ）かどうかを確認
// $passwordResetUserが取れればフロー中、取れなければ新規のリクエストということ
$sql = 'SELECT * FROM jd_token_table WHERE `email` = :email';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email, \PDO::PARAM_STR);
$stmt->execute();
$passwordResetUser = $stmt->fetch(\PDO::FETCH_OBJ);

if (!$passwordResetUser) {
    // $passwordResetUserがいなければ、仮登録としてテーブルにインサート
    $sql = 'INSERT INTO jd_token_table (`email`, `token`, `token_sent_at`) VALUES(:email, :token, :token_sent_at)';
} else {
    // 既にフロー中の$passwordResetUserがいる場合、tokenの再発行と有効期限のリセットを行う
    $sql = 'UPDATE jd_token_table SET `token` = :token, `token_sent_at` = :token_sent_at WHERE `email` = :email';
}

// password reset token生成
$passwordResetToken = bin2hex(random_bytes(32));

// password_resetsテーブルへの変更とメール送信は原子性を保ちたいため、トランザクションを設置する
// メール送信に失敗した場合は、パスワードリセット処理自体も失敗させる
try {
    $pdo->beginTransaction();

    // ユーザーを仮登録
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
    $stmt->bindValue(':token', $passwordResetToken, \PDO::PARAM_STR);
    $stmt->bindValue(':token_sent_at', (new \DateTime())->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
    $stmt->execute();

    $reset_token = filter_input(INPUT_POST, 'reset_token'); // リセットトークンを取得


// 以下、mail関数でパスワードリセット用メールを送信
// mb_language("Japanese");

// URLはご自身の環境に合わせてください
$to = $email;
$url = "http://kekechama515.sakura.ne.jp/php/php02/views/reset_form.php?token={$passwordResetToken}";

$subject =  'パスワードリセット用URLをお送りします';

$message = '24時間以内に下記URLへアクセスし、パスワードの変更を完了してください。' . "\r\n\r\n" . $url . "\r\n\r\n" . 'このメールは送信用です。';
// $message = '24時間以内に下記URLへ';

// SMTPサーバーの設定
$smtpServer = 'smtp.kekechama515.com'; // さくらのSMTPサーバーのホスト名
$smtpUsername = 'journalingdiary'; // さくらのSMTPサーバーのユーザー名
$smtpPassword = 'kzkkzk828'; // さくらのSMTPサーバーのパスワード
$smtpPort = 587; // さくらのSMTPサーバーのポート番号


    // 送信元
    $from = "journalingdiary@kekechama515.jp";
    // 送信元メールアドレス
    $from_mail = "journalingdiary@kekechama515.jp";
    // 送信者名
    $from_name = "Journaling Diary事務局";
    // 送信者情報の設定
    $header = '';
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $header .= "From: {$from_name} <{$from_mail}>\r\n";
    $header .= "Reply-To: {$from_mail}\r\n";
    $header .= "X-Mailer: PHP/" . phpversion() . "\r\n";
            
          
// メール送信
$isSent = mb_send_mail($to, $subject, $message, $header);

if (!$isSent) {
    throw new \Exception('メール送信に失敗しました。');
}

// メール送信まで成功したら、password_resetsテーブルへの変更を確定
$pdo->commit();

} catch (\Exception $e) {
$pdo->rollBack();

exit($e->getMessage());
}


// 送信済み画面を表示
require_once './views/email_sent.php';