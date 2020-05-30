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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!--
    <link rel="stylesheet" href="css/style.css">
    -->

    <!-- 画像を表示するCSS -->
    <style>
    .imagePreview {
        width: 180px;
        height: 180px;
        background-position: center center;
        background-size: cover;
        -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, .3);
        display: inline-block;
    }
    </style>

    <title>アカウント作成</title>
</head>
<body>
<img src="../image/logo.png" class="rounded mx-auto mt-5 d-block" alt="うまかもんロゴ">
<div class="container">
    <div class="text-center">
        <p class="mt-3">登録内容を確認してください</p>
        <form action="" method="post">
            <input type="hidden" name="action" value="submit">
            <!-- 変数actionに値submitという値を入れた隠し要素を入力しているので，「登録する」を押すとこの隠し要素が送信される-->
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th scope="row">ニックネーム</th>
                            <td><?php print(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)) ?></td>
                        </tr>
                        <tr>
                            <th scope="row">メールアドレス</th>
                            <td><?php print(htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES)) ?></td>
                        </tr>
                        <tr>
                            <th scope="row">パスワード</th>
                            <td><?php 
                        $password_len = strlen($_SESSION['join']['password']);
                        for ($i=1; $i<=$password_len; $i++){
                            print('*');
                        }
                    ?>
                        </tr>
                        <tr>
                            <th scope="row">アイコン</th>
                            <!-- 画像指定してない場合でも日時が保存されているので文字数で判別 -->
                            <?php if(strlen(($_SESSION['join']['image'])) === 14): ?>
                            <td>指定なし</td>
                            <?php else: ?>               
                            <td>
                                <img src="../member_picture/<?php print(htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES)); ?>" class="imagePreview">
                            </td>
                            <?php endif; ?>
            
                        </tr>
                    </tbody>
                </table>
            <div class="row justify-content-around mt-5">
                <a class="btn btn-secondary col-3" href="index.php?action=rewrite" role="button">書き直す</a>
                <button type="submit" class="btn btn-primary col-3">登録する</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>