<?php
session_start();

$request = filter_input_array(INPUT_POST);

// csrf tokenが正しければOK
if (
    empty($request['_csrf_token'])
    || empty($_SESSION['_csrf_token'])
    || $request['_csrf_token'] !== $_SESSION['_csrf_token']
) {
    exit('不正なリクエストです');
}

// パスワードのバリデーション
if (
    empty($request['lpw']) ||
    empty($request['lpw_confirmation']) ||
    $request['lpw'] !== $request['lpw_confirmation']
) {
    exit('パスワードが正しく入力されていません');
}

// pdoオブジェクトを取得
include("funcs.php");
$pdo = db_conn();

// tokenに合致するユーザーを取得
$sql = 'SELECT * FROM jd_token_table WHERE `token` = :token';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':token', $request['password_reset_token'], \PDO::PARAM_STR);
$stmt->execute();
$passwordResetuser = $stmt->fetch(\PDO::FETCH_OBJ);

// どのレコードにも合致しない無効なtokenであれば、処理を中断
if (!$passwordResetuser) exit('無効なURLです');

// テーブルに保存するパスワードをハッシュ化
$hashedPassword = password_hash($request['lpw'], PASSWORD_BCRYPT);

// usersテーブルとpassword_resetsテーブルの原子性を原始性を保証するため、トランザクションを設置
try {
    $pdo->beginTransaction();

    // 該当ユーザーのパスワードを更新
    $sql = 'UPDATE jd_user_table SET `lpw` = :lpw  WHERE `email` = :email';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':lpw', $hashedPassword, \PDO::PARAM_STR);
    $stmt->bindValue(':email', $passwordResetuser->email, \PDO::PARAM_STR);
    $stmt->execute();

    // 用が済んだので、パスワードリセットテーブルから削除
    $sql = 'DELETE FROM jd_token_table WHERE `email` = :email';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $passwordResetuser->email, \PDO::PARAM_STR);
    $stmt->execute();

    $pdo->commit();

} catch (\Exception $e) {
    $pdo->rollBack();

    exit($e->getMessage());
}

echo 'パスワードの変更が完了しました。';