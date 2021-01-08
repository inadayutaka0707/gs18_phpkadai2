<?php
    function connectDB(){
        try{
            $db = new PDO('mysql:dbname=gs_kadai;host=localhost;charset=utf8','root','root');
            return $db;
        }catch(PDOException $e){
            exit('DBConnectError:'.$e->getMessage());
        }
    }
?>