<?php
    session_start();
    require_once('funcs.php');
    require_once('function.php');

    if(!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"]!=session_id()){
        echo "LOGINしてからきてちょ<br>";
        echo '<button><a href="index.html">もどる</a></button>';
        exit();
    }

    $followid = $_SESSION['name'];
    $userid = $_SESSION['id'];
    $db = connectDB();

    //選択したユーザーの処理
    $sql = "SELECT * FROM gs_bm_table WHERE name=:name";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':name', h($followid), PDO::PARAM_STR);
    $status = $stmt->execute();

    $view="";
    if($status==false){
        $error = $stmt->errorInfo();
        echo '何か問題があったので最初からお願いします';
        exit('<br><button><a href="index.html">登録画面へ戻る</a></button>');
    }else{
        $row = $stmt->fetch();
        // $view .= '<p>'.$row["name"].'</p>';
        // $view .= '<p>'.$row["email"].'</p>';
        // $view .= '<p>'.$row["sex"].'</p>';
        // $view .= '<p>'.$row["age"].'歳</p>';
        // echo $row['name'];
    }

    //ログインしているユーザーの処理
    $sql2 = "SELECT * FROM gs_bm_table WHERE id=:id";
    $stmt2 = $db->prepare($sql2);
    $stmt2->bindValue(':id', h($userid), PDO::PARAM_STR);
    $status2 = $stmt2->execute();

    $view2="";
    if($status2==false){
        $error = $stmt2->errorInfo();
        echo '何か問題があったので最初からお願いします';
        exit('<br><button><a href="index.html">登録画面へ戻る</a></button>');
    }else{
        $row2 = $stmt2->fetch();
        // $view2 .= '<p>'.$row2["name"].'</p>';
        // $view2 .= '<p>'.$row2["email"].'</p>';
        // $view2 .= '<p>'.$row2["sex"].'</p>';
        // $view2 .= '<p>'.$row2["age"].'歳</p>';
        // echo $row['name'];
    }

    $serch = $db->prepare('SELECT * FROM gs_follow_table');
    $status3 = $serch->execute();

    if($status3==false){
        $error = $serch->errorInfo();
        echo '何か問題があったので最初からお願いします';
        exit('<br><button><a href="index.html">登録画面へ戻る</a></button>');
    }else{
        // $row3 = $serch->fetch();
        // $count=0;
        while($view = $serch->fetch(PDO::FETCH_ASSOC)){
            if($view["userid"] == $row2["id"]){
                if($view["followid"]==$row["id"]){
                    $row3userid = $view["userid"];
                    $row3followid = $view["followid"];
                    // echo $view["userid"]."　";
                    // echo $view["followid"]."<br>";
                }
            }
        }
    }
?>

<!-- フォロー数を出す処理 -->
<?php
    $sql2 = 'SELECT * FROM gs_follow_table';
    $stmt2 = $db->prepare($sql2);
    $stmt2->bindValue(':userid', h($row["name"]), PDO::PARAM_STR);
    $status2 = $stmt2->execute();

    $follow="";
    $follower="";
    $followcount = 0;
    $followercount = 0;
    if($status2==false){
        $error = $stmt->errorInfo();
        echo '何か問題があったので最初からお願いします';
        exit('<br><button><a href="index.html">登録画面へ戻る</a></button>');
    }else{
        while($result = $stmt2->fetch(PDO::FETCH_ASSOC)){
            if($result["userid"] == $row["id"]){
                $followcount++;
            }
            if($result["followid"] == $row["id"]){
                $followercount++;
            }
            $follow = $followcount;
            $follower = $followercount;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="profilebody">
    <form action="follow.php" method="post" name="follow">
        <label class="filelabel">
            <img src="<?=$row['image']?>" width="100px" height="100px">
            <!-- <input type="file" name="image" accept="image/*" class="filehidden"> -->
        </label><br>
        <label>
            <p><?=$row['name']?></p>
        </label>
        <input type="hidden" name="userid" value="<?=$row2['id']?>">
        <input type="hidden" name="followid" value="<?=$row['id']?>">
    </form>
    <form action="followdelete.php" method="post" name="delete1">
        <input type="hidden" name="userid" value="<?=$row2['id']?>">
        <input type="hidden" name="followid" value="<?=$row['id']?>">
    </form>
        <?php
            if($row3userid==$row2["id"]){
                if($row3followid==$row["id"]){
                    echo '<button><a href="javascript:delete1.submit()">ふぉろー介助</a></button><br>';
                }
            }else{
                echo '<button><a href="javascript:follow.submit()">ふぉろー</a></button><br>';
            }
        ?>
    <p><?=$follow?>人フォロー中　　<?=$follower?>人のフォロワー</p>

    <a href="tweet.php">戻る</a>
</body>
</html>