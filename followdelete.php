<?php
    require_once('funcs.php');
    require_once('function.php');

    $userid = $_POST["userid"]; //ログインしているユーザー
    $followid = $_POST["followid"]; //対象のユーザー

    $db = connectDB();
    $stmt = $db->prepare('SELECT * FROM gs_follow_table');
    $status = $stmt->execute();

    if($status==false){
        $error = $stmt->errorInfo();
        echo '何か問題があったので最初からお願いします';
        exit('<br><button><a href="index.html">登録画面へ戻る</a></button>');
    }else{
        while($row = $stmt->fetch(PDO::PARAM_STR)){
            if($userid == $row["userid"]){
                if($followid == $row["followid"]){
                    $sql = 'DELETE FROM gs_follow_table WHERE id=:id';
                    $stmt2 = $db->prepare($sql);
                    $stmt2->bindValue(':id', h($row["id"]), PDO::PARAM_INT);
                    $status2 = $stmt2->execute();

                    if($status2==false){
                        $error = $stmt2->errorInfo();
                        echo '何か問題があったので最初からお願いします';
                        exit('<br><button><a href="index.html">登録画面へ戻る</a></button>');
                    }else{
                        header('Location: otherprofile.php');
                        exit;
                    }
                }
            }
        }
    }



