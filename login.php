<?php
session_start();
require('dbconnect.php');

if (!empty($_POST)) {
    // ログインボタンが押されている
    if ($_POST['email'] === '') {
        $error['email'] = 'blank';
    }
    if ($_POST['password'] === '') {
        $error['password'] = 'blank';
    } else {
        $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
        $login->execute(array(
            $_POST['email'],
            sha1($_POST['password'])
        ));
        $member = $login->fetch();
        // 上でヒットするアカウントがあれば行で返ってくる
        if ($member) {
            // 誰がいつログインしたのかをindexページに渡す
            $_SESSION['id'] = $member['id'];
            $_SESSION['time'] = time();
            header('Location: index.php');
            exit();
        } else {
            // ヒットするアカウントがなかったのでログイン失敗
            $error['login'] = 'failed';
        }
    }
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
<h1>ログインする</h1>
    <p>メールアドレスとパスワードを入力してログインしてください</p>
    <p>入会手続きがまだの方はこちら→<a href="join/index.php">入会手続きをする</a></p>
    <form action="" method="post">
    <!--フォーム内でnameで名づけられたデータをaction属性のURLへmethod属性のHTTPのPOSTメソッドで送信-->
        <dl>
            <dt>メールアドレス</dt>
            <dd>
                <input type="email" name="email" value="">
                <?php if($error['email']==='blank'): ?>
                <p>* メールアドレスを入力してください</p>
                <?php endif; ?>
            </dd>
            <dt>パスワード</dt>
            <dd>
                <input type="password" name="password" value="">
                <?php if($error['password']==='blank'): ?>
                <p>* パスワードを入力してください</p>
                <?php endif; ?>
                <?php if($error['login']==='failed'): ?>
                <p>* ログインに失敗しました．情報を正しく入力してください．</p>
                <?php endif; ?>
            </dd>
            <dt>ログイン情報の記録</dt>
            <dd>
                <input id="save" type="checkbox" name="save">
                <label for="save">次回からはログイン情報を記録する</label>
                <!--label:ボックスの横に説明を追加--><!--for:説明を加えたいボックスのidを入力-->
            </dd>
        </dl>
        <input type="submit" value="ログインする">
        <!--buttonタグでも送信できるし，button使うともっと複雑なボタンコンテンツを作れるらしい-->
    </form>
    
    
</body>
</html>