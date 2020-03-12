<?php

$dsn = database';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//mission4-2
$sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date char(32),"
    . "password char(32)"
    .");";
$stmt = $pdo->query($sql);

$comment1 = "コメント";
$name1 = "名前";
$handan = 0;

if(empty($_POST["edit"]) != TRUE){
    /*編集部分*/
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach($results as $row){
        if($row['id'] == $_POST["edit"]) {
            $pass = $row['password'];
            $idd = $row['id'];
            $name = $row['name'];
            $comment = $row['comment'];
        }   
    }
    
    if($pass == $_POST["password3"]){
        $comment1 = $comment;
        $name1 = $name;
        $handan = $_POST["edit"];
    }else {
        echo "パスワードが違います。<br>";
    }
    
}

?>
<html>
    <head>
    <meta charset="utf-8">
    </head>
    <body>
        <form method="POST" action="">
        <input type="hidden" name="select" value="<?php echo $handan; ?>"><br>
        <input type="text" name="name" placeholder="<?php echo $name1; ?>"><br>
        <input type="text" name="comment" placeholder="<?php echo $comment1; ?>"><br>
        <input type="password" name="password1" placeholder="パスワード">
        <input type="submit" value="送信">
        </form> 
        <form method="POST" action="">
        <input type="text" name="remove" placeholder="消去対象番号"><br>
        <input type="password" name="password2" placeholder="パスワード">
        <input type="submit" value="消去">
        </form>
        <form method="POST" action="">
            <input type="text" name="edit" placeholder="編集対象番号"><br>
            <input type="password" name="password3" placeholder="パスワード">
            <input type="submit" value="編集">
        </form>
    </body>
</html>
<?php

        $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, password) VALUES (:name,:comment,:date,:password)");

        if(empty($_POST["comment"]) != TRUE && empty($_POST["name"]) != TRUE && empty($_POST["remove"]) == TRUE && empty($_POST["edit"]) == TRUE && $_POST["select"] == 0){
            if(empty($_POST["password1"]) == TRUE) {
                echo "パスワードがありません。<br>";
            }else{
                
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                
                $new_date = date("Y/m/d H:i:s");
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $date = $new_date;
                $password = $_POST["password1"];
                
                $sql -> execute();
                
                //mission4-6　表示
                $sql = 'SELECT * FROM tbtest';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach($results as $row){
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['date'].'<br>';
                echo "<hr>";
                }
                
            }
        } elseif(empty($_POST["remove"]) != TRUE && empty($_POST["edit"]) == TRUE){
            //消去
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach($results as $row){
                if($row['id'] == $_POST["remove"]) {
                    $pass = $row['password'];
                }   
            }
            
            
            if(empty($_POST["password2"]) == TRUE){
                echo "パスワードがありません。<br>";
            }else{
                /*消去部分*/
                if($pass != $_POST["password2"]){
                    /*パスワードとあっているか(あっていない場合)*/
                    echo "パスワードが違います。<br>";
                } else {
                    //mission4-8　消去
                    $id = $_POST["remove"];//消去する投稿番号
                    $sql = 'delete from tbtest where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
            //表示
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach($results as $row){
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['date'].'<br>';
            echo "<hr>";
            }
        } elseif(empty($_POST["name"]) != TRUE &&
            empty($_POST["comment"]) != TRUE && $_POST["select"] != 0){
            //編集
            //パスワードがない場合
            if(empty($_POST["password1"]) == TRUE) {
                echo "パスワードがありません。<br>";
            }else{
                /*編集部分*/
                //mission4-7　編集
                $id = $_POST["select"]; //変更する投稿番号
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $date = date("Y/m/d H:i:s");
                $password = $_POST["password1"];
                
                $sql = 'update tbtest set name=:name,comment=:comment,date=:date,password=:password where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->execute();
                
                //mission4-6　表示
                $sql = 'SELECT * FROM tbtest';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach($results as $row){
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['date'].'<br>';
                echo "<hr>";
                }
            }
        }
?>