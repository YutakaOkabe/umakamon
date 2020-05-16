<?php
session_start();
if (!isset($_SESSION['join'])){
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
</head>
<body>
<h1>会員登録</h1>
    <p>登録内容を確認してください</p>
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
                <input type="file" name="image">
            </dd>
        </dl>
        <a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する">
        <!-- &laquo; '<' , &nbsp; 半角スペース-->

    </form>
    
    
</body>
</html>