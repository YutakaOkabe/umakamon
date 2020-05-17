<?php
session_start();
require('../dbconnect.php');

if (!isset($_SESSION['join'])){
    header('Location: index.php');
    exit();
}

if(!empty($_POST)){
    $statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?, created=NOW()');
    $statement->execute(array(
        $_SESSION['join']['name'],
        $_SESSION['join']['email'],
        sha1($_SESSION['join']['password']),
        $_SESSION['join']['image'],
    ));
    unset($_SESSION['join']);
    // sessionには次々値が入るのでデータベースに記入したら前のデータはすぐに削除
    header('Location: thanks.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>
</head>
<body>
<h1>会員登録</h1>
<p>登録内容を確認してください</p>
<form action="" method="post">
    <input type="hidden" name="action" value="submit">
    <!-- 変数actionに値submitという値を入れた隠し要素を入力しているので，「登録する」を押すとこの隠し要素が送信される-->
    <dl>
        <dt>ニックネーム</dt>
        <dd>
            <?php print(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)) ?>
        </dd>
        <dt>メールアドレス</dt>
        <dd>
            <?php print(htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES)) ?>
        </dd>
        <dt>パスワード</dt>
        <dd>
            <?php 
                $password_len = strlen($_SESSION['join']['password']);
                for ($i=1; $i<=$password_len; $i++){
                    print('*');
                }
            ?>
        </dd>
        <dt>写真など</dt>
        <dd>
            <?php if(!empty($_SESSION['join']['image'])): ?>
            <img src="../member_picture/<?php print(htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES)); ?>" alt="ユーザの写真" width="200" height="200">
            <?php endif; ?>
        </dd>
    </dl>
    <a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する">
    <!-- &laquo; '<' , &nbsp; 半角スペース-->
</form>
    
</body>
</html>