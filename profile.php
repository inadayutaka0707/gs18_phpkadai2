<?php
    session_start();
    require_once('funcs.php');
    require_once('function.php');

    if(!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"]!=session_id()){
        echo "LOGINしてからきてちょ<br>";
        echo '<button><a href="index.html">もどる</a></button>';
        exit();
    }

    $id = $_SESSION["id"];
    $name = $_POST["name"];

    $db = connectDB();

    $sql = "SELECT * FROM gs_bm_table WHERE id=:id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', h($id), PDO::PARAM_STR);
    $status = $stmt->execute();

    $view="";
    if($status==false){
        $error = $stmt->errorInfo();
        echo '何か問題があったので最初からお願いします';
        exit('<br><button><a href="index.html">登録画面へ戻る</a></button>');
    }else{
        $row = $stmt->fetch();
        $view .= '<p>'.$row["name"].'</p>';
        $view .= '<p>'.$row["email"].'</p>';
        $view .= '<p>'.$row["sex"].'</p>';
        $view .= '<p>'.$row["age"].'歳</p>';
    }

    

    if($row["name"] != $name){
        $_SESSION["name"] = $name;
        header('Location: otherprofile.php');
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
    <form action="profileupdate.php" method="post" enctype="multipart/form-data">
        <label class="filelabel"><img src="<?=$row['image']?>" width="100px" height="100px">
            <input type="file" name="image" accept="image/*" class="filehidden">
        </label><br>
        <label><span>名前</span>
            <input type="text" name="name" value="<?=$row['name']?>"><br>
        </label>
        <input type="hidden" name="id" value="<?=$row['id']?>"><br>
        <input type="hidden" name="email" value="<?=$row['email']?>"><br>
        <input type="submit" value="保存">
    </form>
    <p><?=$follow?>人フォロー中　　<?=$follower?>人のフォロワー</p>
    <a href="tweet.php">戻る</a>
</body>
</html>
