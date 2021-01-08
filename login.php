<?php
    session_start();
    require_once('funcs.php');
    require_once('function.php');

    $name = $_POST["name"];
    $password = $_POST["password"];

    $pdo = connectDB();

    $sql = "SELECT * FROM gs_bm_table WHERE name=:name";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':name', h($name), PDO::PARAM_STR);
    $login = $stmt->execute();

    if($login==false){
        header('Location: error.html');
        exit;
    }else{
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row["name"] != $name){
            echo '<p>登録されていません。</p>';
            echo '<a href="save.html">新規登録画面へ</a>';
            exit;
        }else if($row["password"]==""){
            echo '<p>パスワードが設定できていません。</p>';
            echo '<a href="password.php?email='.$row["email"].'">パスワード設定画面へ</a>';
            exit;
        }else if(password_verify($password,$row['password'])){
            $_SESSION["id"] = $row["id"];
            $_SESSION["chk_ssid"] = session_id();
            header('Location: tweet.php');
            exit;
        }else{
            echo '<p>パスワードが一致していません。</p>';
            echo '<a href="index.html">ログイン画面へ</a>';
            exit;
        }
    }