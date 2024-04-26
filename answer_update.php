<?php
session_start();

$users_id = $_SESSION["id"];

    
    //1. POSTデータ取得
    $date = $_POST["date"];
    $answers = $_POST["answers"];


  
    //３．データ登録SQL作成
    $sql ="UPDATE jd_answer_table SET answers=:answers, date=:date, q_id=:q_id, WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':answers', $answers, PDO::PARAM_STR);
    $stmt->bindValue(':date', $date, PDO::PARAM_STR);
    $stmt->bindValue(':q_id', $q_id, PDO::PARAM_INT); // ここで適切な変数をバインドする
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  
    $status = $stmt->execute();
  
    //４．データ登録処理後
    if($status==false){
        //*** function化する！*****************
      
        sql_error($stmt);
    }else{
        //*** function化する！*****************
       redirect("answer_select.php");
    } 

?>