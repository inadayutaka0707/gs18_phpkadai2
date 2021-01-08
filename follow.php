<?php
    session_start();
    require_once('funcs.php');
    require_once('function.php');

    $userid = $_POST["userid"];
    $followid = $_POST["followid"];

    $db = connectDB();

    // $serch = $db->prepare('SELECT * FROM gs_follow_table');
    // $status = $serch->execute();

    // if($status==false){
    //     $error = $stmt->errorInfo();
    //     echo '何か問題があったので最初からお願いします';
    //     exit('<br><button><a href="index.html">登録画面へ戻る</a></button>');
    // }else{
    //     // $row = $serch->fetch();
    //     $count=0;
    //     while($view = $serch->fetch(PDO::FETCH_ASSOC)){

    //         if($view["followid"] == $followid){

    //             echo $view["userid"]."　";
    //             echo $view["followid"]."<br>";
    //         }
    //     }
    // }

    


    $sql = 'INSERT INTO gs_follow_table(id, userid, followid)VALUES(NULL, :userid, :followid)';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':userid', h($userid), PDO::PARAM_STR);
    $stmt->bindValue(':followid', h($followid), PDO::PARAM_STR);
    $result = $stmt->execute();

    if($result==false){
        $error = $stmt->errorInfo();
        echo '何か問題があったので最初からお願いします';
        exit('<br><button><a href="index.html">登録画面へ戻る</a></button>');
    }else{
        header('Location: otherprofile.php');
    }