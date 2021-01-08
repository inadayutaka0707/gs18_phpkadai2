<!-- ログイン処理 -->
<?php
session_start();
if(!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"]!=session_id()){
    echo "LOGINしてからきてちょ<br>";
    echo '<button><a href="index.html">もどる</a></button>';
    exit();
}

    require_once('funcs.php');
    require_once('function.php');

    $id = $_SESSION["id"];

    $user = connectDB();

    $sql = "SELECT * FROM gs_bm_table WHERE id=:id";
    $stmt = $user->prepare($sql);
    $stmt->bindValue(':id', h($id), PDO::PARAM_STR);
    $login = $stmt->execute();

    $view="";
    if($login==false){
        $error = $stmt->errorInfo();
        echo '何か問題があったので最初からお願いします';
        exit('<br><button><a href="index.html">登録画面へ戻る</a></button>');
    }else{
        $row = $stmt->fetch();
    }
?>

<!-- tweet処理 -->
<?php
    $comment = $user->prepare("SELECT * FROM gs_tweet_table");
    $allComment = $comment->execute();

    $view="";
    $count=0;
    if($allComment==false){
        echo "<p>コメントを表示できません</p>";
        echo '<p><a href="index.html">最初からやり直してください</a></p>';
        exit;
    }else{
        while($result = $comment->fetch(PDO::FETCH_ASSOC)){
            $view = '</div>'.$view;
            $view = '</form>'.$view;
            $view = '<p>'.h($result["date"]).'</p>'.$view;
            $view = '<p>'.h($result["tweet"]).'</p>'.$view;
            $view = '<input type="hidden" name="name" value="'.h($result["name"]).'">'.$view;
            $view = '<a href="javascript:profile'.$count.'.submit()">'.h($result["name"]).'</a>'.$view;
            $view = '<img src="'.h($result["image"]).'" width="50px" height="50px">'.$view;
            $view = '<form name="profile'.$count.'" action="profile.php" method="post">'.$view;
            $view = '<div class="comment">'.$view;
            $count++;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
</head>
<body onload="init();">
    <div class="leftside">
        <form name="profilesetting" action="profile.php" method="post">
            <button><a href="javascript:profilesetting.submit()">プロフィール設定</a></button>
            <input type="hidden" name="name" value="<?=$row["name"]?>">
        </form>
    </div>
    <dev class="tweetbody">
        <div class="tweet">
            <form name="profile" action="profile.php" method="post">
                <img src="<?=$row['image']?>">
                <div class="formleft">
                    <a href="javascript:profile.submit()"><?=$row["name"]?></a>
                    <input type="hidden" name="name" value="<?=$row["name"]?>">
                </div>
            </form>
            <form action="tweetsave.php" method="post">
                <div class="formcenter">
                    <textarea name="tweet" cols="44" rows="1" wrap="hard" id="text" maxlength="200" placeholder="あなたの気持ちをどうぞ"></textarea>
                </div>
                <input type="hidden" name="name" value="<?=$row["name"]?>">
                <input type="hidden" name="image" value="<?=$row["image"]?>">
                <input type="hidden" name="email" value="<?=$row["email"]?>">
                <div class="formright">
                    <input type="submit" value="投稿">
                </div>
            </form>
        </div>
        <div class="mainContent">
            <?=nl2br($view)?>
        </div>
    </dev>
    <div class="rightside">
        <button><a href="logout.php">ログアウト</a></button>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
</body>
</html>