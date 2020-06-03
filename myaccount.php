<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){
    // ログインしていて1時間以内にアクションがある場合
        $_SESSION['time'] = time();
        // ユーザー情報を引き出す
        $members = $db->prepare('SELECT * FROM members WHERE id=?');
        $members->execute(array($_SESSION['id']));
        // ここではまだテーブルでいっぱい呼び出されていることになるので，fetch()で1行だけ取り出す
        $member = $members->fetch();
    }else{
        header('Location: login.php');
    }

$postAmounts = $db->prepare('SELECT COUNT(*) AS cnt FROM posts WHERE members_id=?');
$postAmounts->execute(array($member['id']));
$postAmount = $postAmounts->fetch();

?>

<!DOCTYPE html> <!--HTML5に準拠したサイトならこれだけでOK　昔のにも対応したいならさらに記述が必要-->
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!--
    <link rel="stylesheet" href="css/style.css">
    -->


    <title>アカウント情報</title>
</head>
<body>

<!-- ナビゲーションメニュー -->
<nav class="navbar navbar-expand-md navbar-light bg-light">
    <!-- ロゴ -->
    <a class="navbar-brand" href="index.php"><img src="image/logo_onlylogo.png" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">うまかもん</a>
    <!-- 狭い時にハンバーガーにする記述 -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>

    <!-- ナビゲーションコンテンツ -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">タイムライン</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="post.php">ポスト</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="map.php">マップ</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                アカウント情報<span class="sr-only">(current)</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="myaccount.php">アカウント情報確認</a>
                <a class="dropdown-item" href="login.php">別アカウントでログイン</a>
                <a class="dropdown-item" href="logout.php">ログアウト</a>
            </li>
        </ul>

        <!-- ログインアカウントの表示 -->
        <a class="navbar-text my-auto" href="myaccount.php"><img class="rounded" src="member_picture/<?php print(htmlspecialchars($member['picture'], ENT_QUOTES)); ?>" width="40" height="40" class="d-inline-block align-top" alt="" loading="lazy">&nbsp;<?php print(htmlspecialchars($member['name'], ENT_QUOTES)); ?>さんでログイン中</a>
    </div>
</nav>

<!-- アカウント情報の表示 -->
<div class="container">
    <h4 class="mt-5">アカウント情報</h4>
    <div class="text-center mt-5">
        <table class="table table-striped">
        <tbody>
            <tr>
                <th scope="row">アカウント名</th>
                <td><?php print(htmlspecialchars($member['name'], ENT_QUOTES)); ?></td>
                <!-- アカウント名変更 -->
                <td><a href="#">変更</a></td>
            </tr>
            <tr>
                <th scope="row">アイコン</th>
                <td><img class="rounded" src="member_picture/<?php print(htmlspecialchars($member['picture'], ENT_QUOTES)); ?>" width="200" height="200" class="d-inline-block align-top" alt="" loading="lazy"></td>
                <!-- アイコン変更 -->
                <td class="align-middle"><a href="#">変更</a></td>
            </tr>
            <tr>
                <th scope="row">総投稿数</th>
                <td><?php print($postAmount['cnt']); ?></td>
                <td></td>
            </tr>
            <tr>
                <th scope="row">アカウント作成日</th>
                <td><?php print(htmlspecialchars($member['created'], ENT_QUOTES)); ?></td>
                <td></td>
            </tr>

        </tbody>
        </table>

        <!-- アカウント削除 -->

    </div>
</div>
    

<!-- BootstrapのJS読み込み <- 読み込んでないとメニュー開かない　-->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>


</body>
</html>